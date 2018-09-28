#ifndef task_ret_codes_h
#define task_ret_codes_h

#define TASK_RET_OK				100
#define TASK_ERR_ACCOUNT		101
#define TASK_ERR_CAP_GET		102
#define TASK_ERR_CAP_DATA		103
#define TASK_ERR_CAP_WRONG		104
#define TASK_ERR_CLEAN			1000
#define TASK_ERR_LOGIN			1001
#define TASK_ERR_HOME			1002
#define TASK_ERR_SEARCH_HINT	1003
#define TASK_ERR_SEARCH			1004
#define TASK_ERR_OPEN_APP		1005
#define TASK_ERR_PRE_BUY		1006
#define TASK_ERR_PW_SET			1007
#define TASK_ERR_BUY			1008
#define TASK_ERR_BUY_CAP		1009
#define TASK_ERR_DOWNLOAD		1010
#define TASK_ERR_XP				1011

#define NET_ERR_ITUNES_CONNECTION    1012   //网络错误，无法连接Itunes

#define MAGICIAN_ERR_LOGIN    1013     //改机参数错误，无法登陆

#define ACCOUNT_ERR_LOCKED    1014     //账号锁定,停用

#define TASK_ERR_TIMEOUT		2000



#endif // task_ret_codes_h
