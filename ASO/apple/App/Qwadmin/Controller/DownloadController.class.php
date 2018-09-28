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

class DownloadController extends ComController {

    public function clear_email() {
        $sql = "truncate table qw_download";
        M()->execute($sql);
        $this->success('清空成功');
    }

	public function index(){
		$config = ['email' => '', 'create_time_s' => '', 'create_time_e' => '','status'=>''];
		$param  = $this->get_param($config);

		$where = [];
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

		$list = M('download')->where($where)->auto_page()->order('id desc')->select();

		$this->assign($param);
		$this->assign('status_name', \AccountStatus::$status_name);
		$this->assign('list', $list);
		$this -> display();
	}
    
     private function export_excel($where) {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $list = M('download')->where($where)->order('id desc')->select();

        $result = '';
        foreach ($list as $d) {
            $result .= "{$d['email']} {$d['password']} {$d['serial']} {$d['imei']} {$d['bt']} {$d['wifi']} {$d['udid']} {$d['ecid']} {$d['product_type']} {$d['product_version']} {$d['build_version']} {$d['hardware_platform']} {$d['hardware_model']} {$d['mlb_serial']} {$d['idfa']}\r\n";
        }
        $file_name =  'ASO账号'.date('Y-m-d').'.txt';
        $this->down_file($file_name, $result);
    }
 
    private function create_uuid($prefix = ""){    //可以指定前缀
      $str = md5(uniqid(mt_rand(), true));
      $uuid  = substr($str,0,8) . '-';
      $uuid .= substr($str,8,4) . '-';
      $uuid .= substr($str,12,4) . '-';
      $uuid .= substr($str,16,4) . '-';
      $uuid .= substr($str,20,12);
      return $prefix . $uuid;
    }

    public function import_file() {
        if (!IS_POST) {
            $this->display();
            exit;
        }
        if(!isset($_FILES["file"]["tmp_name"])) {
            $this->error('没有上传文件');
        }
        $result = 0;
        $data = file_get_contents($_FILES["file"]["tmp_name"]);
        if(!$data){
            $this->error('上传文件失败！');
        }
        $m_download =  M('download');
        $m_download->startTrans();
        $data = explode("\n", $data);
        foreach ($data as $d) {
            $d = trim($d);
            $d = preg_replace("/ +/", " ", $d);
            list ($email, $password, $serial, $imei, $bt, $wifi, $udid, $ecid, $productType, $productVersion, $buildVersion, $hardwarePlatform, $hardwareModel, $mlbSerial) = explode(' ', $d);
            if ($d) {
                $add_data = [
                    'email' => trim($email),
                    'password' => trim($password),
                    'serial' => trim($serial),
                    'imei' => trim($imei),
                    'bt' => trim($bt),
                    'wifi' => trim($wifi),
                    'udid' => trim($udid),
                    'ecid' => trim($ecid),
                    'product_type' => trim($productType),
                    'product_version' => trim($productVersion),
                    'build_version' => trim($buildVersion),
                    'hardware_platform' => trim($hardwarePlatform),
                    'hardware_model' => trim($hardwareModel),
                    'mlb_serial' => trim($mlbSerial),
                    'idfa' => strtoupper($this->create_uuid())
                ];
                $where = ['email' => trim($email)];
                $cnt = $m_download->where($where)->count();
                if ($cnt) {
                    continue;
                }
                $add_ret = $m_download->add($add_data);
                if ($add_ret) {
                    ++$result;
                }
            }
        }
        $m_download->commit();
        $this->success("导入文件成功,更新记录{$result}条");
    }
	public function del() {
		$id = $_REQUEST['id'];
		if (!$id) {
			$this->error('id不能为空');
		}
		$where = ['id' => $id];
		M('download')->where($where)->delete();
		$this->success('删除成功');
	}

}
