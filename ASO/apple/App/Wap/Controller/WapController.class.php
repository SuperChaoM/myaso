<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-21
* 版    本：1.0.0
* 功能说明：前台控制器演示。
*
**/
namespace Wap\Controller;

use Common\Controller\BaseController;
use Think\Controller;
use Think\Log;

class WapController extends BaseController{
    //错误编码
    const ERR_0001 = '20001';
    const ERR_0002 = '20002';
    const ERR_0003 = '20003';
    const ERR_0004 = '20004';
    const ERR_0005 = '20005';
    const ERR_0006 = '20006';
    const ERR_0007 = '20007';
    const ERR_0008 = '20008';
    const ERR_0009 = '20009';
    const ERR_0010 = '20010';
    const ERR_0011 = '20011';
    const ERR_0012 = '20012';
    const ERR_0013 = '20013';
    const ERR_0014 = '20014';
    const ERR_0015 = '20015';

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
    /**
     * @功能：
     * @开发者：小菜鸟
     * @param $config : 配置信息
     * 调用格式如下:
    $config = array(
    'id'    => [self::NOT_EMPTY, self::ENGLISH],
    'phone' => [self::MOBILE],
    );
    $param = $this->get_param($config);
     * @param $is_remove_xss : 是否进行xss删除
     * @return array
     */
    public function get_param($config, $is_remove_xss=true) {
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
                    $this->error("字段[{$k}]:{$err_msg}");
                }
            }
            $data[$k] = $p;
        }

        //xss过滤
        if ($is_remove_xss) {
            $this->xss_filter($data);
        }

        return $data;
    }

    /**
     * @功能：xss过滤
     * @开发者：小菜鸟
     * @param $data
     */
    public function xss_filter(&$data) {
        if (is_array($data)) {
            foreach ($data as &$d) {
                $this->xss_filter($d);
            }
            unset($d);
        } else {
            $data = remove_xss($data);
        }
    }

    /**
     * @功能：检测数据类型
     * @开发者：小菜鸟
     * @param $data :数据
     * @param $type :类型
     * @return array
     */
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
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type:application/json; charset=UTF-8');
        exit(json_encode(array('ret' => 0, 'data' => $data), JSON_UNESCAPED_UNICODE));
    }

    /**
     * @功能：错误返回
     * @开发者：小菜鸟
     * @param $msg
     * @param string $err_code
     */
    public function error($msg, $err_code=self::ERR_0001) {
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type:application/json; charset=UTF-8');
        exit(json_encode(array('ret' => $err_code, 'msg' => $msg), JSON_UNESCAPED_UNICODE));
    }

    public function _initialize(){
        C(setting());
        C('DB_PREFIX', 'cs_');
        $this->assign('version', 100);
    }

    private function get_html_table($data) {
        $html = '<!DOCTYPE html>
                <html lang="zh" >
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                        <title>新订单来了</title>
                        <meta name="description" content="">
                        <meta name="author" content="Administrator">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
                    </head>
                    <body>
                ';
        foreach ($data as $k => $v) {
            $html .= "{$k}:{$v}<br>";
        }
        $html .= "</body></html>";
        return $html;
    }

    /**
     * @功能：发送邮件
     * @param $title
     * @param $data
     */
    protected function send_mail($title, $data) {
        fastcgi_finish_request();
        session_write_close();
        $mail_data = $this->get_html_table($data);

        import_org('PhpMail.class.php');
        $mail = new \PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->isHTML(true);

        $user_name = 'caihong6543@126.com';
        $mail->Host = "smtp.126.com";
        $mail->Username = $user_name;
        $mail->Password = 'txwh2008';

        $mail->setFrom($user_name, '悠悠科技');

        $address_list = C('email');
        $address_list = str_replace('，', ',', $address_list);
        $address_list = explode(',', $address_list);

        foreach ($address_list as $address) {
            $mail->addAddress($address);
        }

        $mail->Subject = $title;
        $mail->Body = $mail_data;

        $ret = $mail->send();
    }
}