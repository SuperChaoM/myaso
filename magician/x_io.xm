#include <CydiaSubstrate.h>
#include <IOKit/IOKitLib.h>
#include <NSData+FastHex.h>
#include <debug.h>

static CFTypeRef (*origin_IORegistryEntryCreateCFProperty)(io_registry_entry_t entry, CFStringRef key, CFAllocatorRef allocator, IOOptionBits options);
CFTypeRef new_IORegistryEntryCreateCFProperty(io_registry_entry_t entry, CFStringRef key, CFAllocatorRef allocator, IOOptionBits options) {
	CFTypeRef orig_ret = origin_IORegistryEntryCreateCFProperty(entry, key, allocator, options);
// #if	MAGICIAN_DEBUG
// 		NSString *fn = @"new_IORegistryEntryCreateCFProperty";
// 		NSString *k = (NSString *)key;
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

	if (CFEqual(key, CFSTR("IOPlatformSerialNumber"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"SerialNumber"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### SerialNumber ### ... %@  (via iokit)", v);
			return CFStringCreateCopy(kCFAllocatorDefault, (CFStringRef)v);
		}
	}

	if (CFEqual(key, CFSTR("mac-address-bluetooth0"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"BluetoothAddress"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### BluetoothAddress ### ... %@  (via iokit property)", v);
			return CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);
		}
	}

	if (CFEqual(key, CFSTR("mac-address-wifi0"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"WifiAddress"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### WifiAddress ### ... %@  (via iokit property)", v);
			return CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);
		}
	}

	if (CFEqual(key, CFSTR("unique-chip-id"))){
		NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
		NSString *v = [mockDeviceInfo objectForKey:@"UniqueChipID"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### UniqueChipID ### ... %@ (via iokit property)", v);
			return CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);
		}
	}

	return orig_ret;
}

static kern_return_t (*origin_IORegistryEntryCreateCFProperties)(io_registry_entry_t entry, CFMutableDictionaryRef * properties, CFAllocatorRef	allocator, IOOptionBits	options);
kern_return_t new_IORegistryEntryCreateCFProperties(io_registry_entry_t entry, CFMutableDictionaryRef * properties, CFAllocatorRef		allocator, IOOptionBits		options ) {
	kern_return_t ret = origin_IORegistryEntryCreateCFProperties(entry, properties, allocator, options);
	NSMutableDictionary *propDict = (NSMutableDictionary *)*properties;

// #if MAGICIAN_DEBUG
// 		NSLog(@"===ytboss.magician=== new_IORegistryEntryCreateCFProperties is called, properties %@", propDict);	
// #endif

	NSDictionary *mockDeviceInfo = [NSDictionary dictionaryWithContentsOfFile:X_DEVICE_PATH];
	if (propDict[@"mac-address-bluetooth0"] && mockDeviceInfo[@"BluetoothAddress"]){
		NSString *v = [mockDeviceInfo objectForKey:@"BluetoothAddress"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### BluetoothAddress ### ... %@  (via iokit properties)", v);
			CFDataRef btData = CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);;
			CFDictionarySetValue(*properties, CFSTR("mac-address-bluetooth0"), btData);
		}
	}

	if (propDict[@"mac-address-wifi0"] && mockDeviceInfo[@"WifiAddress"]){
		NSString *v = [mockDeviceInfo objectForKey:@"WifiAddress"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### WifiAddress ### ... %@  (via iokit properties)", v);
			CFDataRef wifiData = CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);;
			CFDictionarySetValue(*properties, CFSTR("mac-address-wifi0"), wifiData);
		}
	}

	if (propDict[@"unique-chip-id"] && mockDeviceInfo[@"UniqueChipID"]){
		NSString *v = [mockDeviceInfo objectForKey:@"UniqueChipID"];
		if (v != nil){
			NSLog(@"===ytboss.magician=== mock ### UniqueChipID ### ... %@ (via iokit properties)", v);
			CFDataRef chipIdData = CFDataCreateCopy(kCFAllocatorDefault, (CFDataRef)[NSData dataWithHexString:v]);;
			CFDictionarySetValue(*properties, CFSTR("unique-chip-id"), chipIdData);
		}
	}
	return ret;
}



%ctor {
	// hook iokit
    origin_IORegistryEntryCreateCFProperty = (CFTypeRef (*)(io_registry_entry_t, CFStringRef, CFAllocatorRef, IOOptionBits))MSFindSymbol(NULL, "_IORegistryEntryCreateCFProperty");
    if (origin_IORegistryEntryCreateCFProperty != NULL){
		// NSLog(@"===ytboss.magician=== hooking ---> IORegistryEntryCreateCFProperty");
    	MSHookFunction((void*)IORegistryEntryCreateCFProperty, (void*)new_IORegistryEntryCreateCFProperty, (void**)&origin_IORegistryEntryCreateCFProperty);
    }
    // hook iokit
    origin_IORegistryEntryCreateCFProperties = (kern_return_t (*)(io_registry_entry_t entry, CFMutableDictionaryRef * properties, CFAllocatorRef	allocator, IOOptionBits	options))MSFindSymbol(NULL, "_IORegistryEntryCreateCFProperties");
    if (origin_IORegistryEntryCreateCFProperties != NULL){
    	// NSLog(@"===ytboss.magician=== hooking ---> IORegistryEntryCreateCFProperties");
    	MSHookFunction((void*)IORegistryEntryCreateCFProperties, (void*)new_IORegistryEntryCreateCFProperties, (void**)&origin_IORegistryEntryCreateCFProperties);
    }
}