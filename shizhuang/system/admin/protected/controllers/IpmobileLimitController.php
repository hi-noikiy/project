<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ip、手机限制
* ==============================================
* @date: 2016-11-27
* @author: luoxue
* @version:
*/
require_once(ROOT_PATH.'/inc/config.php');
require_once(ROOT_PATH.'/inc/config_account.php');
require_once(ROOT_PATH.'/inc/function.php');
class IpmobileLimitController extends Controller{
	public $iplimitTable = 'iplimit';
	public $mobilelimitTable = 'mobilelimit';
	
	public function _condition(&$condition){
		$condition=array();
		$condition[] = "status=0";
		if(isset($_POST['ipmobile']) && !empty($_POST['ipmobile']))
			$condition[]="ipmobile = '{$_POST['ipmobile']}'";
	}
	
	
// 	public function _params(&$params){
// 		$params = array();
// 		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
// 		$gameServer = GameServer::model()->getInfo();
	
// 		$params['game'] = $game;
// 		$params['gameServer'] = $gameServer;
// 		$params['title'] = 'ip、机型封';
// 	}
	public function actionAdd(){
		$model =new IpmobileLimit;
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		if(isset($_POST["IpmobileLimit"])){
			$gameId = $_POST['gameid'];
			$_POST['IpmobileLimit']['operator'] = $adminName = Yii::app()->user->name;
			$_POST["IpmobileLimit"]['game_id'] = $_POST['gameid'];
			$model->attributes=$_POST["IpmobileLimit"];
			if($model->save()){
				$type = $_POST["IpmobileLimit"]['type'];
				$ipmobile = $_POST["IpmobileLimit"]['ipmobile'];
				
				global $accountServer;
				$accountConn = $accountServer[$gameId];
				$conn = SetConn($accountConn);
				if($type == 1)
					$sql = "insert into iplimit(ip) values ('$ipmobile')";
				else
					$sql = "insert into mobilelimit(mobilemac) values ('$ipmobile')";
				$query = @mysqli_query($conn,$sql);
				write_log(ROOT_PATH.'log','ipmobile_limit_', "sql=$sql, result=$query".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
				@mysqli_close($conn);
				$this->success('添加信息成功');
			}else
				$this->error(CHtml::errorSummary($model));
		}
		
		$this->renderPartial('add', array('title'=>'ip、机型封', 'model'=>$model, 'game'=>$game));
	}
	
	public function actionCancel($id){
		$model = IpmobileLimit::model();
		$info = $model->findByPk($id);
		$count = $model->updateByPk($id, array('status'=>1));
		if($count){
			$type = $info->type;
			$ipmobile = $info->ipmobile;
			$gameId = $info->game_id;
			global $accountServer;
			$accountConn = $accountServer[$gameId];
			$conn = SetConn($accountConn);
			if($type == 1)
				$sql = "delete from iplimit where ip='$ipmobile'";
			else
				$sql = "delete from mobilelimit where mobilemac='$ipmobile'";
			$query = @mysqli_query($conn,$sql);
			write_log(ROOT_PATH.'log','ipmobile_limit_', "sql=$sql, result=$query".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
			@mysqli_close($conn);
			$this->display('取消成功', 1);
		}else
			$this->display('取消失败', 0);
	}
}