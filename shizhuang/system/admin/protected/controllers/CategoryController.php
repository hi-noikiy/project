<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 网站导航
* ==============================================
* @date: 2016-5-25
* @author: luoxue
* @version:
*/
class CategoryController extends Controller{
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name = '{$_POST['name']}'";
	}
	
	public function _params(&$params){
		$params = array();
		$sort = Category::model()->getSort();
		
		$gameList = Game::model()->getGame();
		
		$params['sort'] = $sort;
		
		$params['gameList'] = $gameList;
	}
}