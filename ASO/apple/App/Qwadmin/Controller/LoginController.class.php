<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-17
* 版    本：1.0.0
* 功能说明：后台登录控制器。
*
**/

namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Auth;
class LoginController extends BaseController {
    public function index(){
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
				$this -> success('您已经登录,正在跳转到主页',U("index/index"));
			}
		}
		$this -> display();
    }

    public function login(){
		$verify = isset($_POST['verify'])?trim($_POST['verify']):'';
		if (!$this->check_verify($verify,'login')) {
			$this -> error('验证码错误！',U("login/index"));
		}

		$ip = get_client_ip();
		$m_login_log = M('login_log');
		$where = ['ip' => $ip, 'create_time' =>['egt', date('Y-m-d H:i:s', strtotime('-1 days'))]];
		$cnt = $m_login_log->where($where)->count();
		if ($cnt >= 5) {
			$this->error('密码错误五次,封禁登录1天');
			exit;
		}

		$username = isset($_POST['user'])?trim($_POST['user']):'';
		$password = isset($_POST['password'])?password(trim($_POST['password'])):'';
		$remember = isset($_POST['remember'])?$_POST['remember']:0;
		if ($username=='') {
			$this -> error('用户名不能为空！',U("login/index"));
		} elseif ($password=='') {
			$this -> error('密码必须！',U("login/index"));
		}

		$model = M("Member");
		$user = $model ->field('uid,user,login_time')-> where(array('user'=>$username,'password'=>$password)) -> find();
		
		if($user) {
			//记录session
			session('uid', $user['uid']);
			session('user_name', $user['user']);
			cookie('qw_uid', sys_encrypt($user['uid']), 3600*24*365);
			cookie('qw_pwd', sys_encrypt($password), 3600*24*365);

			$group_status = M('auth_group_access as a ')->join(get_table('auth_group as b on a.group_id=b.id'))
				->where(['a.uid' => $user['uid']])->field('b.status')->getField('status');
			if ($group_status != 1) {
				$this->error('你所在的组已经禁用,或者删除');
			}

            $where = ['uid' => $user['uid']];
            $data  = [
                'login_num' => ['exp', 'login_num+1'],
                'login_time'=> date('Y-m-d H:i:s'),
                'last_login_time' => $user['login_time'],
            ];
            M('member')->where($where)->save($data);

			$url=U('index/index');
			header("Location: $url");

			$data = ['ip' => $ip, 'user_name' => $username, 'note' => '成功'];
			$m_login_log->add($data);
			exit(0);
		}else{
			$data = ['ip' => $ip, 'user_name' => $username, 'note' => '失败'];
			$m_login_log->add($data);

			$this -> error('登录失败，请重试！',U("login/index"));
		}
    }
	
	public function verify() {
		$config = array(
		'fontSize' => 14, // 验证码字体大小
		'length' => 4, // 验证码位数
		'useNoise' => false, // 关闭验证码杂点
		'imageW'=>100,
		'imageH'=>30,
		);
		$verify = new \Think\Verify($config);
		$verify -> entry('login');
	}
	
	function check_verify($code, $id = '') {
		$verify = new \Think\Verify();
		return $verify -> check($code, $id);
	}
}