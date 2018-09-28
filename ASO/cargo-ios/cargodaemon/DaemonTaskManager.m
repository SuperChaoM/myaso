#import "DaemonTaskManager.h"
#import "constants.h"
#import "msg.h"
#import "CargoTask.h"
#import "version.h"
#import "macro.h"
#import "task_ret_codes.h"
#import "DaemonHandyMan.h"
#import <notify.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>
#include <CoreFoundation/CoreFoundation.h>

@interface DaemonTaskManager() {
	BOOL _initDone;
	BOOL _downloadingTask;

	// current running
	CargoTask *_currentTask;
	long _startedAt;

	NSMutableArray<CargoTask *> *_localTaskQueue;
	DaemonHandyMan *_hdMan;
	CPDistributedMessagingCenter *_mc;
}

@end

@implementation DaemonTaskManager

- (BOOL)daemon_startTask {
	_currentTask = nil;
	_currentTask = [self _popTask];
	if (_currentTask == nil){
		NSLog(@"###cargo no task");
		return NO;
	}
	NSLog(@"###cargo daemon_startTask %@", _currentTask);
	[_hdMan daemon_clean];
	[_hdMan daemon_uninstallApp:_currentTask.appBundleId];
	[_currentTask task_writeToDisk];
	[_hdMan daemon_kill];
	_hdMan.rebootCounter ++;
	// [_mc sendMessageName:MSG_LAUNCH_APPSTORE userInfo:nil];
	[self _openAppStore];
	_startedAt = [self _timestamp];
	return YES;
}

- (BOOL)daemon_checkTimeOut{
	if (_currentTask == nil){
		NSLog(@"###cargo no task is running");
		[self daemon_startTask];
		return NO;
	}

	long passed = [self _timestamp] - _startedAt;
	NSLog(@"###cargo check time passed %ld seconds", passed);
	if (passed >= TIMEOUT_TASK){
		// shoud run next if timeout
		[self _reportTaskTimeout];
		[self _nextTask];
		_currentTask = nil;
		return YES;
	}
	return NO;
}

#ifndef SPRINGBOARDSERVICES_H_
	extern int SBSLaunchApplicationWithIdentifier(CFStringRef identifier, Boolean suspended);
	extern CFStringRef SBSApplicationLaunchingErrorString(int error);
#endif

- (void)_openAppStore {
	CFStringRef identifier = CFStringCreateWithCString(kCFAllocatorDefault, "com.apple.AppStore", kCFStringEncodingUTF8);
    int ret = SBSLaunchApplicationWithIdentifier(identifier, FALSE);
    if (ret != 0) {
        fprintf(stderr, "Couldn't open AppStore, Reason: %i, ", ret);
    }
    CFRelease(identifier);
}


- (instancetype)init {
	self = [super init];
	if (self){
		[self _init];
	}
	return self;
}

- (void)_init {
	_localTaskQueue = [NSMutableArray array];
	_hdMan = [[DaemonHandyMan alloc] init];
	[_hdMan daemon_reboot];

	// messages
	_mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	rocketbootstrap_distributedmessagingcenter_apply(_mc);

	// notify
	// ------- MSG_FINISH_TASK -----------
	const char *done_msg = [MSG_FINISH_TASK cStringUsingEncoding:NSUTF8StringEncoding];
	int done_token;
	notify_register_dispatch(done_msg, &done_token, dispatch_get_main_queue(), ^(int token){
	    NSLog(@"###cargo got notify MSG_FINISH_TASK");
	    int ret;
	    notify_get_state(token, &ret);
	    NSLog(@"###cargo task ret is %d", ret);
	    [self _reportTask:ret];
	    if (ret == RET_CODE_OK){
	    	// wait for sending xp events done
	    	_currentTask = nil;
	    	// we are waiting MSG_NEXT_TASK msg
	    }else{
	    	// we do not care xp events for failed task
	    	[self _nextTask];
	    }
	});

	// ------- MSG_FINISH_TASK -----------
	const char *next_msg = [MSG_NEXT_TASK cStringUsingEncoding:NSUTF8StringEncoding];
	int next_token;
	notify_register_dispatch(next_msg, &next_token, dispatch_get_main_queue(), ^(int token){
	    NSLog(@"###cargo got notify MSG_NEXT_TASK");
	    [self _nextTask];
	});
}

