#import "iTunesTaskManager.h"
#import "msg.h"
#import "macro.h"
#import "task_ret_codes.h"
#import <substrate.h>
#import <notify.h>
#import <objc/runtime.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>

@interface iTunesTaskManager()
{
	NSDictionary *_taskDict;
	CPDistributedMessagingCenter *_mc;
}

@end

@implementation iTunesTaskManager

+ (instancetype)sharedManager {
    static iTunesTaskManager *_instance = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _instance = [[iTunesTaskManager alloc] init];
    });
    return _instance;
}

- (void)itunes_start {
	_taskDict = [NSDictionary dictionaryWithContentsOfFile:CG_PATH(@"Task.plist")];
	NSLog(@"###cargo got task dict %@", _taskDict);

	// notify
	const char *auth_msg = [MSG_START_AUTHENTICATE cStringUsingEncoding:NSUTF8StringEncoding];
	int auth_token;
	notify_register_dispatch(auth_msg, &auth_token, dispatch_get_main_queue(), ^(int token){
		NSLog(@"###cargo got notify MSG_START_AUTHENTICATE");
		[[iTunesTaskManager sharedManager] itunes_authenticate];
	});

	const char *done_msg = [MSG_FINISH_TASK cStringUsingEncoding:NSUTF8StringEncoding];
	int done_token;
	notify_register_dispatch(done_msg, &done_token, dispatch_get_main_queue(), ^(int token){
	    NSLog(@"###cargo got notify MSG_FINISH_TASK");
	    int ret;
	    notify_get_state(token, &ret);
	    NSLog(@"###cargo task ret is %d", ret);
	    if (ret == RET_CODE_OK){
	    	// send xp events right now
	    	[[iTunesTaskManager sharedManager] itunes_sendXpEvents];
	    }
	});

	_mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	rocketbootstrap_distributedmessagingcenter_apply(_mc);
	[_mc sendMessageName:MSG_ITUNES_STARTED userInfo:nil];
}

- (void)itunes_authenticate {
	// sign out all account first
	id accStore = [objc_getClass("SSAccountStore") defaultStore];
	[accStore performSelector:@selector(signOutAllAccounts)];

	// authenticate with username in task
	NSString *username = [_taskDict objectForKey:@"email"];
	NSString *password = [_taskDict objectForKey:@"password"];
	if (username == nil || password == nil){
		NSLog(@"###cargo username:%@ or password:%@ is not ready", username, password);
		return;
	}

	NSLog(@"###cargo auto login with username %@", username);
	// trigger itunes authenticate
	id authCtx = [[objc_getClass("SSMutableAuthenticationContext") alloc] init];
	[authCtx setAccountName: username];
	// [authCtx setAltDSID:d[@"AKAltDSID"]];
	[authCtx setInitialPassword: password];

	id authOp = [[objc_getClass("AuthenticateOperation") alloc] initWithAuthenticationContext:authCtx];
	[authOp run];
}

- (void)itunes_sendXpEvents {
	NSLog(@"###cargo send xp evnets --- start");
	id metric = [objc_getClass("MetricsController") sharedInstance];
	NSLog(@"metric %@", metric);
	Ivar ev_ivar = class_getInstanceVariable(objc_getClass("MetricsController"), "_eventController");
	id ev = object_getIvar(metric, ev_ivar);
	NSLog(@"ev %@", ev);
	id op = [[objc_getClass("AnalyticsReportingOperation") alloc] initWithController:ev];
	[op run];
	NSLog(@"###cargo send xp evnets --- done");

	// notify next
	notify_post([MSG_NEXT_TASK cStringUsingEncoding:NSUTF8StringEncoding]);
}

- (void)itunes_handleAuthenticateResult:(NSDictionary *)retDict {
	NSLog(@"###cargo handleAuthenticateResult %@", retDict);
	NSString *msg = [retDict objectForKey:@"msg"];
	int codeIfErr = ([msg containsString:@"\u505c\u7528"] || [msg containsString:@"\u9501\u5b9a"] || [msg containsString:@"\u7981\u7528"])?ACCOUNT_ERR_LOCKED:RET_CODE_ERR_AUTHENTICATE;
	int ret = ([retDict[@"ok"] boolValue] ? RET_CODE_OK : codeIfErr);
	const char *auth_msg = [MSG_FINISH_AUTHENTICATE cStringUsingEncoding:NSUTF8StringEncoding];
  	int auth_token;
	notify_register_check(auth_msg, &auth_token);
	notify_set_state(auth_token, ret);
	notify_post(auth_msg);
}

- (void)itunes_handlePurchaseResult:(NSDictionary *)retDict {
	NSLog(@"###cargo handlePurchaseResult %@", retDict);
	NSString *msg = [retDict objectForKey:@"msg"];
	int codeIfErr = ([msg containsString:@"\u505c\u7528"] || [msg containsString:@"\u9501\u5b9a"] || [msg containsString:@"\u7981\u7528"])?ACCOUNT_ERR_LOCKED:RET_CODE_ERR_PURCHASE;
	GCD_AFTER_MAIN(5)
			int ret = ([retDict[@"ok"] boolValue] ? RET_CODE_OK : codeIfErr);
		const char *buy_msg = [MSG_FINISH_PURCHASE cStringUsingEncoding:NSUTF8StringEncoding];
	  	int buy_token;
		notify_register_check(buy_msg, &buy_token);
		notify_set_state(buy_token, ret);
		notify_post(buy_msg);
	GCD_END
}

@end
