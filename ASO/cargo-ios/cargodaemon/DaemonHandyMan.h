#import "only_god_can_read.h"

@interface DaemonHandyMan : NSObject

@property (assign, nonatomic) int rebootCounter;

- (void)daemon_reboot;

- (void)daemon_clean;

- (void)daemon_kill;

- (void)daemon_uninstallApp:(NSString *)bid;

@end
