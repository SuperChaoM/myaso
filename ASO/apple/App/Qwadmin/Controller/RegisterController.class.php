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

class RegisterController extends ComController {

    public function clear_email() {
        $sql = "truncate table qw_apple";
        M()->execute($sql);
        $this->success('清空成功');
    }

	public function index(){
		$config = ['email' => '', 'create_time_s' => '', 'create_time_e' => '','device_id' =>'','status'=>''];
		$param  = $this->get_param($config);

		$where = [];
		if ($param['device_id']) {
			$where['device_id'] = $param['device_id'];
		}
		if ($param['status']) {
			$where['status'] = $param['status'];
		}

		if ($param['email']) {
			$where['email'] = $param['email'];
		}
		$time_where_list = [];
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

		$list = M('apple')->where($where)->auto_page()->order('id desc')->select();

		$this->assign($param);
		$this->assign('status_name', \Email::$status_name);
		$this->assign('list', $list);
		$this -> display();
	}

    private function export_excel($where) {
        $list = M('apple')->where($where)->order('id desc')->select();
        $filename = date('Y-m-d下载的注册文件').'.txt';
        $result = [];
        foreach ($list as $d) {
            $result[] = $d['email'].'|'.$d['password'];
        }
        $result = join("\r\n", $result);
        $this->down_file($filename, $result);
    }

    public function import_file() {
        if (IS_POST) {
            if(isset($_FILES["file"]["tmp_name"]))//检测$_FILES变量中是否存在数据，这里是传过来的 （input type = file ）
            {
                $result = 0;
                $data = file_get_contents($_FILES["file"]["tmp_name"]);
                if($data){
                    $m_apple =  M('apple');
                    $m_apple->startTrans();
                    $data = explode("\n", $data);
                    foreach ($data as $d) {
                        $d = trim($d);
                        list ($email, $password) = explode('|', $d);
                        if ($d) {
                            $add_data = ['email' => trim($email), 'password' => trim($password)];
                            $where = ['email' => trim($email)];
                            $cnt = $m_apple->where($where)->count();
                            if ($cnt) {
                                continue;
                            }
                            $add_ret = $m_apple->add($add_data);
                            if ($add_ret) {
                                ++$result;
                            }
                        }
                    }
                    $m_apple->commit();
                    $this->success("导入文件成功,更新记录{$result}条");
                } else {
                    $this->error('上传文件失败！');
                }
            } else {
                $this->error('没有上传文件');
            }

            return;
        }
        $this->display();
    }


	public function check() {
		$id = $_REQUEST['id'];
		$where = ['id' => $id];
		$now_time = date('Y-m-d H:i:s');
		$data = ['status' => \Email::CHECK_DONE, 'check_time' => $now_time, 'check_done' => $now_time];
		M('apple')->where($where)->save($data);
		$this->success('设置过检成功');
	}

	public function del() {
		$id = $_REQUEST['id'];
		if (!$id) {
			$this->error('id不能为空');
		}
		$where = ['id' => $id];
		M('apple')->where($where)->delete();
		$this->success('删除成功');
	}

}