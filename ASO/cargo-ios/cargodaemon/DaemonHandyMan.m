#import "DaemonHandyMan.h"
#import "macro.h"
#import "LSApplicationWorkspace.h"
#import <objc/runtime.h>

@implementation DaemonHandyMan

- (void)daemon_reboot {
  if(self.rebootCounter == 10){
    system("killall -9 backboardd");
    self.rebootCounter = 0;
  }
}

- (void)daemon_clean {
    [self _nkcg_uninstallPlaceholderApps];
    system("rm -fr /private/var/mobile/Media/ytboss_device_info.plist");
    system("rm -fr /private/var/mobile/Media/Task.plist");
    system("rm -fr /private/var/mobile/Library/Caches/sharedCaches/com.apple.iTunesStore.NSURLCache/*");
    system("rm -fr /private/var/mobile/Library/Caches/com.apple.akd/*");
    system("rm -fr /private/var/mobile/Library/Caches/com.apple.itunesstored/*");
    system("rm -fr /private/var/mobile/Library/Cookies/*");
    system("rm -fr /private/var/mobile/Media/Downloads/*");
    system("find /private/var/mobile/Containers/Data -type d -name com.apple.AppStore | xargs rm -fr");
    system("find /private/var/containers/Data/System/ -type f -name adi.pb | xargs rm -fr");
    system("rm -fr /private/var/mobile/Library/com.apple.itunesstored/*");
    system("rm -fr /private/var/mobile/Library/Preferences/com.apple.itunesstored.plist*");
    system("rm -fr /var/mobile/Library/Caches/com.apple.nsurlsessiond/*");
    system("rm -fr /private/var/mobile/Library/MusicLibrary/AccountCache*");
    system("rm -fr /private/var/installd/Library/Caches/com.apple.mobile.installd.staging/*");
    system("rm -fr /private/var/installd/Library/Caches/com.apple.containermanagerd/*");
    system("rm -fr /private/var/mobile/Library/Caches/com.apple.storeServices.analytics/*");
    system("rm -fr /private/var/mobile/Library/Caches/com.apple.storeservices/*");
    system("cp /private/var/mobile/Media/kvs.sqlitedb /private/var/mobile/Library/com.apple.itunesstored/");
    system("chown mobile:mobile /private/var/mobile/Library/com.apple.itunesstored/kvs.sqlitedb");
}

- (void)daemon_kill {
    system("killall -9 akd");
    system("killall -9 itunesstored");
    system("killall -9 AppStore");
    system("killall -9 fairplayd.H2");
    system("killall -9 cloudd");
    system("killall -9 itunescloudd");
    system("killall -9 absd");
    system("killall -9 CloudKeychainProxy");
    system("killall -9 bird");
    system("killall -9 coreauthd");
    system("killall -9 AppleIDAuthAgent");
    system("killall -9 accountsd");
    system("killall -9 appstored");
    system("killall -9 ubd");
    system("killall -9 com.apple.accounts.dom");
    system("killall -9 mstreamd");
    system("killall -9 nesessionmanager");
    system("killall -9 adid");
    system("killall -9 MobileCal");
    system("killall -9 nsurlsessiond");
}

- (void)daemon_uninstallApp:(NSString *)bid {
    id ws = [objc_getClass("LSApplicationWorkspace") defaultWorkspace];
    if ([ws applicationIsInstalled:bid]){
        [ws uninstallApplication:bid withOptions:nil];
    }
}

- (void)_nkcg_uninstallPlaceholderApps {
    // remove all placeholder apps
    id ws = [objc_getClass("LSApplicationWorkspace") defaultWorkspace];
    NSArray *placeholders = [ws placeholderApplications];
    for (id p in placeholders){
        if ([p respondsToSelector:@selector(bundleIdentifier)]){
            NSString *bid = [p bundleIdentifier];
            NSLog(@"###cargo start uninstall placeholder %@", bid);
            [ws uninstallApplication:bid withOptions:nil];
            NSLog(@"###cargo uninstall placeholder %@ done", bid);
        }
    }
}

@end
