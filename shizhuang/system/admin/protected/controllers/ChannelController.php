<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 游戏设置
* ==============================================
* @date: 2016-4-28
* @author: luoxue
* @version:
*/
class ChannelController extends Controller{
	
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name like '%{$_POST['name']}%'";
	}
	
	public function _order(&$order){
		$order = 'cid asc';
	}
	
	public function _params(&$params){
		$params = array();
		$cid = Channel::model()->getCid();
		
		$params['cid'] = $cid;
	}
	
}