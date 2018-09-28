//
//  FloatingWindowViewController.m

#import <Foundation/Foundation.h>
#import "FloatingWindowViewController.h"
#import "StoreTaskManager.h"
#import "constants.h"

@interface FloatingWindowViewController()<StoreTaskDelegate>

@property (strong, nonatomic) UILabel *copyrightLabel;
@property (strong, nonatomic) UILabel *versionLabel;
@property (strong, nonatomic) UILabel *statusLabel;
@property (strong, nonatomic) UIButton *tools;

@end


@implementation FloatingWindowViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    [self.view addSubview:self.copyrightLabel];
    [self.view addSubview:self.versionLabel];
    [self.view addSubview:self.statusLabel];

    [[StoreTaskManager sharedManager] setDelegate:self];

    NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
    if (nil == task){
        NSLog(@"===yt.cargo-ios=== no task");
        [self updateStatusLabel:@"无任务等待中..." taskStatus:YES];
    }else{
        NSString *loginLabel = [NSString stringWithFormat:@"[%@]登陆中...",[task objectForKey:@"email"]];
        [self updateStatusLabel:loginLabel taskStatus:YES];
    }
    [self updateCopyRightLabel:COPY_RIGHT];
    [self updateVersionLabel:BUILD_VERSION];
}



- (void)updateStatusLabel:(NSString *)content taskStatus:(BOOL) ts {
    NSString *oldContent = (self.statusLabel.text== NULL)?@"":[NSString stringWithFormat:@"%@\n",self.statusLabel.text];
    NSString *contentToAppend = [NSString stringWithFormat:@"%@>>%@",self.currentTime,content];
    self.statusLabel.text = [oldContent stringByAppendingString:contentToAppend];
    self.statusLabel.backgroundColor = [self floatWindowUIColor];
}


- (void)updateCopyRightLabel:(NSString *)copyRightText {
    self.copyrightLabel.text = copyRightText;
    self.copyrightLabel.backgroundColor = [self floatWindowUIColor];
}

- (void)updateVersionLabel:(NSString *)versionText {
    self.versionLabel.text = versionText;
    self.versionLabel.backgroundColor = [self floatWindowUIColor];
}


- (UIColor *) floatWindowUIColor{
    return [UIColor blackColor];
}

- (NSString *) currentTime{
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    [formatter setDateFormat:@"HH:mm:ss"];
    NSDate *datenow = [NSDate date];
    NSString *currentTimeString = [formatter stringFromDate:datenow];
    return currentTimeString;

}

#pragma mark - StoreTaskDelegate
// result dict has two key: 1) ok -- bool, 2) msg --- string
- (void)delegate_itunesDidAuthenticate:(NSDictionary *)retDict {
    // TODO: login result
    [self updateStatusLabel:[retDict objectForKey:@"msg"] taskStatus:[retDict[@"ok"] boolValue]];
}

// result dict has two key: 1) ok -- bool, 2) msg --- string
- (void)delegate_itunesDidPurchase:(NSDictionary *)retDict {
    // TODO: buy result
    [self updateStatusLabel:[retDict objectForKey:@"msg"] taskStatus:[retDict[@"ok"] boolValue]];
}

#pragma mark - Getters & Setters


- (UILabel *)copyrightLabel {
  CGFloat wth = [UIScreen mainScreen].bounds.size.width;
    if (_copyrightLabel == nil){
        _copyrightLabel = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, wth, 20)];
        _copyrightLabel.font = [UIFont systemFontOfSize:14];
        _copyrightLabel.numberOfLines = 0;
        _copyrightLabel.textAlignment = NSTextAlignmentLeft;
        _copyrightLabel.textColor = [UIColor whiteColor];
    }
    return _copyrightLabel;
}

- (UILabel *)versionLabel {
  CGFloat wth = [UIScreen mainScreen].bounds.size.width;
    if (_versionLabel == nil){
        _versionLabel = [[UILabel alloc] initWithFrame:CGRectMake(0, 20, wth, 20)];
        _versionLabel.font = [UIFont systemFontOfSize:14];
        _versionLabel.numberOfLines = 0;
        _versionLabel.textAlignment = NSTextAlignmentLeft;
        _versionLabel.textColor = [UIColor whiteColor];
    }
    return _versionLabel;
}

- (UILabel *)statusLabel {
    CGFloat wth = [UIScreen mainScreen].bounds.size.width;
    if (_statusLabel == nil){
        _statusLabel = [[UILabel alloc] initWithFrame:CGRectMake(0, 40, wth, 150)];
        _statusLabel.font = [UIFont systemFontOfSize:14];
        _statusLabel.numberOfLines = 0;
        _statusLabel.textAlignment = NSTextAlignmentLeft;
        _statusLabel.textColor = [UIColor whiteColor];
    }
    return _statusLabel;
}


@end
