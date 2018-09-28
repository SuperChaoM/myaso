#import <debug.h>

%hook ASIdentifierManager

- (id)advertisingIdentifier {
	%log;
	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
	NSString *v = [mockDeviceInfo objectForKey:@"IDFA"];
	if (v != nil){
		NSLog(@"===ytboss.magician=== mock ### IDFA ### ... %@ (via ASIdentifierManager)", v);
		return [[NSUUID alloc] initWithUUIDString:v];
	}
	return %orig;
}

%end