<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-29
* 版    本：1.0.0
* 功能说明：自定义变量
*
**/

namespace Qwadmin\Controller;

class VariableController extends ComController {
	
	public function index(){
		$vars = M('setting')->where('type=1')->order('name')->select();
		$this->assign('vars',$vars);
		$this -> display();
	}

	public function add(){
		$this -> display();
	}

	public function edit($k=null){
		$where = ['k' => $k];
		$var = M('setting')->where($where)->find();
		if(!$var){
			$this->error('参数错误！');
		}
		$this->assign('var',$var);
		$this->display();
	}

	public function del(){
		$k = I('get.k');
		$where = ['k' => $k, 'type' => 1];
		if($k<>''){
			if(M('setting')->where($where)->delete()){
				$this->success('恭喜，删除成功！');
			}else{
				$this->error('参数错误！');
			}
		}else{
			$this->error('参数错误！');
		}
	}

	public function update(){
		$k = $data['k'] = I('post.k');
		$varname = I('post.varname');
		if($data['k']==''){
			$this->error('变量名不能为空。');
		}
		$where = ['k' => $k];
		if(M('setting')->where($where)->count() && $varname==''){
			$this->error('变量名称已经存在。');
		}

		$data['v'] = I('post.v');
		$data['name'] = I('post.name');
		$data['type']  = 1;//自定义

		if($varname==''){
			M('setting')->data($data)->add();
		}else{
			$where = ['k' => $varname];
			M('setting')->data($data)->where($where)->save();
		}
		
		$this->success('恭喜，操作成功！',U('index'));
	}
}