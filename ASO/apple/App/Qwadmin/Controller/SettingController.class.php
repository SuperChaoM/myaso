<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-20
* 版    本：1.0.0
* 功能说明：网站设置控制器。
*
**/

namespace Qwadmin\Controller;

class SettingController extends ComController {

    public function setting(){
		$this -> display();
    }

    public function update(){
		$data = $_POST;
		$data['sitename'] = isset($_POST['sitename'])?strip_tags($_POST['sitename']):'';
		$data['title'] = isset($_POST['title'])?strip_tags($_POST['title']):'';
		$data['keywords'] = isset($_POST['keywords'])?strip_tags($_POST['keywords']):'';
		$data['description']= isset($_POST['description'])?strip_tags($_POST['description']):'';
		$data['footer'] = isset($_POST['footer'])?$_POST['footer']:'';

		$Model = M('setting');
		foreach($data as $k=>$v){
			$Model->data(array('v'=>$v))->where("k='{$k}'")->save();
		}
		$this->success('恭喜，网站配置成功！');
    }


    /**
     * @功能：网站基础信息
     * @开发者：小菜鸟
     */
    public function wx_config() {
        if (IS_POST) {
            $m_web_config = M('wx_config');
            $m_web_config->startTrans();
            foreach ($_POST as $k => $v) {
                $m_web_config->where(['key' => $k])->save(['value' => $v]);
            }
            $m_web_config->commit();

            $this->success('修改成功!');
        }
        $config_list = M('wx_config')->select();
        $this->assign('list', $config_list);
        $this->display();
    }
}