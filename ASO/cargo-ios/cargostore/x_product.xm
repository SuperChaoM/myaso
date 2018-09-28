#import "StoreTaskManager.h"
#import "macro.h"
#import "msg.h"

%hook SKUIViewElementPageSection

- (void)collectionViewWillDisplayCellForItemAtIndexPath:(id)ip {
	// %log;
	%orig;


	NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
    NSString *appId = task[@"app_id"];
    if (task == nil){
        return;
    }

    if ([StoreTaskManager sharedManager].offerButton != nil){
    	return;
    }

	NSString *reuseIdentifier = MSHookIvar<NSString *>(self, "_reuseIdentifier");
	if ([@"SKUIProductLockupReuseIdentifier" isEqualToString:reuseIdentifier]){
		// it is in product page
		UICollectionViewCell *cell = MSHookIvar<UICollectionViewCell *>(self, "_lastCell");
		if (cell){
			id offerView = nil;
			NSHashTable *ofvHashTable = MSHookIvar<NSHashTable *>(cell, "_offerViews");
			if (ofvHashTable && [ofvHashTable isKindOfClass:[NSHashTable class]]){
				id ofv = [ofvHashTable anyObject];
				if ([NSStringFromClass([ofv class]) isEqualToString:@"SKUIOfferView"]){
					NSLog(@"###cargo found offer view");
					offerView = ofv;
				}
			}

			if (offerView){
				id offerButton = nil;
				NSArray *ofbvs = MSHookIvar<NSArray *>(offerView, "_offerButtonViews");
				if (ofbvs && [ofbvs isKindOfClass:[NSArray class]]){
					id ov = [ofbvs firstObject];
					if (ov && [NSStringFromClass([ov class]) isEqualToString:@"SKUIItemOfferButton"]){
						NSLog(@"###cargo found buy button element");
						offerButton = ov;
					}
				}

				if (offerButton){
					// save them for later use
					[StoreTaskManager sharedManager].offerView = offerView;
					[StoreTaskManager sharedManager].offerButton = offerButton;
					NSLog(@"###cargo offer view and button are saved");
					[[StoreTaskManager sharedManager] _updatePurchaseStatus:@"开始购买"];
					GCD_AFTER_MAIN(1)
						NSString *btnTitle = [offerButton title];
						NSLog(@"###cargo get button title is %@", btnTitle);
						if ([@"获取" isEqualToString:btnTitle]){
							NSLog(@"===yt.dragon=== offer button title is %@", btnTitle);
							// click 'GET' for me
							[offerView _showConfirmationAction:offerButton];
							long delay = (500 + arc4random() % 500);
	            CG_SLEEP(delay);
							[offerView _buttonAction:offerButton];
							NSLog(@"===yt.dragon=== clicked get button by first time");

						}else{
							// notify task fail
							[[StoreTaskManager sharedManager] appStore_markTaskFail:RET_CODE_ERR_ALREADY_PURCHASED];
						}
					GCD_END
				}
			}
		}

	}
}

%end



// hook offer button state change
%hook SKUIItemOfferButton

- (void)setProgress:(float)progress {
	// %log;
	%orig;

	if (self == [StoreTaskManager sharedManager].offerButton){
		// stop download
		if ([StoreTaskManager sharedManager].purchaseDone){
			NSLog(@"#cargo has purchase done... do not click again");
			return;
		}

		[StoreTaskManager sharedManager].purchaseDone = YES;
		NSLog(@"#cargo app is downloading now");
		GCD_AFTER_MAIN(2)
			// click again to stop downloading app
			[[StoreTaskManager sharedManager].offerView _buttonAction:[StoreTaskManager sharedManager].offerButton];
			NSLog(@"#cargo click again to stop downloading app");
			[[StoreTaskManager sharedManager] appStore_markTaskSucc];
		GCD_END
	}

}

%end
