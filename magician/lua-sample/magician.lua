require("TSLib")
require("sz")

toast("正在切换手机参数",1)
local plist = sz.plist
--需要和magician插件里一致
local X_DEVICE_PATH = "/private/var/mobile/Media/ytboss_device_info.plist";
local device_info_table = plist.read(X_DEVICE_PATH);
-- 蓝牙和wifi地址如果有冒号，要删掉它
device_info_table["BluetoothAddress"] = "0c510163bcdd";
device_info_table["BuildVersion"] = "15A432";
device_info_table["HWModelStr"] = "N71AP";
device_info_table["HardwarePlatform"] = "s8000";
device_info_table["IDFA"] = "A2D9E42E-5C18-DE98-2F00-319234667EF8";
device_info_table["InternationalMobileEquipmentIdentity"] = "355782070152024";
device_info_table["MLBSerialNumber"] = "F3X617412EVG2KNF";
device_info_table["ProductType"] = "iPhone8,1";
device_info_table["ProductVersion"] = "11.0.3";
device_info_table["SerialNumber"] = "F17RMEU6GRYD";
device_info_table["UniqueChipID"] = "0009446C386B8226";
device_info_table["UniqueDeviceID"] = "d84d22334af39e3bfa406068810d5feb8536732f";
device_info_table["WifiAddress"] = "0c510163bcdc";
