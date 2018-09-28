#import "CargoTask.h"
#import "macro.h"

@implementation CargoTask

- (BOOL)task_writeToDisk {
	if (self.underlyingTaskDict == nil){
		return NO;
	}

  BOOL taskWritten = [self.daemon_taskInfo writeToFile:CG_PATH(@"Task.plist") atomically:YES];
  BOOL deviceInfoWritten = [self.task_deviceInfo writeToFile:@"/private/var/mobile/Media/ytboss_device_info.plist" atomically:YES];
  return (taskWritten && deviceInfoWritten);
}

- (NSString *)appBundleId {
    return self.underlyingTaskDict[@"app_name"];
}

- (NSMutableDictionary *)daemon_taskInfo {
    NSMutableDictionary *t = [NSMutableDictionary dictionary];
    if (self.underlyingTaskDict == nil){
          return t;
    }

    if (self.underlyingTaskDict[@"app_id"]){
        t[@"app_id"] = self.underlyingTaskDict[@"app_id"];
    }
    if (self.underlyingTaskDict[@"keyword"]){
        t[@"keyword"] = self.underlyingTaskDict[@"keyword"];
    }
    if (self.underlyingTaskDict[@"email"]){
        t[@"email"] = self.underlyingTaskDict[@"email"];
    }
    if (self.underlyingTaskDict[@"password"]){
        t[@"password"] = self.underlyingTaskDict[@"password"];
    }

    return t;
}

- (NSMutableDictionary *)task_deviceInfo{
  	NSMutableDictionary *deviceInfo = [NSMutableDictionary dictionary];
  	if (self.underlyingTaskDict == nil){
  		  return deviceInfo;
  	}
    if (self.underlyingTaskDict[@"hardware_platform"]){
        deviceInfo[@"HardwarePlatform"] = self.underlyingTaskDict[@"hardware_platform"];
    }
    if (self.underlyingTaskDict[@"hardware_model"]){
        deviceInfo[@"HWModelStr"] = self.underlyingTaskDict[@"hardware_model"];
    }
    if (self.underlyingTaskDict[@"build_version"]){
        deviceInfo[@"BuildVersion"] = self.underlyingTaskDict[@"build_version"];
    }
    if (self.underlyingTaskDict[@"product_type"]){
        deviceInfo[@"ProductType"] = self.underlyingTaskDict[@"product_type"];
    }
    if (self.underlyingTaskDict[@"ecid"]){
        deviceInfo[@"UniqueChipID"] = self.underlyingTaskDict[@"ecid"];
    }
    if (self.underlyingTaskDict[@"imei"]){
        deviceInfo[@"InternationalMobileEquipmentIdentity"] = self.underlyingTaskDict[@"imei"];
  	}
    if (self.underlyingTaskDict[@"udid"]){
        deviceInfo[@"UniqueDeviceID"] = self.underlyingTaskDict[@"udid"];
    }
    if (self.underlyingTaskDict[@"product_version"]){
        deviceInfo[@"ProductVersion"] = self.underlyingTaskDict[@"product_version"];
    }
  	if (self.underlyingTaskDict[@"mlb_serial"]){
        deviceInfo[@"MLBSerialNumber"] = self.underlyingTaskDict[@"mlb_serial"];
    }
    if (self.underlyingTaskDict[@"serial"]){
        deviceInfo[@"SerialNumber"] = self.underlyingTaskDict[@"serial"];
    }
    if (self.underlyingTaskDict[@"bt"]){
        deviceInfo[@"BluetoothAddress"] = [self.underlyingTaskDict[@"bt"] stringByReplacingOccurrencesOfString:@":" withString:@""];
    }
    if (self.underlyingTaskDict[@"wifi"]){
        deviceInfo[@"WifiAddress"] = [self.underlyingTaskDict[@"wifi"] stringByReplacingOccurrencesOfString:@":" withString:@""];
    }
    if (self.underlyingTaskDict[@"idfa"]){
        deviceInfo[@"IDFA"] = self.underlyingTaskDict[@"idfa"];
    }
  	return deviceInfo;
}

- (NSString *)description {
	if (self.underlyingTaskDict == nil){
		return @"<Task empty task!!! >";
	}

	return [NSString stringWithFormat:@"<Task app:%@  keyword:%@ account:%@ >",
		self.underlyingTaskDict[@"app_id"],
		self.underlyingTaskDict[@"keyword"],
		self.underlyingTaskDict[@"email"]];
}

@end
