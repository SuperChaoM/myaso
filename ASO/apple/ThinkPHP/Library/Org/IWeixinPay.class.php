<?php
/**
 * @开发工具: PhpStorm.
 * @文件名: IWeixinPay.class.php
 * @类功能: 微信支付
 * @开发者: 小菜鸟
 * @开发时间: 15/7/10
 * @版本: version 1.0
 */

class IWeixinPay {

    private $api_key, $appid, $mch_id, $create_ip;
    /**
     * 初始化
     */
    public function __construct() {
        $this->api_key = C('wx_pay_api_key');
        $this->appid   = C('wx_pay_appid');
        $this->mch_id  = C('wx_pay_mch_id');
        $this->create_ip = C('wx_pay_ip');
    }

    /**
     * @功能：生成随机字符串
     * @开发者：小菜鸟
     * @param int $length
     * @return string
     */
    public function create_nonce_str($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * @功能：签名
     * @开发者：小菜鸟
     * @param $params
     * @param $key
     * @return mixed
     */
    public function get_params_sign($params, $key) {
        ksort($params);
        $str = array();
        foreach ($params as $k => $v) {
            $str[] = "{$k}={$v}";
        }

        $str = join('&', $str).'&key='.$key;
        $sign = strtoupper(md5($str));

        $params['sign'] = $sign;

        return $params;
    }

    /**
     * @功能：统一下单
     * @开发者：小菜鸟
     * 'body'   => 'xx',
     * 'out_trade_no' => time().'',
     * 'total_fee' => 888,
     * @param $pay_data
     * @param $notify_url
     * @param $openid
     * @param string $trade_type
     * @return
     * @throws Exception
     */
    public function prepay($pay_data,$notify_url, $openid,$trade_type='JSAPI') {
        $pay_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $params = array(
            'appid'  => $this->appid,
            'mch_id' => $this->mch_id,
            'spbill_create_ip' => $this->create_ip,
            'nonce_str'  => $this->create_nonce_str(),
            'notify_url' => $notify_url,
            'trade_type' => $trade_type,
        );
        if ($openid) {
            $params['openid'] = $openid;
        }
        $params = array_merge($params, $pay_data);

        $params = $this->get_params_sign($params, $this->api_key);

        require_once __DIR__.'/IXml.class.php';
        $xml = Array2XML::createXML('xml', $params);
        $send_str = $xml->saveXML();

        $ch = curl_init($pay_unifiedorder_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_str);

        $result = curl_exec($ch);
        curl_close($ch);

        $result= XML2Array::createArray($result);

        return $result['xml'];
    }

    /**
     * @功能：订单查询
     * @开发者：小菜鸟
     * @param $order_no
     */
    public function order_query($order_no) {
        $query_url = 'https://api.mch.weixin.qq.com/pay/orderquery';

        $params = array(
            'appid'  => $this->appid,
            'mch_id' => $this->mch_id,
            'nonce_str'     => $this->create_nonce_str(),
            'out_trade_no'  => $order_no,
        );

        $params = $this->get_params_sign($params, $this->api_key);

        require_once __DIR__.'/IXml.class.php';
        $xml = Array2XML::createXML('xml', $params);
        $send_str = $xml->saveXML();

        $ch = curl_init($query_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_str);

        $result = curl_exec($ch);
        curl_close($ch);

        $result= XML2Array::createArray($result);

        return $result['xml'];
    }

    /**
     * @功能：退款
     * @开发者：小菜鸟
     * @param $out_trade_no : 商户侧传给微信的订单号
     * @param $out_refund_no : 商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
     * @param $total_fee : 订单总金额，单位为分，只能为整数，详见支付金额
     * @param $refund_fee : 退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
     *
     * @return
     * {
            return_code: "SUCCESS",
            return_msg: "OK",
            appid: "wxc7678d5f5d7e732b",
            mch_id: "1246502801",
            nonce_str: "efz8JMo3XmKR8HXM",
            sign: "FC7D60449CE84BFE701724E62DA4E740",
            result_code: "SUCCESS",
            transaction_id: "4005892001201605256283381080",
            out_trade_no: "ten_7",
            out_refund_no: "xten_7",
            refund_id: "2005892001201605300258119697",
            refund_channel: "",
            refund_fee: "1",
            coupon_refund_fee: "0",
            total_fee: "1",
            cash_fee: "1",
            coupon_refund_count: "0",
            cash_refund_fee: "1"
        }
     */
    public function refund($out_trade_no, $out_refund_no, $total_fee, $refund_fee) {
        $refund_url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $params = [
            'appid' => $this->appid,
            'mch_id'=> $this->mch_id,
            'nonce_str'     => $this->create_nonce_str(),
            'out_trade_no'  => $out_trade_no,
            'out_refund_no' => $out_refund_no,
            'total_fee'     => $total_fee,
            'refund_fee'    => $refund_fee,
            'op_user_id'    => $this->mch_id,
        ];
        $params = $this->get_params_sign($params, $this->api_key);

        require_once __DIR__.'/IXml.class.php';
        $xml = Array2XML::createXML('xml', $params);
        $send_str = $xml->saveXML();

        $ch = curl_init($refund_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_str);

        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, __DIR__.'/Wxpay/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,__DIR__.'/Wxpay/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO, __DIR__.'/Wxpay/rootca.pem');

        $result = curl_exec($ch);
        curl_close($ch);

        $result= XML2Array::createArray($result);

        return $result['xml'];
    }

