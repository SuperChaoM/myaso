#import "macro.h"
#import "only_god_can_read.h"
#import "StoreTaskManager.h"

%hook SKUISearchBarController

- (UISearchBar *)searchBar {
    %log;

    id cached = [StoreTaskManager sharedManager].searchBarController;
    if (cached == nil){
        [StoreTaskManager sharedManager].searchBarController = self;
    }

    return %orig;
}

%new
- (void)nkcg_goSearch {
    NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
    if(task != nil){
        UISearchBar *sBar = [self searchBar];
        NSString *keyword = [task objectForKey:@"keyword"];
        // search action has three steps:
        // 1. click search field
        // 2. input keyword
        // 3. click enter, start serach

        GCD_RUN("com.nkaso.cargo.appstore.processsearch")
            long delay = (500 + arc4random() % 500);
            CG_SLEEP(delay);
            GCD_RUN_MAIN
                // search step #1
                [self searchBarTextDidBeginEditing:sBar];
            GCD_END

            delay = (500 + arc4random() % 500);
            CG_SLEEP(delay);
            GCD_RUN_MAIN
                // search step #2
                sBar.text = keyword;
                [[StoreTaskManager sharedManager] _updatePurchaseStatus:@"开始搜索"];
            GCD_END

            delay = (500 + arc4random() % 500);
            CG_SLEEP(delay);
            GCD_RUN_MAIN
                // search step #3
                [self searchBarSearchButtonClicked:sBar];
            GCD_END
        GCD_END
    }
}


%end

%hook SKUIGridViewElementPageSection
- (int)numberOfCells {
    // %log;
    int ret = %orig;
    if ([self isTopSection] && [self isBottomSection]){
        // only one section, that is search result page!
        NSArray /* <SKUICardViewElement> */ *cardEles = MSHookIvar<NSArray *>(self, "_viewElements");
        int row = 0;
        BOOL found = [StoreTaskManager sharedManager].myAppFoundInSearchResult;
        if (found){
            return ret;
        }

        NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
        NSString *appId = task[@"app_id"];
        if (task == nil){
            return ret;
        }

        for (id cardEle in cardEles) {
            NSDictionary *attr = MSHookIvar<NSDictionary *>(cardEle, "_attributes");
            if ([appId isEqualToString:attr[@"data-content-id"]]){
                found = YES;
                [StoreTaskManager sharedManager].myAppFoundInSearchResult = YES;
                NSLog(@"###cargo my app is seen in search page with row %d", row);
                [[StoreTaskManager sharedManager] _updatePurchaseStatus:@"搜索ok"];
                break;
            }
            row++;
        }

        if (found){
            // method 1: navigate to product page
            NSIndexPath *idp = [NSIndexPath indexPathForRow:row inSection:0];
            [self collectionViewDidSelectItemAtIndexPath:idp];

            // method 2: simulate click, acts more like a human
            // int x = 50;
            // int y = 100;
           	// x += ( arc4random() % 100);
           	// y += ( arc4random() % 100);
           	// NSLog(@"###cargo request simulate touch (%d, %d)", x, y);
            // [[StoreTaskManager sharedManager] appStore_simulateClickAtX:x y:y];
        }
    }

    return ret;
}

%new
- (void)randomPointForView:(UIView *)v toPoint:(CGPoint *)p {
    CGFloat w = CGRectGetWidth(v.frame);
    CGFloat h = CGRectGetHeight(v.frame);
    CGFloat x = CGRectGetMinX(v.frame);
    CGFloat y = CGRectGetMinY(v.frame);
    y += 64; // nav bar height
    NSLog(@"x %0.1f y %0.1f w %0.1f h %0.1f", x, y ,w, h);
    CGFloat scale = [UIScreen mainScreen].scale;
    p->x = scale * (x + (long)(w * 0.1) + arc4random() % ((long)(w * 0.8)));
    p->y = scale * (y + (long)(h * 0.1) + arc4random() % ((long)(h * 0.8)));
}

%end
