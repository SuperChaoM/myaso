

#import <notify.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>


#define MSG_CENTER				@"com.nkaso.cargo.msgcenter"
#define MSG_LAUNCH_APPSTORE		@"com.nkaso.cargo.launchappstore"

%hook SpringBoard
- (void)_menuButtonDown:(id)down
{
    NSLog(@"You've pressed home button.");
    %orig; // call the original _menuButtonDown:
}


- (void)applicationDidFinishLaunching:(id)arg1 {
	// %log;
	%orig;

	// messages
	CPDistributedMessagingCenter *mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	rocketbootstrap_distributedmessagingcenter_apply(mc);
	[mc runServerOnCurrentThread];
	[mc registerForMessageName:MSG_LAUNCH_APPSTORE target:self selector:@selector(nkcg_launchAppStore)];
	//[mc registerForMessageName:MSG_START_AUTHENTICATE target:self selector:@selector(nkcg_startAuthenticate)];
	//[mc registerForMessageName:MSG_ITUNES_STARTED target:self selector:@selector(nkcg_itunesDidStart)];
	//[mc registerForMessageName:MSG_SIMULATE_CLICK target:self selector:@selector(nkcg_simulateClick:userInfo:)];

	//[self performSelector:@selector(nkcg_unlockDevice)];
}
%new
- (void)nkcg_launchAppStore {
	NSLog(@"got MSG_LAUNCH_APPSTORE");
	if ([self respondsToSelector:@selector(launchApplicationWithIdentifier:suspended:)]){
		[self launchApplicationWithIdentifier:@"com.apple.AppStore" suspended:NO];
	}
}

%end
