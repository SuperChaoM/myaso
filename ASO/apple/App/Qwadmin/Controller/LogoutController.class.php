<?php

namespace Qwadmin\Controller;

class LogoutController extends ComController {
    public function index(){
		cookie('qw_uid',null);
		cookie('qw_pwd',null);
		session_destroy();

		$url = U("login/index");
		header("Location: {$url}");
		exit();
    }
}