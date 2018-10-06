#line 1 "Tweak.xm"


#import <notify.h>
#import <rocketbootstrap/rocketbootstrap.h>
#import <AppSupport/CPDistributedMessagingCenter.h>


#define MSG_CENTER				@"com.nkaso.cargo.msgcenter"
#define MSG_LAUNCH_APPSTORE		@"com.nkaso.cargo.launchappstore"

#if defined(__clang__)
#if __has_feature(objc_arc)
#define _LOGOS_SELF_TYPE_NORMAL __unsafe_unretained
#define _LOGOS_SELF_TYPE_INIT __attribute__((ns_consumed))
#define _LOGOS_SELF_CONST const
#define _LOGOS_RETURN_RETAINED __attribute__((ns_returns_retained))
#else
#define _LOGOS_SELF_TYPE_NORMAL
#define _LOGOS_SELF_TYPE_INIT
#define _LOGOS_SELF_CONST
#define _LOGOS_RETURN_RETAINED
#endif
#else
#define _LOGOS_SELF_TYPE_NORMAL
#define _LOGOS_SELF_TYPE_INIT
#define _LOGOS_SELF_CONST
#define _LOGOS_RETURN_RETAINED
#endif

#include <substrate.h>
@class SpringBoard; 
static void (*_logos_orig$_ungrouped$SpringBoard$_menuButtonDown$)(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST, SEL, id); static void _logos_method$_ungrouped$SpringBoard$_menuButtonDown$(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST, SEL, id); static void (*_logos_orig$_ungrouped$SpringBoard$applicationDidFinishLaunching$)(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST, SEL, id); static void _logos_method$_ungrouped$SpringBoard$applicationDidFinishLaunching$(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST, SEL, id); static void _logos_method$_ungrouped$SpringBoard$nkcg_launchAppStore(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST, SEL); 

#line 11 "Tweak.xm"


static void _logos_method$_ungrouped$SpringBoard$_menuButtonDown$(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST self, SEL _cmd, id down) {
    NSLog(@"You've pressed home button.");
    _logos_orig$_ungrouped$SpringBoard$_menuButtonDown$(self, _cmd, down); 
}


static void _logos_method$_ungrouped$SpringBoard$applicationDidFinishLaunching$(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST self, SEL _cmd, id arg1) {
	
	_logos_orig$_ungrouped$SpringBoard$applicationDidFinishLaunching$(self, _cmd, arg1);

	
	CPDistributedMessagingCenter *mc = [CPDistributedMessagingCenter centerNamed:MSG_CENTER];
	rocketbootstrap_distributedmessagingcenter_apply(mc);
	[mc runServerOnCurrentThread];
	[mc registerForMessageName:MSG_LAUNCH_APPSTORE target:self selector:@selector(nkcg_launchAppStore)];
	
	
	

	
}

static void _logos_method$_ungrouped$SpringBoard$nkcg_launchAppStore(_LOGOS_SELF_TYPE_NORMAL SpringBoard* _LOGOS_SELF_CONST self, SEL _cmd) {
	NSLog(@"got MSG_LAUNCH_APPSTORE");
	if ([self respondsToSelector:@selector(launchApplicationWithIdentifier:suspended:)]){
		[self launchApplicationWithIdentifier:@"com.apple.AppStore" suspended:NO];
	}
}


static __attribute__((constructor)) void _logosLocalInit() {
{Class _logos_class$_ungrouped$SpringBoard = objc_getClass("SpringBoard"); if (_logos_class$_ungrouped$SpringBoard) {MSHookMessageEx(_logos_class$_ungrouped$SpringBoard, @selector(_menuButtonDown:), (IMP)&_logos_method$_ungrouped$SpringBoard$_menuButtonDown$, (IMP*)&_logos_orig$_ungrouped$SpringBoard$_menuButtonDown$);} else {HBLogError(@"logos: nil class %s", "SpringBoard");}if (_logos_class$_ungrouped$SpringBoard) {MSHookMessageEx(_logos_class$_ungrouped$SpringBoard, @selector(applicationDidFinishLaunching:), (IMP)&_logos_method$_ungrouped$SpringBoard$applicationDidFinishLaunching$, (IMP*)&_logos_orig$_ungrouped$SpringBoard$applicationDidFinishLaunching$);} else {HBLogError(@"logos: nil class %s", "SpringBoard");}{ char _typeEncoding[1024]; unsigned int i = 0; _typeEncoding[i] = 'v'; i += 1; _typeEncoding[i] = '@'; i += 1; _typeEncoding[i] = ':'; i += 1; _typeEncoding[i] = '\0'; class_addMethod(_logos_class$_ungrouped$SpringBoard, @selector(nkcg_launchAppStore), (IMP)&_logos_method$_ungrouped$SpringBoard$nkcg_launchAppStore, _typeEncoding); }} }
#line 43 "Tweak.xm"
