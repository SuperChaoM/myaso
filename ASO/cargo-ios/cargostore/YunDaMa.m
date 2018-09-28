//
//  YunDaMa.m
//  xCaptcha
//
//  Created by Samwise Pan on 8/1/17.
//  Copyright © 2017 Samwise Pan. All rights reserved.
//

#import "YunDaMa.h"

static NSString *YUNDAMA_API_URL = @"http://api.yundama.com/api.php";

@implementation YunDaMaConfig

@end

@interface YunDaMa()

@property (strong, nonatomic) YunDaMaConfig *config;

@end

@implementation YunDaMa

- (instancetype)initWithConfig:(YunDaMaConfig *)config {
    self = [super init];
    if (self){
        self.config = config;
    }
    return self;
}

- (void)upload:(NSData *)capData typeCode:(NSString *)type cidRet:(long *)cid {
    NSString *boundary = [self generateBoundaryString];
    NSDictionary *parameters = @{
                             @"username": self.config.account,
                             @"password": self.config.password,
                             @"codetype": type,
                             @"appid": self.config.softId,
                             @"appkey": self.config.softKey,
                             @"timeout": self.config.timeout,
                             @"method": @"upload"
                             };
    NSMutableData *httpBody = [NSMutableData data];
    NSURLSessionConfiguration *sessionConfig = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfig];
    NSURL *theURL = [NSURL URLWithString:YUNDAMA_API_URL];
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
    [httpBody appendData:[@"Content-Disposition: form-data; name=\"file\"; filename=\"captcha_challenge.gif\"\r\n" dataUsingEncoding:NSUTF8StringEncoding]];
    [httpBody appendData:[[NSString stringWithFormat:@"Content-Type: image/gif\r\n\r\n"] dataUsingEncoding:NSUTF8StringEncoding]];
    [httpBody appendData:capData];
    [httpBody appendData:[@"\r\n" dataUsingEncoding:NSUTF8StringEncoding]];

    [httpBody appendData:[[NSString stringWithFormat:@"--%@--\r\n", boundary] dataUsingEncoding:NSUTF8StringEncoding]];
    
    dispatch_semaphore_t semaphore = dispatch_semaphore_create(0);
    
    NSURLSessionUploadTask *uploadTask = [session uploadTaskWithRequest:req fromData:httpBody completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        //NSLog(@"data: %@ response: %@ error: %@",data, response, error);
        if (error == nil){
            NSDictionary *retDict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
            if (retDict != nil && [retDict[@"ret"] intValue] == 0 && retDict[@"cid"]){
                NSLog(@"upload, retDict: %@",retDict);
                *cid = [retDict[@"cid"] longValue];
                NSLog(@"===yt.asobitch=== upload in block ... cid %ld", *cid);
            }
        }
        dispatch_semaphore_signal(semaphore);
    }];
    [uploadTask resume];
    dispatch_semaphore_wait(semaphore, DISPATCH_TIME_FOREVER);
    NSLog(@"===yt.asobitch=== upload will return cid %ld", *cid);
}

- (void)tryGetResult:(long)upId capRet:(const char * *)text {
    *text = NULL;
    NSString *capRetUrl = [NSString stringWithFormat:@"%@?method=result&cid=%ld",YUNDAMA_API_URL, upId];
    NSLog(@"===yt.asobitch=== cap ret url %@", capRetUrl);
    NSURL *theURL = [NSURL URLWithString:capRetUrl];
    NSURLSessionConfiguration *sessionConfig = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfig];
    dispatch_semaphore_t semaphore = dispatch_semaphore_create(0);
    NSURLSessionDataTask *dataTask = [session dataTaskWithURL:theURL completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        //NSLog(@"data: %@ response: %@ error: %@",data, response, error);
        if (error == nil){
            NSDictionary *retDict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
            if (retDict != nil){
                NSLog(@"try get result, retDict: %@",retDict);
                int retCode = [retDict[@"ret"] intValue];
                if (retCode == -3002){
                    NSLog(@"===yt.asobitch=== 请等待，打码中");
                    *text = NULL;
                }
                if (retCode == 0){
                    NSLog(@"===yt.asobitch=== 打码 ok");
                    *text = [retDict[@"text"] cStringUsingEncoding:NSUTF8StringEncoding];
                }
            }
        }
        dispatch_semaphore_signal(semaphore);
    }];
    [dataTask resume];
    dispatch_semaphore_wait(semaphore, DISPATCH_TIME_FOREVER);
}

- (long long)queryBalance {
    // TODO
    return 0;
}

- (BOOL)reportWrongAnswer:(NSString *)upId {
    if (upId == nil){
        return NO;
    }
    NSMutableDictionary *parameters = [NSMutableDictionary dictionary];
    parameters[@"username"] = self.config.account;
    parameters[@"password"] = self.config.password;
    parameters[@"appid"] = self.config.softId;
    parameters[@"appkey"] = self.config.softKey;
    parameters[@"method"] = @"report";
    parameters[@"flag"] = @"0";
    parameters[@"cid"] = upId; 

    
    NSURLSessionConfiguration *sessionConfig = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfig];
    dispatch_semaphore_t semaphore = dispatch_semaphore_create(0);
    NSMutableData *httpBody = [NSMutableData data];
    NSURL *theURL = [NSURL URLWithString:YUNDAMA_API_URL];
    NSMutableURLRequest *req = [[NSMutableURLRequest alloc] initWithURL:theURL];
    [req setHTTPMethod:@"POST"];
    NSString *boundary = [self generateBoundaryString];
    NSString *contentType = [NSString stringWithFormat:@"multipart/form-data; boundary=%@", boundary];
    [req setValue:contentType forHTTPHeaderField: @"Content-Type"];
    [parameters enumerateKeysAndObjectsUsingBlock:^(NSString *parameterKey, NSString *parameterValue, BOOL *stop) {
        [httpBody appendData:[[NSString stringWithFormat:@"--%@\r\n", boundary] dataUsingEncoding:NSUTF8StringEncoding]];
        [httpBody appendData:[[NSString stringWithFormat:@"Content-Disposition: form-data; name=\"%@\"\r\n\r\n", parameterKey] dataUsingEncoding:NSUTF8StringEncoding]];
        [httpBody appendData:[[NSString stringWithFormat:@"%@\r\n", parameterValue] dataUsingEncoding:NSUTF8StringEncoding]];
    }];

    NSURLSessionDataTask *dataTask = [session uploadTaskWithRequest:req fromData:httpBody completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        //NSLog(@"data: %@ response: %@ error: %@",data, response, error);
        if (error == nil){
            NSLog(@"汇报ok， 打码错误");
        }
        dispatch_semaphore_signal(semaphore);
    }];
    [dataTask resume];
    dispatch_semaphore_wait(semaphore, DISPATCH_TIME_FOREVER);
    return NO;
}

#pragma mark - Private Methods

- (NSString *)generateBoundaryString {
    return [NSString stringWithFormat:@"asobitch-%@", [[NSUUID UUID] UUIDString]];
}

@end
