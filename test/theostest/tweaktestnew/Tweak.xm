%hook SpringBoard
-(void)applicationDidFinishLaunching:(id)application {

    %orig;
    NSLog(@"###cargo tw# applicationDidFinishLaunching");
	SEL search = @selector(new_test);
	if ([self respondsToSelector:search]){
          [self performSelector:search];
	} else {
		NSLog(@"###cargo tw# not define new_test");
	}
}

%new
- (void)new_test {
	NSLog(@"###cargo tw# new test");
	
}


%end

