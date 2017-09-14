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
class GameController extends Controller{
	
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="game_name = '{$_POST['name']}'";
	}
}