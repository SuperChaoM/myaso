#include <CoreFoundation/CoreFoundation.h>
#include <CydiaSubstrate.h>
#include <NSData+FastHex.h>
#include <debug.h>

static CFPropertyListRef (*orig_MGCopyAnswer_internal)(CFStringRef prop, uint32_t* outTypeCode);
CFPropertyListRef new_MGCopyAnswer_internal(CFStringRef prop, uint32_t* outTypeCode) {
	CFPropertyListRef orig_ret = orig_MGCopyAnswer_internal(prop, outTypeCode);
// #if MAGICIAN_DEBUG
// 		NSString *fn = @"MGCopyAnswer_internal";
// 		NSString *k = (NSString *)prop;
// 		CFTypeID tid = CFGetTypeID(orig_ret);
// 		if (tid == CFDataGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFData", fn, k);
// 		}else if (tid == CFStringGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFString ... %@", fn, k, (NSString *)orig_ret);
// 		}else if (tid == CFArrayGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFArray", fn, k);
// 		}else if (tid == CFDictionaryGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFDictionary", fn, k);
// 		}else if (tid == CFDateGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFDate", fn, k);
// 		}else if (tid == CFBooleanGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFBoolean", fn, k);
// 		}else if (tid == CFNumberGetTypeID()){
// 			NSLog(@"===ytboss.magician=== %@ ... %@  ...  CFNumber", fn, k);
// 		}
// #endif

	if (CFEqual(prop, CFSTR("HardwarePlatform"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"HardwarePlatform"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### HardwarePlatform ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}

	}else if (CFEqual(prop, CFSTR("HWModelStr"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"HWModelStr"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### HWModelStr ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}

	}else if (CFEqual(prop, CFSTR("BuildVersion"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"BuildVersion"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### BuildVersion ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}

	}else if (CFEqual(prop, CFSTR("ProductType"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"ProductType"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### ProductType ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}

	}else if (CFEqual(prop, CFSTR("InternationalMobileEquipmentIdentity"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"InternationalMobileEquipmentIdentity"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### InternationalMobileEquipmentIdentity ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}
	}else if (CFEqual(prop, CFSTR("UniqueDeviceID"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"UniqueDeviceID"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### UniqueDeviceID ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}
	}else if (CFEqual(prop, CFSTR("ProductVersion"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"ProductVersion"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### ProductVersion ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}
	}else if (CFEqual(prop, CFSTR("MLBSerialNumber"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"MLBSerialNumber"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### MLBSerialNumber ### ... %@ (via mg)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}
	}else if (CFEqual(prop, CFSTR("UniqueDeviceIDData"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"UniqueDeviceID"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### UniqueDeviceID data ### ... %@ (via mg)", v);
			return CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);
		}
	}

    return orig_ret;
}



%ctor {
	uint8_t MGCopyAnswer_arm64_impl[8] = {0x01, 0x00, 0x80, 0xd2, 0x01, 0x00, 0x00, 0x14};
	const uint8_t* MGCopyAnswer_ptr = (const uint8_t*)MSFindSymbol(NULL, "_MGCopyAnswer");
	if (MGCopyAnswer_ptr != NULL){
		// NSLog(@"===ytboss.magician=== found MGCopyAnswer ... now try hooking");
		if (memcmp(MGCopyAnswer_ptr, MGCopyAnswer_arm64_impl, 8) == 0) {
			// NSLog(@"===ytboss.magician=== hooking ---> MGCopyAnswer_internal");
			MSHookFunction((void*)(MGCopyAnswer_ptr + 8), (void*)new_MGCopyAnswer_internal, (void**)&orig_MGCopyAnswer_internal);
		} else {
			NSLog(@"===ytboss.magician=== hooking ---> MGCopyAnswer 32bit device? It is not support yet.");
			//MSHookFunction((void*)MGCopyAnswer_ptr, (void*)new_MGCopyAnswer, (void**)&orig_MGCopyAnswer);
		}
	}
}