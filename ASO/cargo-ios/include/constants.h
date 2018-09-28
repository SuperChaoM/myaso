#ifndef __cargo_constants_h
#define __cargo_constants_h

#define MOVING_MY_APP_TO_INDEX 0
#define MY_APP_INDEX_UNKNOWN -1
#define DO_NOT_MOVE_MY_APP_BEFORE_INDEX 0
#define OVERLAY_VIEW_WIDTH 240
#define OVERLAY_VIEW_HEIGHT 180

static NSString *COPY_RIGHT = @"ASO大神";
static NSString *BUILD_VERSION = @"版本: iOS10 ty/180125.231312";

// task server
static NSString *TASK_SERVER_HOST = @"";



// timeout ( by seconds)
static long TIMEOUT_TASK = 300;


// captcha YDM 云打码 http://www.yundama.com/
// YDM账号
static NSString *CAPTCHA_YUNDAMA_ACCOUNT = @"";
// YDM密码
static NSString *CAPTCHA_YUNDAMA_PASSWORD = @"";
// YDM超时时间
static NSString *CAPTCHA_YUNDAMA_TIMEOUT = @"60";
// YDM验证码类型
// 	4000 - 不定长数字， 3题分/数字
//	4105 - 模糊动态5位数字， 24题分
//  1000 - 不定长英文数字
//  4005 - 5位纯数字
static NSString *CAPTCHA_YUNDAMA_TYPE = @"4005";


static NSString *CAPTCHA_SUPERHAWK_ACCOUNT = @"";

static NSString *CAPTCHA_SUPERHAWK_PASSWORD = @"";

static NSString *CAPTCHA_SUPERHAWK_SOFT_ID = @"";

static NSString *CAPTCHA_SUPERHAWK_SOFT_KEY = @"";

// superHawk验证码类型
// 	4000 - 不定长数字， 3题分/数字
//	4105 - 模糊动态5位数字， 24题分
//  1000 - 不定长英文数字
//  4005 - 1-5位纯数字
static NSString *CAPTCHA_SUPERHAWK_TYPE = @"4005";

#endif // __cargo_constants_h
