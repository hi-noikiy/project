<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 区服
* ==============================================
* @date: 2016-6-8
* @author: luoxue
* @version:
*/
class GameServerController extends Controller{
	
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="server_name like '%{$_POST['name']}%'";
	}
	public function _order(&$order){
		$order = 'server_id desc';
	}
	public function _params(&$params){
		$params = array();
		
		$gameList = Game::model()->getGame();
		
		$params['gameList'] = $gameList;
	}
}