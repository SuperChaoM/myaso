<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-15
* 版    本：1.0.0
* 功能说明：配置文件。
*
**/
return array(
	//数据库链接配置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'qw_', // 数据库表前缀
	'DB_CHARSET'=>  'utf8',      // 数据库编码默认采用utf8

	//-------------网站根URL-------------
	'URL' =>'http://vote.3185565.com',

	//-------------mysql---------------------
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'apple', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'a', // 密码

	//----------------no sql------------------
	"MC_HOST" => '127.0.0.1',
	"MC_PORT" => 11211,
	"REDIS_HOST" => '127.0.0.1',
	"REDIS_PORT" => 6379,

    //-------------zjtest------
    //'SHOW_PAGE_TRACE'=>true, // 显示页面Trace信息
);
