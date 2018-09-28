#import "macro.h"
#import "DaemonTaskManager.h"
#import "only_god_can_read.h"

#define TIMEOUT_CHECK_INTERVAL 10

int main(int argc, char **argv, char **envp) {
	NSLog(@"###cargo main start");

	DaemonTaskManager *taskMan = [[DaemonTaskManager alloc] init];
	[taskMan daemon_startTask];

	NSTimer *timer = [NSTimer timerWithTimeInterval:TIMEOUT_CHECK_INTERVAL target:taskMan selector:@selector(daemon_checkTimeOut) userInfo:nil repeats:YES];
	[[NSRunLoop currentRunLoop] addTimer:timer forMode:NSDefaultRunLoopMode];
	while (true){
		[[NSRunLoop currentRunLoop] runMode:NSDefaultRunLoopMode beforeDate:[NSDate distantFuture]];
	}
	[timer invalidate];
	[timer release];
	[taskMan release];
	NSLog(@"###cargo main exit");
	return 0;
}

// vim:ft=objc
