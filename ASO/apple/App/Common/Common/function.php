<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.0
* 功能说明：模块公共文件。
*
**/


function UpImage($callBack="image",$width=100,$height=100,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.U('Upload/uploadpic').'&Width='.$width.'&Height='.$height.'&BackCall='.$callBack.'&Img='.$image.'"></iframe>
         <input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}
function BatchImage($callBack="image",$width=300, $height=100,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.U('Upload/batchpic').'&BackCall='.$callBack.'&Img='.$image.'"></iframe>
		<input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}
/**
 * @功能：列表到映射
 * @开发者：小菜鸟
 */
function list2map($list, $key, $value = array())
{
    $result = array();
    foreach ($list as $data) {
        $result[$data[$key]] = is_array($value) ? $data : $data[$value];
    }

    return $result;
}

/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k=''){
    static $data = false;
    if ($data===false) {
        $setting = M('setting')->field('k,v')->select();
        $data = list2map($setting, 'k', 'v');
    }
    if($k===''){
        return $data;
    }else{
        return $data[$k];
    }
}

/**
 * 函数：格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password){
	/*
	*后续整强有力的加密函数
	*/
	return sha1('fka^'.$password.'Wfk6!j');

}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length=6, $type='string', $convert=0){
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

/**
 * @功能：去xss
 * @开发者：小菜鸟
 * @param $val
 * @return mixed
 */
function remove_xss($val)
{
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

function curl_get_contents($url, $timeout = 30, $headers = array(), $return_status = false) {
    $curlHandle = curl_init();
    if ($headers) {
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0); //让CURL支持HTTPS访问
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($curlHandle);

    // 增加返回http状态
    if($return_status) {
        $http_status = curl_getinfo($curlHandle,CURLINFO_HTTP_CODE);
        curl_close($curlHandle);
        $return_data = array(
            'status' => $http_status,
            'data'   => $result,
        );
        return $return_data;
    }
    curl_close($curlHandle);
    return $result;
}

function curl_post_contents($url, $params, $use_http_build_query = false, $headers = array(), $time_out = 30) {
    if ($use_http_build_query) {
        $params = http_build_query($params);
    }

    $curlHandle = curl_init();
    if ($headers) {
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0); //让CURL支持HTTPS访问
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, $time_out);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, $time_out);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $params);
    $result = curl_exec($curlHandle);

    curl_close($curlHandle);
    return $result;
}

/**
 * @功能：获取地理位置
 * @开发者：小菜鸟
 * @param $ip
 * @return string
 */
function get_location_by_ip($ip='')
{
    if (empty($ip)) {
        $ip = get_client_ip();
    }
    $api_url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
    $ip_info = json_decode(file_get_contents($api_url), true);
    $data = $ip_info['data']['region'].$ip_info['data']['city'];
    return $data;
}

function get_table($table) {
    return C('DB_PREFIX').$table;
}

/**
 * @功能：从短链接中获取真实URL
 * @开发者：小菜鸟
 * @param $short_url
 * @return mixed
 */
function get_real_url_from_short_url($short_url) {
    $headers = get_headers($short_url, true);
    return $headers['Location'];
}

/**
 *引入一个目录的所有文件
 **/
function bing_include_all_php( $folder ){
    foreach( glob( "{$folder}/*.php" ) as $filename ) require_once $filename;
}

//引入枚举
bing_include_all_php(dirname(__DIR__).'/Enum');

/**
 * @功能：导入org文件
 * @开发者：小菜鸟
 * @param $file
 */
function import_org($file) {
    include_once ROOT_DIR.'/ThinkPHP/Library/Org/'.$file;
}

define('AES_KEY', 'abc1237142749731');

//系统加密
function sys_encrypt($data) {
    //return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, AES_KEY, $data, MCRYPT_MODE_CBC, AES_KEY));
    return base64_encode(openssl_encrypt(MCRYPT_RIJNDAEL_128, AES_KEY, $data, MCRYPT_MODE_CBC, AES_KEY));
}
//系统解密
function sys_decrypt($data) {
    //return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, AES_KEY, base64_decode($data), MCRYPT_MODE_CBC, AES_KEY));
    return trim(openssl_decrypt(MCRYPT_RIJNDAEL_128, AES_KEY, base64_decode($data), MCRYPT_MODE_CBC, AES_KEY));
}

/**
 * 返回数组深度
 */
function array_depth($data)
{
    return is_array($data) ? 1 + array_depth(current($data)) : 0;
}

/**
 * @功能：获取memcached
 * @开发者：小菜鸟
 * 121.41.95.9
 */
function get_mc() {
    static $mc = false;
    if ($mc) {
        return $mc;
    }
    $mc = new Memcached();
    $mc->addServer(C('MC_HOST'), C('MC_PORT'));
    return $mc;
}

/**
 * @功能：获取redis
 * @开发者：小菜鸟
 * @param $is_refresh: 是否强制刷新
 * @return bool|Redis
 */
function get_redis($is_refresh=false) {
    static $redis = false;
    if (!$redis || $is_refresh) {
        $redis = new Redis();
        $redis->popen(C('REDIS_HOST'), C('REDIS_PORT'));
        $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
    }
    return $redis;
}

function format_device_num(&$list) {
    $device_id_list = array_column($list, 'device_id');
    if (!empty($device_id_list)) {
        $device_id_list = array_unique($device_id_list);
        $device_map = M('device')->where(['device_id' => ['in', $device_id_list]])->select();
        $device_map = list2map($device_map, 'device_id', 'device_num');
        foreach ($list as $k => $v) {
            $list[$k]['device_num'] = $device_map[trim($v['device_id'])];
        }
    }
}

function get_device_id($device_num) {
    return M('device')->where(['device_num' => $device_num])->getField('device_id');
}

include_once __DIR__.'/IForm.php'; //表单
include_once __DIR__.'/work.php';  //业务相关