- (CargoTask *)_popTask {
	NSLog(@"###cargo queue has %ld tasks", (long)[_localTaskQueue count]);

	if ([_localTaskQueue count] > 0){
		CargoTask *next = [_localTaskQueue lastObject];
		[_localTaskQueue removeLastObject];
		return next;
	}

	[self _downloadTasks];
	return nil;
}

- (void)_downloadTasks {
	NSLog(@"###cargo start download tasks ...");
	dispatch_semaphore_t sem = dispatch_semaphore_create(0);
	NSURL *taskURL = [NSURL URLWithString:[NSString stringWithFormat:@"http://%@/index.php?m=Api&c=apple&a=get_mixed_task&pw=Txwh2008", TASK_SERVER_HOST]];
    NSMutableURLRequest *taskReq = [NSMutableURLRequest requestWithURL:taskURL];
    // NSString *userAgent = [NSString stringWithFormat:@"%@/%@", CARGO_APPNAME, CARGO_VERSION];
    NSString *userAgent = @"cargo/1.0";
    [taskReq setValue:userAgent forHTTPHeaderField:@"User-Agent"];
    NSURLSessionDataTask *task = [[NSURLSession sharedSession] dataTaskWithRequest:taskReq completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        if (error){
            NSLog(@"###cargo ERROR request %@ with code %ld", taskURL.absoluteString, (long)[(NSHTTPURLResponse *)response statusCode]);
            dispatch_semaphore_signal(sem);
            return;
        }
        NSDictionary *dict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
        if (dict == nil){
            NSLog(@"###cargo can not parse task json");
            dispatch_semaphore_signal(sem);
            return;
        }
        NSInteger code = [dict[@"ret"] integerValue];
        if (code == 0 ){
        	NSArray *tasks = dict[@"data"];
        	if ([tasks isKindOfClass:[NSArray class]]){
        		for (NSDictionary *taskDict in tasks){
        			CargoTask *task = [[CargoTask alloc] init];
        			[task setUnderlyingTaskDict:taskDict];
        			[_localTaskQueue addObject:task];
        		}
        	}
        }else{
           NSLog(@"###cargo ret: %@", dict[@"ret"]);
           NSLog(@"###cargo msg: %@", dict[@"msg"]);
        }
        dispatch_semaphore_signal(sem);
    }];
    [task resume];
    dispatch_semaphore_wait(sem, DISPATCH_TIME_FOREVER);
    NSLog(@"###cargo download tasks done.");
}

- (void)_reportTask:(int)ret {
	NSString *taskId = _currentTask.underlyingTaskDict[@"work_id"];
	NSString *accountId = _currentTask.underlyingTaskDict[@"account_id"];
    NSString *url = nil;
    if (ret == RET_CODE_OK){
        url = [NSString stringWithFormat:@"http://%@/index.php?m=Api&c=apple&a=update_download_task&pw=Txwh2008&work_id=%@&succ_num=1&fail_num=0&type=3&account_id=%@&ret=%d", TASK_SERVER_HOST, taskId, accountId, ret];
    }else{
        url = [NSString stringWithFormat:@"http://%@/index.php?m=Api&c=apple&a=update_download_task&pw=Txwh2008&work_id=%@&succ_num=0&fail_num=1&type=3&account_id=%@&ret=%d", TASK_SERVER_HOST, taskId, accountId, ret];
    }
    NSURL *reportURL = [NSURL URLWithString:url];
    NSMutableURLRequest *reportReq = [NSMutableURLRequest requestWithURL:reportURL];
    // NSString *userAgent = [NSString stringWithFormat:@"%@/%@", CARGO_APPNAME, CARGO_VERSION];
    NSString *userAgent = @"cargo/1.0";
    [reportReq setValue:userAgent forHTTPHeaderField:@"User-Agent"];
    NSURLSessionDataTask *task = [[NSURLSession sharedSession] dataTaskWithRequest:reportReq completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        if (error){
            NSLog(@"ERROR request %@ with code %ld", reportURL.absoluteString, (long)[(NSHTTPURLResponse *)response statusCode]);
            return;
        }
        NSString *body = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        NSLog(@"reponse: \n%@",body);
    }];
    [task resume];
}

- (void)_reportTaskTimeout {
	// TODO:
	NSLog(@"###cargo report task timeout");
	[self _reportTask:TASK_ERR_TIMEOUT];
}

- (void)_nextTask {
	NSLog(@"###cargo run next task");
	[_hdMan daemon_reboot];
	[self daemon_startTask];
}

- (long)_timestamp {
	return (long)[[NSDate date] timeIntervalSince1970];
}

@end
