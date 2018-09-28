<?php
/**
 * @开发工具: PhpStorm.
 * @文件名: ISms.class.php
 * @类功能: //todo
 * @开发者: 小菜鸟
 * @开发时间: 16/8/15
 * @版本: version 1.0
 */
class ISms {
    private $user_name, $password, $company;
    public function __construct() {
        $this->user_name = C('sms_user_name');
        $this->password  = md5(C('sms_password'));
        $this->company   = C('sms_company');
    }

    public function send_sms($phone, $msg) {
        $msg = urlencode("【{$this->company}】{$msg}");
        $url = "http://api.smsbao.com/sms?u={$this->user_name}&p={$this->password}&m={$phone}&c={$msg}";
        $result = file_get_contents($url);
        return $result;
    }
}