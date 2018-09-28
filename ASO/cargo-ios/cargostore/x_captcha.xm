#import "StoreTaskManager.h"
#import "YunDaMa.h"
#import "SuperHawk.h"
#import "macro.h"
#import "constants.h"
#import "task_ret_codes.h"
#import "SimulateTouch.h"
#import "only_god_can_read.h"
#import <objc/runtime.h>

static NSString *CAPTCHA_YUNDAMA_SOFT_ID = @"4247"; // cargo
static NSString *CAPTCHA_YUNDAMA_SOFT_KEY = @"88e28ecc7d8e23da128cd22f82390e6b";

@class WebView,WebResource, WebDataSource;

// ---------------------- get captcha image data, and recognize it as text
%hook SUWebViewManager

- (void)uiWebView:(WebView *)web resource:(WebResource *)res didFinishLoadingFromDataSource:(WebDataSource *)ds {
	%log;
	%orig;
	NSString *url = [[[ds initialRequest] URL] absoluteString];
	if ([url containsString:@"https://finance-app.itunes.apple.com/challenge"]){
	  NSArray *resources = [ds subresources];
	  for (WebResource *r in resources){
	    NSString *rUrl = [[r URL] absoluteString];
	    NSLog(@"res url is %@  ", rUrl);
	    if([rUrl containsString:@"nuDataValidateCaptchaSrv"]){
	        NSData *validateResult = [r data];
	        NSDictionary *validateDict = [NSJSONSerialization JSONObjectWithData:validateResult options:NSJSONReadingAllowFragments error:nil];
	        NSLog(@"===yt.asobitch=== capDict %@", validateDict);
	        if (validateDict && validateDict[@"nuDetectInfo"]){
	         NSDictionary *detectInfo = validateDict[@"nuDetectInfo"];
	         if (![@"CaptchaCorrect" isEqualToString:detectInfo[@"interdictionResponse"]]){
	            [[StoreTaskManager sharedManager] appStore_markTaskFail:TASK_ERR_BUY_CAP];
							return;
	         }
	       }
	    }
	  }
	}

	if (![[StoreTaskManager sharedManager] shouldCaptureCaptchaImage]){
		return;
	}

	GCD_RUN("com.nkaso.cargo.recognizecaptcha")
		if ([url containsString:@"https://finance-app.itunes.apple.com/challenge"]){
			NSData *gifData = nil;
			NSArray *resources = [ds subresources];
			for (WebResource *r in resources){
				NSString *rUrl = [[r URL] absoluteString];
				NSLog(@"res url is %@  ", rUrl);
				// captcha url example: https://api-us-west-2.ndsopaapl.nudatasecurity.com/1.0/w/3.30.77142/w-855182/captcha/?type=VIDEO&lang=hans&index=0&token=1.w-855182.1.2.EHpxKYdJWwK8JrcvxBIo4g,,.KVl_Uyuz5qR6jdmv_wxr2_le7b8U0nP89CPEa0hdp0-iYkZozSh35mztE_MCFdWXa3QnDoff9tF2B1F_MIOLGa-eqnuzaagYboWof_TE3lSDlzCk0Lki8QI1LVz-QwWWth3RjJqVqH7aBW0s7dJGTBvYUBsRY7sOBOt3_lUiH7Y9x95PWZ3jzIyU45vMWBzfUv0iahlosspnxY2sxv4oVfnNeXAGxvSirrDntx3hKE086kRYP1s5naqoz4dcq0uhhIpLg0cnfuFwxSNvze697wNiQqhkjQFYfJ8ItlD--kEseNx_jjpoMqleohIThNr-bHWc13iWFrtAigWrtvLNqL8yBWtgzYBezsteg00QA8jlKH-PxEokJ7iI-byz3xVEfFcwUzCjS8R7Iky-kGls_g,,&r=rs-wKlGxvMWY7tv56Q6D0xsBQxx&ptype=SCRIPT
				if ([rUrl containsString:@"nudatasecurity.com"] && [rUrl containsString:@"/captcha/?type="]){
					gifData = [r data];
					NSLog(@"###cargo captcha data is found in web data source");
					break;
				}
			}

			[[StoreTaskManager sharedManager] shouldCaptureCaptchaImage];

			if (gifData){

				NSLog(@"刷新GIF数据..");
				[[StoreTaskManager sharedManager] captureCaptchaImageDone];
				[[StoreTaskManager sharedManager] _updatePurchaseStatus:@"Gif已获取，开始打码.."];
				GCD_RUN_MAIN
					NSLog(@"点击打码....");
					[StoreTaskManager sharedManager].canRandomTyping = YES;
					[[NSNotificationCenter defaultCenter] postNotificationName:@"NOTIFICATION_CLICK_INPUT_FIELD" object:nil];
				GCD_END

				// init captcha recognizing
			  /*  YunDaMaConfig *ydmConfig = [[YunDaMaConfig alloc] init];
			    ydmConfig.account = CAPTCHA_YUNDAMA_ACCOUNT;
			    ydmConfig.password = CAPTCHA_YUNDAMA_PASSWORD;
			    ydmConfig.softId = CAPTCHA_YUNDAMA_SOFT_ID;
			    ydmConfig.softKey = CAPTCHA_YUNDAMA_SOFT_KEY;
			    ydmConfig.timeout = CAPTCHA_YUNDAMA_TIMEOUT;
			    YunDaMa *ydm = [[YunDaMa alloc] initWithConfig:ydmConfig];

			    // upload
			    long cid = 0;
			    for (int i=0; i<3; i++){
			        NSLog(@"###cargo try upload captcha image... %d", i);
			        [ydm upload:gifData typeCode:CAPTCHA_YUNDAMA_TYPE cidRet:&cid];
			        if (cid != 0 ){
			            NSLog(@"###cargo uploaded. cid %ld", cid);
			            break;
			        }
			    }

			    if (cid == 0){
			    	return;
			    }

			    // pull result
			    const char * capRet;
			    capRet = NULL;
			    for (int i=0; i<100; i++){
			        NSLog(@"===yt.dragon=== try get captcha text... %d", i);
			        [ydm tryGetResult:cid capRet:&capRet];
			        if (capRet != NULL){
			            NSLog(@"===yt.dragon=== got cap text %s", capRet);
			            break;
			        }
			        [NSThread sleepForTimeInterval:0.5];
			    }

			    NSString *ret = [[NSString alloc] initWithCString:capRet encoding:NSUTF8StringEncoding];
			    if (ret == nil || [@"看不清" isEqualToString:ret]){
			    	return;
			    }*/
					CG_SLEEP(5000);
					const char *capRet;
					SuperHawkConfig *superHawkConfig = [[SuperHawkConfig alloc] init];
				  superHawkConfig.account = CAPTCHA_SUPERHAWK_ACCOUNT;
				  superHawkConfig.password = CAPTCHA_SUPERHAWK_PASSWORD;
				  superHawkConfig.softId = CAPTCHA_SUPERHAWK_SOFT_ID;
				  superHawkConfig.softKey = CAPTCHA_SUPERHAWK_SOFT_KEY;
					superHawkConfig.codeType = CAPTCHA_SUPERHAWK_TYPE;
				  SuperHawk *superHawk = [[SuperHawk alloc] initWithConfig:superHawkConfig];
					[superHawk doCaptcha:gifData capRet:&capRet];

					NSString *ret = [[NSString alloc] initWithCString:capRet encoding:NSUTF8StringEncoding];
			    GCD_RUN_MAIN
			   	 	// notify to captcha text is ready, input it
							NSLog(@"输入打码....");
        			[[NSNotificationCenter defaultCenter] postNotificationName:@"NOTIFICATION_INPUT_CAPTCHA_TEXT" object:ret];
        		GCD_END
			}
		}
	GCD_END
}

