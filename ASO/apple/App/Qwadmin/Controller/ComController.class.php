<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2015-09-17
 * 版    本：1.0.0
 * 功能说明：后台公用控制器。
 *
 **/

namespace Qwadmin\Controller;

use Common\Controller\BaseController;
use Think\Auth;

class ComController extends BaseController
{
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


    public $USER,$uid;

    public function _initialize()
    {
        C(setting());
		$flag = false;

        $uid  = session('uid');
        //已经在session中
        if ($uid > 0) {
            $flag = true;
            $user_name = session('user_name');
            $this->USER = array('uid' => $uid, 'user' => $user_name);
        } else {
            //cookie中查找授权
            $uid = cookie('qw_uid');
            $pwd = cookie('qw_pwd');
            if ($uid && $pwd) {
                $uid = sys_decrypt($uid);
                $pwd = sys_decrypt($pwd);

                $where = ['uid' => $uid, 'password' => $pwd];
                $user = M('member')->field('uid,user')->where($where)->find();

                if($user) {
                    $this->USER = $user;

                    //记录session
                    session('uid', $user['uid']);
                    session('user_name', $user['user']);
                    $flag = true;
                }
            }
        }

        $url = U("login/index");
        if (!$flag) {
            header("Location: {$url}");
            exit(0);
        }
        $m = M();
        $prefix = C('DB_PREFIX');
        $UID = $this->USER['uid'];
        $userinfo = $m->query("SELECT * FROM {$prefix}auth_group g left join {$prefix}auth_group_access a on g.id=a.group_id where a.uid=$UID");
        $Auth = new Auth();
        $allow_controller_name = array('Upload');//放行控制器名称
        $allow_action_name = array();//放行函数名称
        if ($userinfo[0]['group_id'] != 1 && !$Auth->check(CONTROLLER_NAME . '/' . ACTION_NAME, $UID) && !in_array(CONTROLLER_NAME, $allow_controller_name) && !in_array(ACTION_NAME, $allow_action_name)) {
            $this->error('没有权限访问本页面!');
        }

        $this->uid = $UID;
        $user = member(intval($UID));
        $this->assign('user_info', $user);


        $current_action_name = ACTION_NAME == 'edit' ? "index" : ACTION_NAME;
        $current = $m->query("SELECT s.id,s.title,s.name,s.tips,s.pid,p.pid as ppid,p.title as ptitle FROM {$prefix}auth_rule s left join {$prefix}auth_rule p on p.id=s.pid where s.name='" . CONTROLLER_NAME . '/' . $current_action_name . "'");
        $this->assign('current', $current[0]);


        $menu_access_id = $userinfo[0]['rules'];

        if ($userinfo[0]['group_id'] != 1) {

            $menu_where = "AND id in ($menu_access_id)";

        } else {

            $menu_where = '';
        }
        $menu = M('auth_rule')->field('id,title,pid,name,icon')->where("islink=1 $menu_where ")->order('o ASC')->select();
        $menu = $this->getMenu($menu);
        $this->assign('menu', $menu);

    }


    protected function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }

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
                    $this->error("字段:[{$k}]".$err_msg);
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

        public function down_file($filename, $data) {
            /* 如果是IE,进行转码,防止乱码 */
            if (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")) {
                $filename = rawurlencode($filename);
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header("Content-Disposition:attachment;filename=\"$filename\"");
            header("Content-Transfer-Encoding:binary");
            echo $data;
            exit;
        }
}