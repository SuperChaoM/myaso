#import "iTunesTaskManager.h"

%hook Daemon

- (void)start {
	// %log;
	%orig;

	[[iTunesTaskManager sharedManager] itunes_start];
}

%end