%end


// ---------------------- input captcha text, and click next

%hook SUWebViewController

- (void)viewWillAppear:(BOOL)arg1 {
	%log;
	%orig;

	NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
	if (task == nil){
		NSLog(@"###cargo no task, dragon will sleep");
		return;
	}

	if (![self nkcg_isCaptchaHandlerRegister]){
		[self nkcg_setCaptchaHandlerRegister:YES];

		[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(nkcg_simulateClickInputField:) name:@"NOTIFICATION_CLICK_INPUT_FIELD" object:nil];
		[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(nkcg_inputCaptchaTextAndClickNextStep:) name:@"NOTIFICATION_INPUT_CAPTCHA_TEXT" object:nil];
		NSLog(@"###cargo captcha input event is registered");
	}
}

%new
- (BOOL)nkcg_isCaptchaHandlerRegister {
	NSNumber *n = objc_getAssociatedObject(self, "com.nkaso.cargo.captchahandler");
	return [n boolValue];
}

%new
- (void)nkcg_setCaptchaHandlerRegister:(BOOL)yesOrNo {
	if (yesOrNo){
		objc_setAssociatedObject(self, "com.nkaso.cargo.captchahandler", [NSNumber numberWithBool:YES], OBJC_ASSOCIATION_RETAIN_NONATOMIC);
	}else{
		objc_setAssociatedObject(self, "com.nkaso.cargo.captchahandler", nil, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
	}
}

%new
- (void)nkcg_simulateClickInputField:(NSNotification *)n {
	NSLog(@"###cargo got notification NOTIFICATION_CLICK_INPUT_FIELD");
	/* SUWebView */ id webView = [self webView];

	// simulate click the input field
	NSString *top = [webView stringByEvaluatingJavaScriptFromString:@"document.getElementById('nucaptcha-answer').getBoundingClientRect().top"];
	NSString *left = [webView stringByEvaluatingJavaScriptFromString:@"document.getElementById('nucaptcha-answer').getBoundingClientRect().left"];
	NSString *width = [webView stringByEvaluatingJavaScriptFromString:@"document.getElementById('nucaptcha-answer').getBoundingClientRect().width"];
	NSString *height = [webView stringByEvaluatingJavaScriptFromString:@"document.getElementById('nucaptcha-answer').getBoundingClientRect().height"];

	int x = [left intValue];
	int y = 64 + [top intValue];
	int w = [width intValue];
	int h = [height intValue];
	x += ( arc4random() % w);
	y += ( arc4random() % h);

	NSLog(@"###cargo simulate touch (%d, %d)", x, y);
	int pathIndex1 = [SimulateTouch simulateTouch:0 atPoint:CGPointMake(x, y) withType:STTouchDown];
	[SimulateTouch simulateTouch:pathIndex1 atPoint:CGPointMake(x, y) withType:STTouchUp];

	int pathIndex2 = [SimulateTouch simulateTouch:0 atPoint:CGPointMake(x, y) withType:STTouchDown];
	[SimulateTouch simulateTouch:pathIndex2 atPoint:CGPointMake(x, y) withType:STTouchUp];

	CGFloat sw = [UIScreen mainScreen].bounds.size.width;
	CGFloat sh = [UIScreen mainScreen].bounds.size.height;
	GCD_RUN("com.nkaso.cargo.fakekeyboard")
		for (int i=0; i<5; i++){
			CG_SLEEP(0.5 + 0.1*(i%3));
			if ([StoreTaskManager sharedManager].canRandomTyping){
				int x = sw/2 + (arc4random()%10)*10 - (arc4random()%20);
				int y = (sh-216) + (arc4random()%10)*5 - (arc4random()%5);;
				NSLog(@"###cargo simulate touch (%d, %d)", x, y);
				int pathIndex3 = [SimulateTouch simulateTouch:0 atPoint:CGPointMake(x, y) withType:STTouchDown];
				[SimulateTouch simulateTouch:pathIndex3 atPoint:CGPointMake(x, y) withType:STTouchUp];
			}
		}
	GCD_END
}

%new
- (void)nkcg_inputCaptchaTextAndClickNextStep:(NSNotification *)n {
	NSLog(@"###cargo got notification NOTIFICATION_INPUT_CAPTCHA_TEXT");
	[StoreTaskManager sharedManager].canRandomTyping = NO;

	NSString *capText = [n object];
	/* SUWebView */ id webView = [self webView];

	// input captcha text
	NSLog(@"###cargo input captcha text %@", capText);
	[[StoreTaskManager sharedManager] _updatePurchaseStatus:[NSString stringWithFormat:@"打码结果：%@",capText]];
    NSString *js = [NSString stringWithFormat:@"document.getElementById('nucaptcha-answer').value='%@'", capText];
    NSString *jsRet = [webView stringByEvaluatingJavaScriptFromString:js];
    NSLog(@"###cargo input done. js result is %@", jsRet);

    // click next in navigation bar
    /* SUDelayedNavigationItem */ id navItem = [self navigationItemForScriptInterface];
    /* SUBarButtonItem */ id rightBarButtonItem = [navItem rightBarButtonItem];
    /* SUNavigationButton */ UIButton *nextBtn = MSHookIvar<UIButton *>(rightBarButtonItem, "_view");
    [nextBtn sendActionsForControlEvents:UIControlEventTouchUpInside];
    NSLog(@"###cargo click next step done.");
}

%end
