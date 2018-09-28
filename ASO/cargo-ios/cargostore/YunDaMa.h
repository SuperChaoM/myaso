
@interface YunDaMaConfig : NSObject

@property (strong, nonatomic) NSString *account;
@property (strong, nonatomic) NSString *password;
@property (strong, nonatomic) NSString *softId;
@property (strong, nonatomic) NSString *softKey;
@property (strong, nonatomic) NSString *timeout;

@end

@interface YunDaMa : NSObject

- (instancetype)initWithConfig:(YunDaMaConfig *)config;

- (void)upload:(NSData *)capData typeCode:(NSString *)type cidRet:(long *)cid;

- (void)tryGetResult:(long)upId capRet:(void *)text;

@end
