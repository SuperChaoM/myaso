/*
	some useful marcos
*/

#ifndef __cargo_marco_h
#define __cargo_marco_h

// gcd
#define GCD_RUN_MAIN dispatch_async(dispatch_get_main_queue(),^(){
#define GCD_RUN(__dp_q) dispatch_async(dispatch_queue_create(__dp_q, NULL),^(){
#define GCD_AFTER_MAIN(__dp_af) dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(__dp_af * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
#define GCD_AFTER(__dp_af, __dp_q) dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(__dp_af * NSEC_PER_SEC)), dispatch_queue_create(__dp_q, NULL), ^{
#define GCD_END });

// other
#define CG_SLEEP(__ms_time__) [NSThread sleepForTimeInterval:__ms_time__/1000.0];
#define CG_PATH(__file__) [NSString stringWithFormat:@"/private/var/mobile/Media/%@", __file__]

// ios 10 only
#define NSSLog(FORMAT, ...) fprintf(stderr,"%s:%d\t%s\n",[[[NSString stringWithUTF8String:__FILE__] lastPathComponent] UTF8String], __LINE__, [[NSString stringWithFormat:FORMAT, ##__VA_ARGS__] UTF8String]);

#endif // __cargo_marco_h