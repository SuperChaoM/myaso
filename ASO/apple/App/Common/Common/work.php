<?php
/**
 * @功能：列表添加图片前缀
 * @开发者：小菜鸟
 * @param $list
 * @param $img_list
 */
function list_add_img_prefix(&$list, $img_list) {
    $prefix = C('URL');
    foreach ($list as &$data) {
        foreach ($img_list as $img) {
            if (strpos($img, 'http') !== 0) {
                $data[$img] = $prefix.$data[$img];
            }
        }
    }
    unset($data);
}

function add_img_prefix($img_url) {
    $prefix = C('URL');
    return strpos($img_url, 'http') === 0 ? $img_url : $prefix.$img_url;
}

function encrypt($s) {
    $pwd = 'dka77766adsfk';
    return base64_encode(rc4($pwd, $s));
}

function decrypt($s) {
    $pwd = 'dka77766adsfk';
    return rc4($pwd, base64_decode($s));
}

function rc4 ($pwd, $data)//$pwd密钥　$data需加密字符串
{
    $key[] ="";
    $box[] ="";
    $cipher='';

    $pwd_length = strlen($pwd);
    $data_length = strlen($data);

    for ($i = 0; $i < 256; $i++)
    {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }

    for ($j = $i = 0; $i < 256; $i++)
    {
        $j = ($j + $box[$i] + $key[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $data_length; $i++)
    {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;

        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;

        $k = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }

    return $cipher;
}

function list_add_ip_address(&$list,$key='ip') {
    import_org('IP/IP.class.php');
    foreach ($list as &$d) {
        if ($d[$key]) {
            $d['address'] = trim(join('',  \IP::find($d[$key])),',');
        }
    }
}


function get_time_step() {
    $min = date('i') % 30;
    if ($min % 3 !== 0) {
        return false;
    }
    $step = intval($min / 3);
    return $step;
}

define('WORK_TASK_LIST', 'WORK_TASK');
define('WORK_TASK_STATS', 'WORK_STATS');
define('GRAPHITE_KEY_IMPRESSION_NUM', 'xy.graphite.impression_num');
define('GRAPHITE_KEY_OPENAPP_NUM', 'xy.graphite.openapp_num');
define('GRAPHITE_KEY_SUCC_NUM', 'xy.graphite.succ_num');
define('GRAPHITE_KEY_FAIL_NUM', 'xy.graphite.fail_num');
