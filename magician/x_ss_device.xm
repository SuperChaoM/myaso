// #import <debug.h>

// %hook SSDevice

// - (id)productVersion {
// 	%log;
// 	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
// 	NSString *v = [mockDeviceInfo objectForKey:@"ProductVersion"];
// 	if (v != nil){
// 		NSLog(@"===ytboss.magician=== mock ### ProductVersion ### ... %@ (via SSDevice)", v);
// 		return v;
// 	}
// 	return %orig;
// }

// - (id)userAgent {
// 	%log;
// 	NSString *ret = %orig;
// 	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
// 	NSString *v = [mockDeviceInfo objectForKey:@"ProductVersion"];
// 	if (v != nil){
// 		NSLog(@"===ytboss.magician=== mock ### ProductVersion ### ... %@ (via SSDevice ua)", v);
// 		return [ret stringByReplacingOccurrencesOfString:[self yt_realOsVersion] withString:[self productVersion]];
// 	}
// 	return ret;
// }

// - (id)userAgentWithBundleIdentifier:(id)arg1 version:(id)arg2 {
// 	%log;
// 	NSString *ret = %orig;
// 	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
// 	NSString *v = [mockDeviceInfo objectForKey:@"ProductVersion"];
// 	if (v != nil){
// 		NSLog(@"===ytboss.magician=== mock ### ProductVersion ### ... %@ (via SSDevice ua bid)", v);
// 		return [ret stringByReplacingOccurrencesOfString:[self yt_realOsVersion] withString:[self productVersion]];
// 	}
// 	return ret;
// }

// - (id)userAgentWithClientName:(id)arg1 version:(id)arg2 {
// 	%log;
// 	NSString *ret = %orig;
// 	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
// 	NSString *v = [mockDeviceInfo objectForKey:@"ProductVersion"];
// 	if (v != nil){
// 		NSLog(@"===ytboss.magician=== mock ### ProductVersion ### ... %@ (via SSDevice ua client)", v);
// 		return [ret stringByReplacingOccurrencesOfString:[self yt_realOsVersion] withString:[self productVersion]];
// 	}
// 	return ret;
// }

// %new
// - (NSString *)yt_realOsVersion {
// 	NSOperatingSystemVersion osVer = [[NSProcessInfo processInfo] operatingSystemVersion];
// 	return [NSString stringWithFormat:@"%ld.%ld.%ld", (long)(osVer.majorVersion), (long)(osVer.minorVersion), (long)(osVer.patchVersion)];
// }

// %end