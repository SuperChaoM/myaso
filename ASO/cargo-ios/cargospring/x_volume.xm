%hook SBVolumeHUDView

- (void)setProgress:(float)arg1 {
	%log;
	%orig;
    [self setHidden:YES];
}

%end