#import <rocketbootstrap/rocketbootstrap.h>
#import "DaemonTaskManager.h"

int main(int argc, char **argv, char **envp) {
    NSLog(@"my first tool test!");
    
    DaemonTaskManager *taskMan = [[DaemonTaskManager alloc] init];
    [taskMan daemon_startTask];

    return 0;
}

