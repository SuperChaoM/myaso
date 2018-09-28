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

namespace Qwadmin\Controller;
class MenuController extends ComController {

    public function index(){
		$m = M('auth_rule');
		$list = $m->order('o asc')->select();
		$list = $this->getMenu($list);
        $this->assign('list',$list);
		$this->display();
    }
	
	public function del(){
		$ids = isset($_REQUEST['ids'])?$_REQUEST['ids']:false;

		if(!is_array($ids)){
            $ids = [$ids];
        }

		$map = [
			'id' => ['in', $ids],
			'pid'=> ['in', $ids],
			'_logic' => 'or',
		];

		if(M('auth_rule')->where($map)->delete()){
			$this->success('恭喜，菜单删除成功！');
		}else{
			$this->error('参数错误！');
		}
	}
	
	public function edit($id=0){
		$id = intval($id);
		$m = M('auth_rule');

		$currentmenu = $m->where(['id' => $id])->find();
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
			M('auth_rule')->data($data)->where("id='{$id}'")->save();
		}else{
			M('auth_rule')->data($data)->add();
		}
		
		$this->success('操作成功！');
	}

	public function add(){
		$option = M('auth_rule')->order('o ASC')->select();
		$option = $this->getMenu($option);
		$this->assign('option',$option);
		$this -> display();
	}
}