    /**
     * @功能：企业付款给个人
     * @开发者：小菜鸟
     * @param $openid: openid
     * @param $fee   : 金额
     * @param $trade_no: 唯一订单号
     * @param $note : 备注
     * @return
     * 失败时返回:
     * {
     * return_code: "SUCCESS",
     * return_msg: "帐号余额不足，请用户充值或更换支付卡后再支付.",
     * result_code: "FAIL",
     * err_code: "NOTENOUGH",
     * err_code_des: "帐号余额不足，请用户充值或更换支付卡后再支付."
     * }
     * =================================================================
     * 成功时返回:
     * {
     * return_code: "SUCCESS",
     * return_msg: "",
     * mch_appid: "wxc7678d5f5d7e732b",
     * mchid: "1246502801",
     * device_info: "",
     * nonce_str: "6nWNdG0se5SLx4X9",
     * result_code: "SUCCESS",
     * partner_trade_no: "1464576048",
     * payment_no: "1000018301201605300412904840",
     * payment_time: "2016-05-30 10:40:49"
     * }
     * @throws Exception
     */
    public function transfer($openid, $fee,$trade_no, $note='佣金发放') {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

        $params = [
            'mch_appid' => $this->appid,
            'mchid'=> $this->mch_id,
            'nonce_str'     => $this->create_nonce_str(),
            'partner_trade_no'  => $trade_no,
            'openid' => $openid,
            'check_name'    => 'NO_CHECK',
            'amount'     => $fee,
            'desc'    => $note,
            'spbill_create_ip'    => $this->create_ip,
        ];
        $params = $this->get_params_sign($params, $this->api_key);

        require_once __DIR__.'/IXml.class.php';
        $xml = Array2XML::createXML('xml', $params);
        $send_str = $xml->saveXML();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_str);

        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, __DIR__.'/Wxpay/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,__DIR__.'/Wxpay/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO, __DIR__.'/Wxpay/rootca.pem');

        $result = curl_exec($ch);
        curl_close($ch);

        $result= XML2Array::createArray($result);

        return $result['xml'];
    }

    /**
     * @功能：异步通知
     * @开发者：小菜鸟
     */
    public function app_notify() {
        //获取参数
        $post_data = file_get_contents("php://input");

        file_put_contents('dd.log', $post_data, FILE_APPEND);
        //数据处理
        require_once __DIR__.'/IXml.class.php';
        $result = XML2Array::createArray($post_data);
        $result = $result['xml'];

        $sign = $result['sign'];
        unset($result['sign']);

        $params = $this->get_params_sign($result, $this->api_key);

        //签名不通过
        $verify = $params['sign'] != $sign ? false : true;
        $return = array('return_code' => $verify ? 'SUCCESS' : 'FAIL', 'return_msg' => $verify ? '签名成功' : '签名失败');
        $xml = Array2XML::createXML('xml', $return);
        $return = $xml->saveXML();

        return array($verify, $result, $return);
    }
}