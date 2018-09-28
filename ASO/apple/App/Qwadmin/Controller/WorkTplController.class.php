<?php
/**
*
* 版权所有：河马社区<www.hemashequ.com>
* 作    者：小河马
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：个人中心控制器。
**/

namespace Qwadmin\Controller;

class WorkTplController extends ComController {

	public function index(){
        $config = ['tpl_name' =>''];
        $param = $this->get_param($config);
        $where = [];
        if ($param['tpl_name']) {
            $where['tpl_name'] = ['like', "%{$param['tpl_name']}%"];
        }

		$list = M('work_tpl')->where($where)->order('tpl_id desc')->auto_page()->select();
		$this->assign('list',$list);
        $this->assign($param);
		$this->display();
	}

	public function add(){
        if (IS_POST) {
            $config = [
                'tpl_name' =>self::NOT_EMPTY,
                'plain' => '',
            ];

            $data = $this->get_param($config);
            $data['plain'] = serialize($data['plain']);

            M('work_tpl')->add($data);

            $this->success('新增成功', U('index'));
        }
        $work_plain=$this->get_init_plain();
        $this->assign('work_plain', json_encode($work_plain, JSON_UNESCAPED_UNICODE));
        $this->display();
	}

    public function del() {
        $tpl_id = intval($_REQUEST['tpl_id']);
        M('work_tpl')->where(['tpl_id' => $tpl_id])->delete();
        $this->success('删除成功');
    }

    public function edit() {
        $tpl_id = $_REQUEST['tpl_id'];
        $m_work = M('work_tpl');
        if (IS_POST) {
            $config = [
                'tpl_name' =>self::NOT_EMPTY,
                'plain' => '',
            ];

            $data = $this->get_param($config);
            $data['plain'] = serialize($data['plain']);
            M('work_tpl')->where(['tpl_id' => $tpl_id])->save($data);
            $this->success('修改成功!', U('index'));
        }

        $vo = $m_work->where(['tpl_id' => $tpl_id])->find();
        $work_plain= empty($vo['plain']) ? $this->get_init_plain() : unserialize($vo['plain']);
        $this->assign('work_plain', json_encode($work_plain, JSON_UNESCAPED_UNICODE));
        $this->assign('plain_x', json_encode(array_keys($work_plain)));
        $this->assign('plain_y', json_encode(array_values($work_plain)));
        $this->assign('vo', $vo);
        $this->display();
    }

    //获取初始化工作计划
    private function get_init_plain() {
        $result = [];
        for($i=0; $i<24;++$i) {
            $plain_0 = str_pad($i,2,'0', STR_PAD_LEFT).':00';
            $plain_1 = str_pad($i,2,'0', STR_PAD_LEFT).':30';
            $result[$plain_0] = '';
            $result[$plain_1] = '';
        }
        return $result;
    }
}