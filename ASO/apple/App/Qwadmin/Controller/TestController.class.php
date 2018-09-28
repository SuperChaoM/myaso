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

class TestController extends ComController {

	public function index(){


		$this -> display();
	}

	public function token() {
        //http://developer.qiniu.com/code/v6/sdk/javascript.html#upload 文档JS
		import_org('qiniu/autoload.php');

		$accessKey = C('qiniu_ack');
		$secretKey = C('qiniu_sck');
		$auth = new Auth($accessKey, $secretKey);

		$bucket = C('qiniu_bucket');
		$upToken = $auth->uploadToken($bucket);
		$data = array('uptoken' => $upToken);
        exit(json_encode($data));
	}
	public function t() {
		$this->display();
	}
	public function update() {
		var_dump($_REQUEST);

	}
	public function login() {
		$this->display();
	}
}