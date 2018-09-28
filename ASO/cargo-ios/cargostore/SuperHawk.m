//
//  YunDaMa.m
//  xCaptcha
//
//  Created by Samwise Pan on 8/1/17.
//  Copyright © 2017 Samwise Pan. All rights reserved.
//

#import "SuperHawk.h"

static NSString *SUPERHAWK_API_URL = @"http://upload.chaojiying.net/Upload/Processing.php";

@implementation SuperHawkConfig

@end

@interface SuperHawk()

@property (strong, nonatomic) SuperHawkConfig *config;

@end

@implementation SuperHawk

- (instancetype)initWithConfig:(SuperHawkConfig *)config {
    self = [super init];
    if (self){
        self.config = config;
    }
    return self;
}

- (void)doCaptcha:(NSData *)capData capRet:(const char * *)text{
    NSString *boundary = [self generateBoundaryString];
    NSDictionary *parameters = @{
                             @"user": self.config.account,
                             @"pass": self.config.password,
                             @"codetype": self.config.codeType,
                             @"softid": self.config.softId,
                             @"len_min": @"0",
                             };
    NSMutableData *httpBody = [NSMutableData data];
    NSURLSessionConfiguration *sessionConfig = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfig];
    NSURL *theURL = [NSURL URLWithString:SUPERHAWK_API_URL];
    NSMutableURLRequest *req = [[NSMutableURLRequest alloc] initWithURL:theURL];
    [req setHTTPMethod:@"POST"];
    NSString *contentType = [NSString stringWithFormat:@"multipart/form-data; boundary=%@", boundary];
    [req setValue:contentType forHTTPHeaderField: @"Content-Type"];

    [parameters enumerateKeysAndObjectsUsingBlock:^(NSString *parameterKey, NSString *parameterValue, BOOL *stop) {
        [httpBody appendData:[[NSString stringWithFormat:@"--%@\r\n", boundary] dataUsingEncoding:NSUTF8StringEncoding]];
        [httpBody appendData:[[NSString stringWithFormat:@"Content-Disposition: form-data; name=\"%@\"\r\n\r\n", parameterKey] dataUsingEncoding:NSUTF8StringEncoding]];
        [httpBody appendData:[[NSString stringWithFormat:@"%@\r\n", parameterValue] dataUsingEncoding:NSUTF8StringEncoding]];
    }];

    [httpBody appendData:[[NSString stringWithFormat:@"--%@\r\n", boundary] dataUsingEncoding:NSUTF8StringEncoding]];
    [httpBody appendData:[@"Content-Disposition: form-data; name=\"userfile\"; filename=\"captcha_challenge.gif\"\r\n" dataUsingEncoding:NSUTF8StringEncoding]];
    [httpBody appendData:[[NSString stringWithFormat:@"Content-Type: image/gif\r\n\r\n"] dataUsingEncoding:NSUTF8StringEncoding]];
    [httpBody appendData:capData];
    [httpBody appendData:[@"\r\n" dataUsingEncoding:NSUTF8StringEncoding]];

    [httpBody appendData:[[NSString stringWithFormat:@"--%@--\r\n", boundary] dataUsingEncoding:NSUTF8StringEncoding]];

    dispatch_semaphore_t semaphore = dispatch_semaphore_create(0);

    NSURLSessionUploadTask *uploadTask = [session uploadTaskWithRequest:req fromData:httpBody completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        //NSLog(@"data: %@ response: %@ error: %@",data, response, error);
        if (error == nil){
            NSDictionary *retDict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
            if (retDict != nil && [retDict[@"err_no"] intValue] == 0 && retDict[@"pic_str"]){
                NSLog(@"upload, retDict: %@",retDict);
                *text = [retDict[@"pic_str"] cStringUsingEncoding:NSUTF8StringEncoding];
            }
        }
        dispatch_semaphore_signal(semaphore);
    }];
    [uploadTask resume];
    dispatch_semaphore_wait(semaphore, DISPATCH_TIME_FOREVER);
}

#pragma mark - Private Methods

- (NSString *)generateBoundaryString {
    return [NSString stringWithFormat:@"asobitch-%@", [[NSUUID UUID] UUIDString]];
}

@end
