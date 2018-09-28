%hook NSURLRequest

+ (id)requestWithURL:(NSURL *)arg1 {
 	// %log;
 	if ([@"http" isEqualToString:arg1.scheme]){
	  	if ([arg1.host containsString:@"mzstatic.com"]){
	   		 //NSLog(@"block it. mzstatic");
	         arg1 = [NSURL URLWithString:@"http://127.0.0.1/404.jpg"];
	     }
	 }
 	return %orig;
}

%end