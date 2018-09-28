<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-24
* 版    本：1.0.0
* 功能说明：菜单控制器。
*
**/

namespace Home\Controller;

class MenuController extends ComController {
    public function index(){
		$list  = M('agent_menu')->order('o asc')->select();
		$list = $this->getMenu($list);
		
        $this->assign('list',$list);

		$this -> display();
    }
	
	public function del(){
		
		$ids = isset($_REQUEST['ids'])?$_REQUEST['ids']:false;
		//uid为1的禁止删除
		if(is_array($ids)) 
		{
			foreach($ids as $k=>$v){
				$ids[$k] = intval($v);
			}
			if(!$ids){
				$this->error('参数错误！');
				$uids = implode(',',$uids);
			}
		}


		$map = [
			'id' => ['in', $ids],
			'pid'=> ['in', $ids],
			'_logic' => 'or',
		];

		if(M('agent_menu')->where($map)->delete()){
			$this->success('恭喜，菜单删除成功！');
		}else{
			$this->error('参数错误！');
		}
	}
	
	public function edit($id=0){
		$id = intval($id);
		$m = M('agent_menu');
		$currentmenu = $m->where("id='$id'")->find();
		if(!$currentmenu) {
			$this->error('参数错误！');
		}
		
		$option = $m->order('o ASC')->select();
		$option = $this->getMenu($option);
		$this->assign('option',$option);
		$this->assign('currentmenu',$currentmenu);
		$this -> display();
	}
	
	public function update(){
		$id = I('post.id','','intval');
		$data['pid'] = I('post.pid','','intval');
		$data['title'] = I('post.title','','strip_tags');
		$data['name'] = I('post.name','','strip_tags');
		$data['icon'] = I('post.icon');
		$data['islink'] = I('post.islink','','intval');
		$data['status'] = 1;
		$data['o'] = I('post.o','','intval');
		$data['tips'] = I('post.tips');
		if($id){
			M('agent_menu')->data($data)->where("id='{$id}'")->save();
		}else{
			M('agent_menu')->data($data)->add();
		}
		
		$this->success('操作成功！');
	}
	
	
	public function add(){

		$option = M('agent_menu')->order('o ASC')->select();
		$option = $this->getMenu($option);
		$this->assign('option',$option);
		$this -> display();
	}
}