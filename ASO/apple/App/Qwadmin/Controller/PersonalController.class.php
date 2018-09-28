<?php
/**
*
* 版权所有：河马社区<www.hemashequ.com>
* 作    者：小河马
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：个人中心控制器。
*
**/

namespace Qwadmin\Controller;

class PersonalController extends ComController {

	public function profile(){
		$member = M('member')->where(['uid' => $this->uid])->find();
		$this->assign('member',$member);
		$this->display();
	}
	
	public function update(){
		$uid = $this->uid;
        $config = [
            'password' => '',
            'head' => '',
            'real_name' => '',
            'phone' => '',
            'email' => '',
            'isadmin' => '',
        ];
        $param = $this->get_param($config);
        $password = $param['password'];

        if (!$password) {
            unset($param['password']);
        } else {
            if (strlen($password) < 6) {
                $this->error('密码必须6位以上,并且包含特殊字符');
            }
            $param['password'] = password($password);
        }
        $isadmin = $param['isadmin'];
        if ($uid != 1) {
            $data['isadmin'] = $isadmin == 'on' ? 1 : 0;
        }
        unset($param['isadmin']);

		$Model = M('member');
		$Model->where(['uid' => $this->uid])->save($param);

		$this->success('操作成功！');
	}
}