<?php
/**
 *
 * 函数：日志记录
 * @param  string $log   日志内容。
 * @param  string $name （可选）用户名。
 *
 **/
function addlog($log,$name=false){

}


/**
 *
 * 函数：获取用户信息
 * @param  int $uid      用户ID。
 * @param  string $name  数据列（如：'uid'、'uid,user'）
 *
 **/
function member($uid,$field=false) {
	$model = M('Member');
	if($field){
		return $model ->field($field)-> where(array('uid'=>$uid)) -> find();
	}else{
		return $model -> where(array('uid'=>$uid)) -> find();
	}
}