
%hook SpringBoard

 

-(void)applicationDidFinishLaunching:(id)application {

    %orig;
    //初始化提示框；  
    NSLog(@"run tweaktest2!");
}

 

%end
