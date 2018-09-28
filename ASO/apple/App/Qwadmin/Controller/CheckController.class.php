<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：个人中心控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Qiniu\Auth;

class CheckController extends ComController {

	public function index(){
		$config = [
            'email' => '', 'create_time_s' => '', 'create_time_e' => '',
            'device_num' =>'','status'=>'', 'ip' =>''];
		$param  = $this->get_param($config);

		$where = [];
		$time_where_list = [];
		if ($param['device_num']) {
			$sql = M('device')->where(['device_num' => ['like', "{$param['device_num']}%"]])->field('device_id')->select(false);
			$time_where_list[] = " device_id in ($sql)";
		}
		if ($param['status']) {
			$where['status'] = $param['status'];
		}

		if ($param['email']) {
			$where['email'] = ['like', "%{$param['email']}%"];
		}
        if ($param['ip']) {
            $where['ip'] = ['like', "{$param['ip']}%"];
        }

		if ($param['create_time_s']) {
			$time = date('Y-m-d', strtotime($param['create_time_s']));
			$time_where_list[] = "created_at >='{$time}'";
		}
		if ($param['create_time_e']) {
			$time = date('Y-m-d 23:59:59', strtotime($param['create_time_e']));
			$time_where_list[] = "created_at <='{$time}'";
		}
		if (!empty($time_where_list)) {
			$where['_string'] = join( ' and ', $time_where_list);
		}

        if ($_REQUEST['action']) {
            $this->export_excel($where);
            return;
        }

		$list = M('check')->where($where)->auto_page()->order('created_at desc')->select();
		$where['status']='成功';
		$param['success_num'] = M('check')->where($where)->count();
		$where['status']='失败';
		$param['fail_num'] = M('check')->where($where)->count();

		format_device_num($list);

		$this->assign($param);
		$this->assign('list', $list);
		$this -> display();
	}

    private function export_excel($where) {
		ini_set('memory_limit', '512M');
		set_time_limit(0);
        $list = M('check')->where($where)->order('id desc')->select();

        $result = '';
        foreach ($list as $d) {
            $result .= "{$d['email']}|{$d['password']}\r\n";
        }
        $file_name =  '过检账号'.date('Y-m-d').'.txt';
        $this->down_file($file_name, $result);
    }

	public function show_ip() {
		$config = ['device' =>'', 'ip' =>''];
		$param  = $this->get_param($config);
		$where = [];
        if ($param['ip']) {
            $where['ip'] = ['like', "{$param['ip']}%"];
        }
        if ($param['device']) {
            $where['device'] = ['like', "{$param['device']}%"];
        }
        $list = M('ip_repeat')->where($where)->auto_page()->order('id desc')->select();
        list_add_ip_address($list);

        $this->assign('list', $list);
        $this->assign($param);
        $this->display();
	}
}