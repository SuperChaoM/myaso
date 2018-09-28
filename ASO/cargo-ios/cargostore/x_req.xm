
#import <substrate.h>
#import "constants.h"
#import "macro.h"
#import "NSData+GZIP.h"
#import "StoreTaskManager.h"

// --------------------------------------------
%hook ISURLOperation

- (void)URLSession:(id)session task:(id)dataTask didCompleteWithEvent:(id)ev error:(id)err {
	NSString *url = [[[dataTask currentRequest] URL] absoluteString];
	NSLog(@">>>>>>>>>>>>>> url: %@", url);
	if ([url containsString:@"/WebObjects/MZStore.woa/wa/search"] ||
		[url containsString:@"https://play.itunes.apple.com/WebObjects/MZPlay.woa/wa/nuDataValidateCaptchaSrv"] ){
		// go on, will call origin after my code
		NSLog(@"===yt.cargo-ios===URL Match=== now Hook");
	}else{
		// all origin and return more quickly
		%orig;
		return;
	}

	NSLog(@">>>>>>>>>>>>>> url: %@", url);
	NSDictionary *task = [[StoreTaskManager sharedManager] appStore_currentTask];
	NSString *appId = [task objectForKey:@"app_id"];
	NSMutableData *raw = MSHookIvar<NSMutableData *>(self, "_dataBuffer");
	NSData *data;
	NSString *rsp;
	if ([raw isGzippedData]){
		data = [raw gunzippedData];
		rsp = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
	}else{
		data = raw;
		rsp = [[NSString alloc] initWithData:raw encoding:NSUTF8StringEncoding];
	 }
	// NSLog(@">>>>>>>>>>>>>> raw: %@", rsp);
	if ([url containsString:@"/WebObjects/MZStore.woa/wa/search"]){

		NSMutableDictionary *srDict = [NSMutableDictionary dictionary];
		srDict[@"ok"] = @(NO);
		NSDictionary *searchRetDict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
		if (nil == searchRetDict){
			NSLog(@"===yt.cargo-ios=== ERROR search result is nil ...");
			%orig;
			return;
		}

		NSDictionary *oldPageData = searchRetDict[@"pageData"];
		NSArray *oldBubbles = oldPageData[@"bubbles"];
		if ([oldBubbles count] > 0){
			NSDictionary *oldBubblesDict = oldBubbles[0];
			NSArray *oldBubblesResults = oldBubblesDict[@"results"];
			NSInteger oldIndex = -1;
			BOOL foundMyApp = NO;
			for (NSDictionary *b in oldBubblesResults){
				oldIndex++;
				if ([appId isEqualToString:b[@"id"]]){
					foundMyApp = YES;
					break;
				}
			}

			if (!foundMyApp){
				NSLog(@"===yt.cargo-ios=== ERROR my app is not found in search results");
				%orig;
				return;
			}

			srDict[@"pos"] = @(oldIndex);
			if (oldIndex < DO_NOT_MOVE_MY_APP_BEFORE_INDEX){
				// my app is in first 20, we dont touch the data
				srDict[@"mpos"] = @(oldIndex);
				NSLog(@"===yt.cargo-ios=== we do not touch the search results. my app is at %ld", (long)oldIndex);

			}else{
				NSDictionary *myAppItem = @{
					@"type": @(0),
					@"id" : appId,
					@"entity" : @"software"
				};

				NSMutableArray *bubblesResults = [NSMutableArray arrayWithArray:oldBubblesResults];
				[bubblesResults removeObjectAtIndex:oldIndex];
				NSLog(@"===yt.cargo-ios=== %@ remove from old bubbles at index %ld", appId, (long)oldIndex);
				[bubblesResults insertObject:myAppItem atIndex:MOVING_MY_APP_TO_INDEX];

				NSMutableDictionary *bubblesDict = [NSMutableDictionary dictionaryWithDictionary:oldBubblesDict];
				[bubblesDict setObject:bubblesResults forKey:@"results"];

				NSMutableDictionary *pageData = [NSMutableDictionary dictionaryWithDictionary:oldPageData];
				[pageData setObject:@[ bubblesDict ] forKey:@"bubbles"];
				NSMutableDictionary *modSearchRetDict = [NSMutableDictionary dictionaryWithDictionary:searchRetDict];
				[modSearchRetDict setObject:pageData forKey:@"pageData"];

				NSData *modData = [NSJSONSerialization dataWithJSONObject:modSearchRetDict options:0 error:nil];
				[raw setData:modData];
				srDict[@"mpos"] = @(MOVING_MY_APP_TO_INDEX);
				NSLog(@"===yt.cargo-ios=== we have moved my app to new index %ld.", (long)MOVING_MY_APP_TO_INDEX);
			}

			srDict[@"ok"] = @(YES);
		}

		%orig;
		return;
	}
}

%end
