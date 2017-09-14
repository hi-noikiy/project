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
    //合服
	public function actionTogether(){
        $model = GameServer::model();
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        $sql = "select game_id,server_id,server_name,together from {{game_server}} where game_id='$gameId'";
        $model->findAllBySql($sql);




        $this->renderPartial('together');
    }
}