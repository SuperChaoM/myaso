#import "msg.h"
#import "macro.h"
#import <notify.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>
#import "SimulateTouch.h"
#import "only_god_can_read.h"

static int itunesStarted = 0;
static int auth_pending = 0;

%hook SpringBoard

- (void)applicationDidFinishLaunching:(id)arg1 {
	// %log;
	%orig;

	// messages
	CPDistributedMessagingCenter *mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	rocketbootstrap_distributedmessagingcenter_apply(mc);
	[mc runServerOnCurrentThread];
	[mc registerForMessageName:MSG_LAUNCH_APPSTORE target:self selector:@selector(nkcg_launchAppStore)];
	[mc registerForMessageName:MSG_START_AUTHENTICATE target:self selector:@selector(nkcg_startAuthenticate)];
	[mc registerForMessageName:MSG_ITUNES_STARTED target:self selector:@selector(nkcg_itunesDidStart)];
	[mc registerForMessageName:MSG_SIMULATE_CLICK target:self selector:@selector(nkcg_simulateClick:userInfo:)];

	[self performSelector:@selector(nkcg_unlockDevice)];
}


%new
- (void)nkcg_unlockDevice {
	if ([[%c(SBLockScreenManager) sharedInstance] isUILocked]){
		[[%c(SBLockScreenManager) sharedInstance] attemptUnlockWithPasscode: nil];
	}
}


%new
- (void)nkcg_launchAppStore {
	NSLog(@"got MSG_LAUNCH_APPSTORE");
	if ([self respondsToSelector:@selector(launchApplicationWithIdentifier:suspended:)]){
		[self launchApplicationWithIdentifier:@"com.apple.AppStore" suspended:NO];
	}
}


%new
-(void)nkcg_startAuthenticate {
	NSLog(@"got MSG_START_AUTHENTICATE");
	if (itunesStarted == 1){
		notify_post([MSG_START_AUTHENTICATE cStringUsingEncoding:NSUTF8StringEncoding]);
	}else{
		// wait for itunes start
		auth_pending = 1;
	}
}

%new
-(void)nkcg_itunesDidStart {
	NSLog(@"got MSG_ITUNES_STARTED");
	itunesStarted = 1;
	if (auth_pending == 1){
		notify_post([MSG_START_AUTHENTICATE cStringUsingEncoding:NSUTF8StringEncoding]);
		auth_pending = 0;
	}
}

%new
- (void)nkcg_simulateClick:(NSString *)msgName userInfo:(NSDictionary *)userInfo {
	NSLog(@"got MSG_SIMULATE_CLICK");
	int x = [userInfo[@"x"] intValue];
	int y = [userInfo[@"y"] intValue];
	NSLog(@"###cargo do simulate touch (%d, %d)", x, y);
	GCD_RUN("com.nkaso.cargo.dosimulateclick")
		int pathIndex = [SimulateTouch simulateTouch:0 atPoint:CGPointMake(x, y) withType:STTouchDown];
		[SimulateTouch simulateTouch:pathIndex atPoint:CGPointMake(x, y) withType:STTouchUp];
		// CG_SLEEP(200 + arc4random()%200);
		// int pathIndex2 = [SimulateTouch simulateTouch:0 atPoint:CGPointMake(x, y) withType:STTouchDown];
		// [SimulateTouch simulateTouch:pathIndex2 atPoint:CGPointMake(x, y) withType:STTouchUp];
	GCD_END
}

%end
