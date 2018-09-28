@interface CargoTask : NSObject

@property (strong, nonatomic) NSDictionary *underlyingTaskDict;

- (BOOL)task_writeToDisk;

- (NSDictionary *)task_deviceInfo;

- (NSString *)appBundleId;

@end
