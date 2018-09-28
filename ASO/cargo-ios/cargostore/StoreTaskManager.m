#import "StoreTaskManager.h"
#import "macro.h"
#import "msg.h"
#import "task_ret_codes.h"
#import <notify.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>

@interface StoreTaskManager() {
    CPDistributedMessagingCenter *_mc;

    // captcha
    int _totalCapNum;
    int _gotCapImageNum;
}

@end

@implementation StoreTaskManager

+ (instancetype)sharedManager {
  static StoreTaskManager *_instance = nil;
  static dispatch_once_t onceToken;
  dispatch_once(&onceToken, ^{
      _instance = [[StoreTaskManager alloc] init];
      [_instance _init];
  });
  return _instance;
}

- (NSDictionary *)appStore_currentTask {
    return [NSDictionary dictionaryWithContentsOfFile:CG_PATH(@"Task.plist")];
}

- (void)startCaptchaVerify {
    _totalCapNum++;
}

- (BOOL)shouldCaptureCaptchaImage {
    NSLog(@" # captcha #  %d / %d", _gotCapImageNum, _totalCapNum );
    return (_totalCapNum > _gotCapImageNum);
}

- (void)captureCaptchaImageDone {
    _gotCapImageNum++;
}

- (void)appStore_startAuthenticate {
	 //[_mc sendMessageName:MSG_START_AUTHENTICATE userInfo:nil];
   NSDictionary *_taskDict = [self appStore_currentTask];
   NSString *username = [_taskDict objectForKey:@"email"];
   NSString *password = [_taskDict objectForKey:@"password"];
   NSLog(@"###cargo auto login with username %@", username);
   id authCtx = [[objc_getClass("SSMutableAuthenticationContext") alloc] init];
   [authCtx setAccountName: username];
   [authCtx setInitialPassword: password];

   id authReq = [[objc_getClass("SSAuthenticateRequest") alloc] initWithAuthenticationContext:authCtx];
   [authReq startWithAuthenticateResponseBlock:^(id rsp){
       NSDictionary *authDict = [rsp responseDictionary];
       NSString *dsId = [authDict objectForKey:@"dsPersonId"];
       NSString *pwToken = [authDict objectForKey:@"passwordToken"];
       if (dsId && password){
           [[StoreTaskManager sharedManager] _handleFinishAuthenticate:RET_CODE_OK];
       }else{
         NSString *msg = [authDict objectForKey:@"customerMessage"];
         int codeIfErr = RET_CODE_ERR_AUTHENTICATE;
         if(msg != nil && ([msg containsString:@"\u505c\u7528"] || [msg containsString:@"\u9501\u5b9a"] || [msg containsString:@"\u7981\u7528"])){
           codeIfErr = ACCOUNT_ERR_LOCKED;
         }
         [self _handleFinishAuthenticate:codeIfErr];
       }
   }];
}

- (void)appStore_suspendApplication {
	 [[UIApplication sharedApplication] performSelector:@selector(suspend)];
}

- (void)appStore_markTaskFail:(int)retCode {
    const char *done_msg = [MSG_FINISH_TASK cStringUsingEncoding:NSUTF8StringEncoding];
    int done_token;
    notify_register_check(done_msg, &done_token);
    notify_set_state(done_token, retCode);
    notify_post(done_msg);

    [self performSelector:@selector(appStore_suspendApplication)];
}

- (void)appStore_markTaskSucc {
    [self appStore_markTaskFail:RET_CODE_OK];
}

- (void)appStore_simulateClickAtX:(int)x y:(int)y {
    [_mc sendMessageName:MSG_SIMULATE_CLICK userInfo:@{ @"x": @(x), @"y": @(y) }];
}

