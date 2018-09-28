#import "only_god_can_read.h"

@protocol StoreTaskDelegate

// result dict has two key: 1) ok -- bool, 2) msg --- string
- (void)delegate_itunesDidAuthenticate:(NSDictionary *)retDict;

// result dict has two key: 1) ok -- bool, 2) msg --- string
- (void)delegate_itunesDidPurchase:(NSDictionary *)retDict;

@end

@interface StoreTaskManager : NSObject

+ (instancetype)sharedManager;

@property (retain, nonatomic) id<StoreTaskDelegate> delegate;

@property (retain, nonatomic) id tabBarController;

// authenticate
@property (assign, nonatomic) BOOL authenticateDone;

// search
@property (retain, nonatomic) id searchBarController;
@property (assign, nonatomic) BOOL myAppFoundInSearchResult;

// product
@property (retain, nonatomic) id offerView;
@property (retain, nonatomic) id offerButton;
@property (assign, nonatomic) BOOL purchaseDone;

// captcha
@property (assign, nonatomic) BOOL canRandomTyping;
- (void)startCaptchaVerify;
- (BOOL)shouldCaptureCaptchaImage;
- (void)captureCaptchaImageDone;

// public
- (NSDictionary *)appStore_currentTask;

- (void)appStore_startAuthenticate;

- (void)appStore_suspendApplication;

- (void)appStore_markTaskFail:(int)retCode;

- (void)appStore_markTaskSucc;

- (void)appStore_simulateClickAtX:(int)x y:(int)y;

@end
