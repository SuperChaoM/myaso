/*
	make sure authenticate or purchase result
*/

#import "macro.h"
#import "iTunesTaskManager.h"
#import "../cargostore/StoreTaskManager.h"

%hook ISURLOperation

- (void)URLSession:(id)session task:(id)dataTask didCompleteWithEvent:(id)ev error:(id)err {
	NSString *url = [[[dataTask currentRequest] URL] absoluteString];
	NSLog(@">>>>>>>>>>>>>> url: %@", url);
	if ([url containsString:@"/WebObjects/MZFinance.woa/wa/authenticate"] ||
		[url containsString:@"/WebObjects/MZBuy.woa/wa/buyProduct"] ||
	  [url containsString:@"https://play.itunes.apple.com/WebObjects/MZPlay.woa/wa/nuDataValidateCaptchaSrv"] ){
		// go on, will call origin after my code
		// NSLog(@"###cargo hijack");
	}else{
		// no hijack, call origin and return more quickly
		%orig;
		return;
	}

	// NSLog(@">>>>>>>>>>>>>> url: %@", url);
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
	if ([url containsString:@"/WebObjects/MZFinance.woa/wa/authenticate"]) {
		// login result
		NSError *error;
		NSPropertyListFormat format = NSPropertyListXMLFormat_v1_0;
		NSDictionary *loginDict = [NSPropertyListSerialization propertyListWithData:data options:NSPropertyListImmutable format:&format error:&error];

		NSMutableDictionary *logRetDict = [NSMutableDictionary dictionary];
		logRetDict[@"ok"] = @(NO);
		while (1){
			if ([loginDict objectForKey:@"passwordToken"]){
				// login ok
				logRetDict[@"ok"] = @(YES);
				logRetDict[@"msg"] = @"ok";
				break;
			}

			if ([loginDict objectForKey:@"failureType"]){
				logRetDict[@"msg"] = loginDict[@"customerMessage"];
				break;
			}
		}

		if ([@"AMD-Action:authenticate:SP" isEqualToString:logRetDict[@"msg"]]){
			// just wait next request
		}else{
			[[iTunesTaskManager sharedManager] itunes_handleAuthenticateResult:logRetDict];
		}

		%orig;
		return;

	}
	else if ([url containsString:@"https://play.itunes.apple.com/WebObjects/MZPlay.woa/wa/nuDataValidateCaptchaSrv"]){
			NSLog(@"===yt.asobitch=== got captcha validate result");
			NSDictionary *capDict = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingAllowFragments error:nil];
			NSLog(@"===yt.asobitch=== capDict %@", capDict);
			if (capDict && capDict[@"nuDetectInfo"]){
				NSDictionary *detectInfo = capDict[@"nuDetectInfo"];
				if (![@"CaptchaCorrect" isEqualToString:detectInfo[@"interdictionResponse"]]){
					//[StoreTaskManager sharedManager].gotCaptchaImageData = NO;
				}
			}
			%orig;
			return;
	}
	else if ([url containsString:@"/WebObjects/MZBuy.woa/wa/buyProduct"]) {
		// purchase result
		NSError *error;
		NSPropertyListFormat format = NSPropertyListXMLFormat_v1_0;
		NSDictionary *purchaseDict = [NSPropertyListSerialization propertyListWithData:data options:NSPropertyListImmutable format:&format error:&error];

		NSMutableDictionary *buyRetDict = [NSMutableDictionary dictionary];
		buyRetDict[@"ok"] = @(NO);
		BOOL shouldSkip = NO;
		while (1){
			if ([rsp containsString:@"purchaseSuccess"]){
				buyRetDict[@"ok"] = @(YES);
	    		buyRetDict[@"msg"] = @"ok";
				break;
			}

			if ([rsp containsString:@"MZCommerce.CaptchaRequired"]
				|| [rsp containsString:@"MZCommerce.ASN.AlwaysSometimes"]
				|| [rsp containsString:@"MZCommerce.ASN.ExpiredPasswordToken"]
				|| [rsp containsString:@"MD-Action:buyProduct:SP"]
				|| [rsp containsString:@"MZCommerce.BadPasswordTokenNoDialog_message"]){
				shouldSkip = YES;
				break;
			}

			if ([purchaseDict objectForKey:@"failureType"]){
				NSInteger failureType = [purchaseDict[@"failureType"] integerValue];
				NSString *customerMessage = purchaseDict[@"customerMessage"];
				buyRetDict[@"msg"] = customerMessage;
				break;
			}
		}

		if (shouldSkip){
			// just wait next request
		}else{
			[[iTunesTaskManager sharedManager] itunes_handlePurchaseResult:buyRetDict];
		}

		%orig;
		return;
	}
}


%end