- (void)_init {
	 _mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	 rocketbootstrap_distributedmessagingcenter_apply(_mc);
	 [_mc registerForMessageName:MSG_FINISH_AUTHENTICATE target:self selector:@selector(_handleFinishAuthenticate:userInfo:)];

    // notify
    const char *auth_msg = [MSG_FINISH_AUTHENTICATE cStringUsingEncoding:NSUTF8StringEncoding];
    int auth_token;
    notify_register_dispatch(auth_msg, &auth_token, dispatch_get_main_queue(), ^(int token){
        NSLog(@"###cargo got notify MSG_FINISH_AUTHENTICATE");
        int ret;
        notify_get_state(token, &ret);
        [[StoreTaskManager sharedManager] _handleFinishAuthenticate:ret];
    });

    const char *buy_msg = [MSG_FINISH_PURCHASE cStringUsingEncoding:NSUTF8StringEncoding];
    int buy_token;
    notify_register_dispatch(buy_msg, &buy_token, dispatch_get_main_queue(), ^(int token){
        NSLog(@"###cargo got notify MSG_FINISH_PURCHASE");
        int ret;
        notify_get_state(token, &ret);
        [[StoreTaskManager sharedManager] _handleFinishPurchase:ret];
    });

    // observe keyboard event
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(_keyboardDidShow:) name:UIKeyboardDidShowNotification object:nil];

}

- (void)_handleFinishAuthenticate:(int)ret {
    if (self.authenticateDone){
        return;
    }

    self.authenticateDone = YES;
    NSLog(@"###cargo _handleFinishAuthenticate %d", ret);
    if (ret == RET_CODE_OK){

      // click search tab
      NSArray *vcs = [self.tabBarController viewControllers];
      long len = (long)[vcs count];
      NSLog(@"###cargo has %ld tab bars", len);
			int flowWindowIndex = -1;
			//Find FlowWindowIndex
			for(int i = 0; i < len; i ++){
				 if([vcs[i] respondsToSelector:@selector(copyrightLabel)]){
					 	flowWindowIndex = i;
						break;
				 }
			}
			NSLog(@"###cargo float window index at %d ",flowWindowIndex);
			int searchBarItemIndex = (flowWindowIndex == -1 || flowWindowIndex < (len-2))?(len-2):(len-3);
			NSLog(@"###cargo searchBarItem index at %d ",searchBarItemIndex);
      if (searchBarItemIndex >=0 && searchBarItemIndex <= len-1){
          id searchTabBarItem = [vcs[searchBarItemIndex] tabBarItem];
          NSLog(@"###cargo search tab bar is %@", searchTabBarItem);
          [self.tabBarController _tabBarItemClicked:searchTabBarItem];
      }

      // go search
      SEL search = @selector(nkcg_goSearch);
      if ([self.searchBarController respondsToSelector:search]){
          [self.searchBarController performSelector:search withObject:nil afterDelay:1];
      }

      // delegate to ui
      if ([self.delegate respondsToSelector:@selector(delegate_itunesDidAuthenticate:)]){
          [self.delegate delegate_itunesDidAuthenticate:@{ @"ok": @(YES), @"msg": @"登录ok" }];
      }
   }else{
      // delegate to ui
      if ([self.delegate respondsToSelector:@selector(delegate_itunesDidAuthenticate:)]){
          [self.delegate delegate_itunesDidAuthenticate:@{ @"ok": @(NO), @"msg": @"登录失败" }];
      }

      [self appStore_markTaskFail:ret];
   }
}

- (void)_handleFinishPurchase:(int)ret {
   NSLog(@"###cargo _handleFinishPurchase %d", ret);
   if (ret == RET_CODE_OK) {
       // delegate to ui
      if ([self.delegate respondsToSelector:@selector(delegate_itunesDidPurchase:)]){
          [self.delegate delegate_itunesDidPurchase:@{ @"ok": @(YES), @"msg": @"购买ok" }];
      }
   }else{
      // delegate to ui
      if ([self.delegate respondsToSelector:@selector(delegate_itunesDidPurchase:)]){
          [self.delegate delegate_itunesDidPurchase:@{ @"ok": @(NO), @"msg": @"购买失败" }];
      }

      [self appStore_markTaskFail:ret];
   }
}

- (void)_updatePurchaseStatus:(NSString *)content {
   NSLog(@"###cargo _updatePurchaseStatus %d", content);
  [self.delegate delegate_itunesDidPurchase:@{ @"ok": @(YES), @"msg": content }];
}

- (void)_keyboardDidShow:(NSNotification *)n {
    NSLog(@"###cargo keyboard did show");
}


@end
