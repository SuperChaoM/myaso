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

class DeviceController extends ComController {

	public function index(){
        $where = [];
        if ($_REQUEST['keyword']) {
            $where = [
               '_logic' => 'or',
                'device_num' => ['like', "%{$_REQUEST['keyword']}%"],
                'device_id' => ['like', "%{$_REQUEST['keyword']}%"],
            ];
        }
		$list = M('device')->where($where)->auto_page()->select();
		$this->assign('list',$list);
        $this->assign($_REQUEST);
		$this->display();
	}
	
	public function add(){
        $m_device = M('device');
        if (IS_POST) {
            $device_id = trim($_REQUEST['device_id']);
            $device_num = trim($_REQUEST['device_num']);

            $where = ['device_id' => $device_id];
            $cnt = $m_device->where($where)->count();
            if ($cnt) {
                $this->error('已经存在同名的设备ID');
            }

            $where = ['device_num' => $device_num];
            $cnt = $m_device->where($where)->count();
            if ($cnt) {
                $this->error('已经存在同名的人工编号');
            }

            $data = [
                'device_id'=> $device_id,
                'device_num'=> $device_num
            ];
            $m_device->add($data);
            $this->success('新增成功', U('index'));
        }
        $this->display();
	}

    public function del() {
        $id = $_REQUEST['id'];
        $m_device = M('device');
        $m_device->where(['id' => $id])->delete();
        $this->success('删除成功');
    }

    public function edit() {
        $id = $_REQUEST['id'];
        $m_device = M('device');
        if (IS_POST) {
            $device_id = trim($_REQUEST['device_id']);
            $device_num = trim($_REQUEST['device_num']);

            $where = ['device_id' => $device_id, 'id' => ['neq', $id]];
            $cnt = $m_device->where($where)->count();
            if ($cnt) {
                $this->error('已经存在同名的设备ID');
            }

            $where = ['device_num' => $device_num, 'id' => ['neq', $id]];
            $cnt = $m_device->where($where)->count();
            if ($cnt) {
                $this->error('已经存在同名的人工编号');
            }
            $data = [
                'device_id'=> $device_id,
                'device_num'=> $device_num
            ];
            $m_device->where(['id' => $id])->save($data);
            $this->success('修改成功!', U('index'));
        }
        $vo = $m_device->where(['id' => $id])->find();
        $this->assign('vo', $vo);
        $this->display();
    }
}