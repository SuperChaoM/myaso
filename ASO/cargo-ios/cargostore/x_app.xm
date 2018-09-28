#import "macro.h"
#import "StoreTaskManager.h"
#import "FloatingWindowViewController.h"
#import "constants.h"

%hook ASAppDelegate

- (_Bool)application:(id)arg1 didFinishLaunchingWithOptions:(id)arg2 {
    %log;
    %orig;

    [[StoreTaskManager sharedManager] appStore_startAuthenticate];

    return YES;
}

- (_Bool)application:(id)arg1 openURL:(NSURL *)theURL sourceApplication:(id)arg3 annotation:(id)arg4 {
	// %log;
	%orig;

	if ([@"itms-appss" isEqualToString:theURL.scheme]
	 && [@"finance-app.itunes.apple.com" isEqualToString:theURL.host]
	 && [@"/challenge" isEqualToString:theURL.path]){
		[[StoreTaskManager sharedManager] startCaptchaVerify];
	}
}

- (void)applicationDidDisplayFirstPage:(id)arg1 {
    %log;
    %orig;

    [self showFloatWindow];
}

%new
- (void)showFloatWindow {
  	UIWindow *win = MSHookIvar<UIWindow *>(self, "_window");
	NSLog(@"root vc is %@", win.rootViewController);
	UIViewController *rootVC = win.rootViewController;
	[StoreTaskManager sharedManager].tabBarController = rootVC;

	CGFloat sh = [UIScreen mainScreen].bounds.size.height;
	CGFloat w = OVERLAY_VIEW_WIDTH;
	CGFloat h = OVERLAY_VIEW_HEIGHT;
	CGFloat x = 0;
	CGFloat y = sh - h;

	GCD_AFTER_MAIN(1)
		FloatingWindowViewController *myVC = [[FloatingWindowViewController alloc] init];
		[rootVC addChildViewController:myVC];
		[myVC.view setFrame:CGRectMake(x, y, w, h)];
		[rootVC.view addSubview:myVC.view];
		[myVC didMoveToParentViewController:rootVC];
	GCD_END
}

%end
