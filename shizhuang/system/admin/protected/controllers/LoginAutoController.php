<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 自动登录
* ==============================================
* @date: 2016-10-21
* @author: luoxue
* @version:
*/
class LoginAutoController extends Controller{
	
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['accountId']) && !empty($_POST['accountId']))
			$condition[]="account_id='{$_POST['accountId']}'";
	}
	
}