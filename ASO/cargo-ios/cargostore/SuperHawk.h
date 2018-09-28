
@interface SuperHawkConfig : NSObject

@property (strong, nonatomic) NSString *account;
@property (strong, nonatomic) NSString *password;
@property (strong, nonatomic) NSString *softId;
@property (strong, nonatomic) NSString *softKey;
@property (strong, nonatomic) NSString *codeType;

@end

@interface SuperHawk : NSObject

- (instancetype)initWithConfig:(SuperHawkConfig *)config;

- (void)doCaptcha:(NSData *)capData capRet:(const char * *)text;

@end
