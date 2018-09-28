/*
	we don't need the alert: SIM card isn't inserted
*/

%hook SBSIMLockManager

- (_Bool)_shouldSuppressAlert 
{
	return 0x1;
}

%end