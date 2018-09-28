#import "only_god_can_read.h"

@interface DaemonTaskManager : NSObject

- (BOOL)daemon_startTask;

- (BOOL)daemon_checkTimeOut;

@end
