#import "DaemonTaskManager.h"
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>

#define MSG_CENTER				@"com.nkaso.cargo.msgcenter"
#define MSG_LAUNCH_APPSTORE		@"com.nkaso.cargo.launchappstore"


@interface DaemonTaskManager() {
	
	CPDistributedMessagingCenter *_mc;
}

@end

@implementation DaemonTaskManager

- (BOOL)daemon_startTask {
    // messages
    _mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
    rocketbootstrap_distributedmessagingcenter_apply(_mc);
    NSLog(@"send msg:MSG_LAUNCH_APPSTORE !");
    [_mc sendMessageName:MSG_LAUNCH_APPSTORE userInfo:nil];
    return YES;
}

- (instancetype)init {
    self = [super init];
    if (self){
        [self _init];
    }
    return self;
}

- (void)_init {
    NSLog(@"DaemonTaskManager init!");


	
}
@end
