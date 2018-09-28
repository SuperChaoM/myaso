/*
	messages send between processes
*/

#ifndef __cargo_msg_h
#define __cargo_msg_h

#define MSG_CENTER				@"com.nkaso.cargo.msgcenter"

#define MSG_FINISH_TASK			@"com.nkaso.cargo.finishtask"

#define MSG_NEXT_TASK			@"com.nkaso.cargo.nexttask"
#define MSG_START_AUTHENTICATE	@"com.nkaso.cargo.startauthenticate"
#define MSG_FINISH_AUTHENTICATE	@"com.nkaso.cargo.finishauthenticate"
#define MSG_CAPTCHA_REQUIRED	@"com.nkaso.cargo.captcharequired"
#define MSG_FINISH_PURCHASE		@"com.nkaso.cargo.finishpurchase"
#define MSG_LAUNCH_APPSTORE		@"com.nkaso.cargo.launchappstore"
#define MSG_SUSPEND_APPSTORE	@"com.nkaso.cargo.suspendappstore"
#define MSG_ITUNES_STARTED		@"com.nkaso.cargo.itunesstarted"
#define MSG_SIMULATE_CLICK		@"com.nkaso.cargo.simulateclick"
#define MSG_ /* TODO */

// result codes
#define RET_CODE_OK 					100
#define RET_CODE_ERR_AUTHENTICATE		403
#define RET_CODE_ERR_PURCHASE			401
#define RET_CODE_ERR_ALREADY_PURCHASED 	408
#define RET_CODE_ERR_CAPTCHA_WRONG	 	411


#endif //__cargo_msg_h