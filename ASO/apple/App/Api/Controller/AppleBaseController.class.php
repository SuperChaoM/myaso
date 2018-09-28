<?php
namespace Api\Controller;

use Think\Log;

class AppleBaseController{
    const RAND_TYPE_EN = 3;
    const RAND_TYPE_INT= 1;
    const RAND_TYPE_ZH = 4;
    const RAND_TYPE_EN_INT = 5;
    const PASSWORD = 'Txwh2008';
    const APP_KEY = 'kda7878asdf';

    //定义校验类型值
    const EMAIL = 'email';          //验证不能为空
    const IP = 'ip';                //验证不能为空
    const NOT_EMPTY = 'require';    //验证不能为空
    const MOBILE = 'mobile';        //验证手机号
    const QQ = 'qq';
    const TEL = 'tel';          //验证度座机号
    const ACCOUNT = 'account';  //验证账号最短长度及最大长度
    const NUMBER = 'number';    //检测是否为number
    const URL = 'url';          //检测网站路径地址
    const ZIP = 'zip';          //检测邮编
    const INT = 'integer';      //检测是否为integer
    const DOUBLE = 'double';    //检测double
    const ENGLISH = 'english';  //检测邮编
    const PHP_NOT_EMPTY = 'require';    //验证不能为空,0也为空

    public function __construct() {
        C(setting());
        $limit_ip = C('limit_ip');
        $limit_ip = str_replace('，', ',', $limit_ip);
        $limit_ip = explode(',', $limit_ip);

        $current_ip = get_client_ip();
        if (!in_array($current_ip, $limit_ip)) {
          //  $this->error('非法访问!');
        }
    }

    public function get_param($config) {
        $data = array();
        foreach ($config as $k => $rule_list) {
            $p = $_REQUEST[$k];

            if (!is_array($rule_list)) {
                $rule_list = explode(',', $rule_list);
            }

            //不校验
            if (empty($rule_list)) {
                $data[$k] = $p;
                continue;
            }

            //逐个校验
            foreach ($rule_list as $rule) {
                list($flag,$err_msg) = $this->check_param($p, $rule);
                if (!$flag) {
                    $this->error("字段:[{$k}]".$err_msg);
                }
            }
            $data[$k] = $p;
        }

        return $data;
    }
    public function check_param($data, $type) {
        switch ($type) {
            case self::NOT_EMPTY:
                $flag = (bool)strlen($data);
                $err = empty($message) ? '数据不能为空!' : $message;
                break;
            case self::EMAIL:
                $flag = (bool)preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $data);
                $err = empty($message) ? '邮箱格式不正确!' : $message;
                break;
            case self::MOBILE:
                $flag = (bool)preg_match('/^1[0-9]{10}$/', $data);
                $err = empty($message) ? '手机号码格式不正确!' : $message;
                break;
            case self::TEL:
                $flag = (bool)preg_match('/^((\+86)|(86))?(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/', $data);
                $err = empty($message) ? '座机号码格式不正确!' : $message;
                break;
            case self::QQ:
                $flag = (bool)preg_match('/^[1-9][0-9]{4,}$/', $data);
                $err = empty($message) ? 'QQ号码格式不正确!' : $message;
                break;
            case self::NUMBER:
                $flag = (bool)preg_match('/^\d+$/', $data);
                $err = empty($message) ? '数字格式不正确!' : $message;
                break;
            case self::URL:
                $flag = (bool)preg_match('/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/', $data);
                $err = empty($message) ? '网址格式不正确!' : $message;
                break;
            case self::ZIP:
                $flag = (bool)preg_match('/^\d{6}$/', $data);
                $err = empty($message) ? '邮编格式不正确!' : $message;
                break;
            case self::INT:
                $flag = (bool)preg_match('/^[-\+]?\d+$/', $data);
                $err = empty($message) ? '整形数据格式不正确!' : $message;
                break;
            case self::DOUBLE:
                $flag = (bool)preg_match('/^[-\+]?\d+(\.\d+)?$/', $data);
                $err = empty($message) ? '浮点型数据格式不正确!' : $message;
                break;
            case self::ENGLISH:
                $flag = (bool)preg_match('/^[A-Za-z]+$/', $data);
                $err = empty($message) ? '英文格式不正确!' : $message;
                break;
            case self::PHP_NOT_EMPTY:
                $flag = (bool)!empty($data);
                $err = empty($message) ? '数据不能为空!' : $message;
                break;
            default:
                $flag = true;
                $err  = '';
        }
        return [$flag, $err];
    }

    /**
     * @功能：成功返回
     * @开发者：小菜鸟
     * @param $data
     */
    public function success($data) {
        Log::info($data);
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type:application/json; charset=UTF-8');
        $result = json_encode(array('ret' => 0, 'data' => $data, 'msg'=>''), JSON_UNESCAPED_UNICODE);
        if ($_SERVER['is_encrypt']) {
            $result = encrypt($result);
        }
        exit($result);
    }

    /**
     * @功能：错误返回
     * @开发者：小菜鸟
     * @param $msg
     * @param int $err_code
     */
    public function error($msg, $err_code=-1) {
        Log::error($msg);
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type:application/json; charset=UTF-8');
        $result = json_encode(array('ret' => $err_code, 'msg' => $msg), JSON_UNESCAPED_UNICODE);
        if ($_SERVER['is_encrypt']) {
            $result = encrypt($result);
        }
        exit($result);
    }

    public function serializeJson($data) {
        Log::info($data);
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type:application/json; charset=UTF-8');
        $result = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($_SERVER['is_encrypt']) {
            $result = encrypt($result);
        }
        exit($result);
    }
}
