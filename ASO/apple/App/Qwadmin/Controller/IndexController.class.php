<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-09-20
* 版    本：1.0.0
* 功能说明：后台首页控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Think\Auth;
class IndexController extends ComController {
    public function index(){
        $user_id = session('uid');

        //个人信息
        $user_info = M('member as a')->join(get_table('auth_group_access as b on a.uid=b.uid'))
            ->join(get_table('auth_group as c on b.group_id=c.id'))
            ->field('a.*,c.title as role_name')
            ->where(['a.uid' => $user_id])
            ->find();

		$log = M('login_log');
		$list = $log->auto_page()->order('login_id desc')->select();
        list_add_ip_address($list);


        $this->assign('user_info', $user_info);
        $this->assign('list',$list);
		$this -> display();
    }
}