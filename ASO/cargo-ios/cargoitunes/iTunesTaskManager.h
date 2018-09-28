#import "only_god_can_read.h"

@interface iTunesTaskManager : NSObject

+ (instancetype)sharedManager;

- (void)itunes_start;

- (void)itunes_handleAuthenticateResult:(NSDictionary *)retDict;

- (void)itunes_handlePurchaseResult:(NSDictionary *)retDict;

@end
