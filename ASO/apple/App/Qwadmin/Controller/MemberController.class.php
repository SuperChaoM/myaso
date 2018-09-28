<?php
/**
*
* 版权所有：河马社区<www.hemashequ.com>
* 作    者：小河马
* 日    期：2016-01-20
* 版    本：1.0.0
* 功能说明：用户控制器。
*
**/

namespace Qwadmin\Controller;
class MemberController extends ComController {

    public function index(){
		$field = isset($_GET['field'])?$_GET['field']:'';
		$keyword = isset($_GET['keyword'])?htmlentities($_GET['keyword']):'';
		$order = isset($_GET['order'])?$_GET['order']:'DESC';
		$where = '';
		
		$prefix = C('DB_PREFIX');
		if($order == 'asc'){
			$order = "{$prefix}member.t asc";
		}elseif(($order == 'desc')){
			$order = "{$prefix}member.t desc";
		}else{
			$order = "{$prefix}member.uid asc";
		}
		if($keyword <>''){
			if($field=='user'){
				$where = "{$prefix}member.user LIKE '%$keyword%'";
			}
			if($field=='phone'){
				$where = "{$prefix}member.phone LIKE '%$keyword%'";
			}
			if($field=='qq'){
				$where = "{$prefix}member.qq LIKE '%$keyword%'";
			}
			if($field=='email'){
				$where = "{$prefix}member.email LIKE '%$keyword%'";
			}
		}

		$user = M('member');
		$list  = $user->auto_page()
            ->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.title")
			->order($order)
			->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")
			->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
			->where($where)->select();

        $this->assign('list',$list);
		$group = M('auth_group')->field('id,title')->select();
		$this->assign('group',$group);
		$this->assign('keyword', $keyword);
		$this -> display();
    }
	
	public function del(){
		$uids = isset($_REQUEST['uids'])?$_REQUEST['uids']:false;

		//uid为1的禁止删除
		if($uids==1 or !$uids){
			$this->error('参数错误！');
		}
		if(is_array($uids)) 
		{
			foreach($uids as $k=>$v){
				if($v==1){//uid为1的禁止删除
					unset($uids[$k]);
				}
				$uids[$k] = intval($v);
			}
			if(!$uids){
				$this->error('参数错误！');
				$uids = implode(',',$uids);
			}
		}

		$map['uid']  = array('in',$uids);
		if(M('member')->where($map)->delete()){
			M('auth_group_access')->where($map)->delete();
			$this->success('恭喜，用户删除成功！');
		}else{
			$this->error('参数错误！');
		}
	}
	
	public function edit(){
		$uid = isset($_GET['uid'])?intval($_GET['uid']):false;
		if($uid){	
			$prefix = C('DB_PREFIX');
			$user = M('member');
			$member  = $user->field("{$prefix}member.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->where("{$prefix}member.uid=$uid")->find();

		}else{
			$this->error('参数错误！');
		}
		
		$usergroup = M('auth_group')->field('id,title')->select();
		$this->assign('usergroup',$usergroup);

		$this->assign('member',$member);
		$this -> display();
	}
	
	public function update($ajax=''){
		if($ajax=='yes'){
			$uid = I('get.uid',0,'intval');
			$gid = I('get.gid',0,'intval');
			M('auth_group_access')->data(array('group_id'=>$gid))->where("uid='$uid'")->save();
			die('1');
		}
		
		$uid = isset($_POST['uid'])?intval($_POST['uid']):false;
		$user = isset($_POST['user'])?htmlspecialchars($_POST['user'], ENT_QUOTES):'';
		$group_id = isset($_POST['group_id'])?intval($_POST['group_id']):0;
		if(!$group_id){
			$this->error('请选择用户组！');
		}
		$password = isset($_POST['password'])?trim($_POST['password']):false;
		if($password) {
			if (strlen($password) < 6) {
				$this->error('密码必须6位以上,并且包含特殊字符');
			}
			if(!preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$password)){ //不允许特殊字符
				$this->error('密码必须6位以上,并且包含特殊字符');
			}
			$data['password'] = password($password);
		}
		$head = I('post.head','','strip_tags');
		if($head<>'') {
			$data['head'] = $head;
		} else {
			$data['head'] = 'Public/head.png';
		}
		$data['real_name'] = I('real_name');
		$data['sex'] = isset($_POST['sex'])?intval($_POST['sex']):0;
		$data['birthday'] = isset($_POST['birthday'])?strtotime($_POST['birthday']):0;
		$data['phone'] = isset($_POST['phone'])?trim($_POST['phone']):'';
		$data['qq'] = isset($_POST['qq'])?trim($_POST['qq']):'';
		$data['email'] = isset($_POST['email'])?trim($_POST['email']):'';
		$data['t'] = time();
		if(!$uid){
			if($user==''){
				$this->error('用户名称不能为空！');
			}
			if(!$password){
				$this->error('用户密码不能为空！');
			}
			if(M('member')->where("user='$user}'")->count()){
				$this->error('用户名已被占用！');
			}
			$data['user'] = $user;
			$uid = M('member')->data($data)->add();
			M('auth_group_access')->data(array('group_id'=>$group_id,'uid'=>$uid))->add();
		}else{
			M('auth_group_access')->data(array('group_id'=>$group_id))->where("uid=$uid")->save();
			M('member')->data($data)->where("uid=$uid")->save();
		}
		$this->success('操作成功！');
	}

	public function add(){
		$usergroup = M('auth_group')->field('id,title')->select();
		$this->assign('usergroup',$usergroup);
		$this -> display();
	}
}