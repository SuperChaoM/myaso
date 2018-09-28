<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * 日志处理类
 */
class Log {

    // 日志级别 从上到下，由低到高
    const EMERG     = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT     = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN      = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE    = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO      = 'INFO';  // 信息: 程序输出信息
    const DEBUG     = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    // 日志信息
    static protected $log       =  array();

    // 日志存储
    static protected $storage   =   null;

    // 日志初始化
    static public function init($config=array()){
    }

    /**
     * 记录日志 并且会过滤未经设置的级别
     * @static
     * @access public
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param boolean $record  是否强制记录
     * @return void
     */
    static function record($message,$level=self::ERR,$record=false) {
        return ;
    }

    /**
     * 日志保存
     * @static
     * @access public
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @return void
     */
    static function save($type='',$destination='') {
        return ;
    }

    /**
     * 日志直接写入
     * @static
     * @access public
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @return void
     */
    static function write($message,$level=self::ERR,$type='',$destination='') {
        return ;
    }

    /**
     * 日志直接写入
     * @static
     * @access public
     * @param string $message 日志信息
     * @param string $level 日志级别
     * @param string $destination 写入目标
     */
    static function real_write($message,$level=self::ERR, $destination='') {
        $now = date('Y-m-d H:i:s');

        //兼容数组格式
        if(is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }

        $log_path = C('LOG_PATH');
        if (!is_dir($log_path)) {
            mkdir($log_path);
        }
        if(empty($destination)) {
            $destination = $log_path.date('y_m_d').'.log';
        } else {
            $destination = $log_path.$destination;
        }

        //检测日志文件大小，超过配置大小则备份日志文件重新生成
        if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination) ) {
            rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }

        file_put_contents($destination, $now.' '.get_client_ip().' '.$_SERVER['REQUEST_URI']." {$level}: {$message}\r\n", FILE_APPEND);
    }

    /**
     * @功能：Error信息
     * @开发者：小菜鸟
     * @param $message : 日志消息
     * @param string $destination : 目标文件
     */
    public static function error($message, $destination='') {
        self::real_write($message, self::ERR, $destination);
    }

    /**
     * @功能：普通信息
     * @开发者：小菜鸟
     * @param $message : 日志消息
     * @param string $destination : 目标文件
     */
    public static function info($message, $destination='') {
        self::real_write($message, self::INFO, $destination);
    }

    /**
     * @功能：警告信息
     * @开发者：小菜鸟
     * @param $message : 日志消息
     * @param string $destination : 目标文件
     */
    public static function warn($message, $destination='') {
        self::real_write($message, self::WARN, $destination);
    }

    /**
     * @功能：警告信息
     * @开发者：小菜鸟
     * @param $message : 日志消息
     * @param string $destination : 目标文件
     */
    public static function debug($message, $destination='') {
        self::real_write($message, self::DEBUG, $destination);
    }
}