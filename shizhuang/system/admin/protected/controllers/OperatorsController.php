<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 运营操作
* ==============================================
* @date: 2016-6-3
* @author: luoxue
* @version:
*/
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class OperatorsController extends Controller{
    private $gmtoolTable = 'u_gmtool';
	//账号封解
	private function checkAccount($account, $type, $gameId){
		$rs = false;
		$where = ($type == 1) ? "NAME='$account'" :  "id='$account'";
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql = "SELECT * FROM account where $where limit 1";
		
		$query = @mysqli_query($conn,$sql);
		$rows = @mysqli_fetch_assoc($query);
		if($rows)
			$rs = $rows;
		mysqli_close($conn);
		return $rs;
	}
	
	private function updateAccount($accountId, $operate, $gameId){
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
        if($conn == false)
            return false;
		$rs = false;
		$sql = "update account set limitType='$operate' where id='$accountId'";
		if(mysqli_query($conn,$sql))
			$rs = true;
		write_log(ROOT_PATH.'log','limit_account_err', "sql=$sql,".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
        @mysqli_close($conn);
        return $rs;
	}
	
	private function gameAccountLimit($playerId, $accountId){
		$conn = SetConn(0);//根据SvrID连接服务器
        $table = subTable($accountId, 'u_accountlimit', 200);
		$sql = "select count(*) as count from $table where account_id='$accountId'";
		$query = @mysqli_query($conn,$sql);
		$RowCount = @mysqli_fetch_assoc($query);
		if (!$RowCount['count']){
			$sql = "insert into $table(account_id, server_id, endtime) values('$accountId', '0', '0')";
			if(mysqli_query($conn,$sql) == False)
				write_log(ROOT_PATH."log","accountlimit_log_error_", "serverId=$serverId, accountId=$accountId ".date("Y-m-d H:i:s")."\r\n"."  , sql=$sql,   ".mysqli_error($conn));
			else
				write_log(ROOT_PATH."log","accountlimit_log_", "serverId=$serverId, accountId=$accountId ".date("Y-m-d H:i:s")."\r\n");
		}
		mysqli_close($conn);
	}
	public function actionSealingSolutionLog(){
		$model = new AccountLimit;
		$criteria = new CDbCriteria;

        if($this->mangerInfo['server_id']) {
            $username = $this->mangerInfo['login_name'];
            $criteria->condition = "server_id like '{$this->mangerInfo['server_id']}%' and operator='$username'";
        }
        if(isset($_POST['accountId']) && !empty($_POST['accountId'])){
            if(isset($criteria->condition) && !empty($criteria->condition))
                $criteria->condition .= ' and account='.intval($_POST['accountId']);
            else
                $criteria->condition = 'account='.intval($_POST['accountId']);
        }

		$count = $model->count($criteria); //统计
		$pages=new CPagination($count);
		$pages->pageSize=20;
		$pages->applyLimit($criteria);
			
		$criteria->limit=$pages->pageSize;
		
		if(isset($_POST['pagination'])){
			$pagination=$_POST['pagination'];
			$criteria->offset=($_POST['pagination']-1)*$criteria->limit;
		}else {
			$pagination=1;
			$criteria->offset=0;
		}
		//排序
		$criteria->order="id desc";
		
		$info=$model->findAll($criteria);
		//print_r(CJSON::encode($info));
		$this->renderPartial('sealingSolutionLog', array('info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination));
	}
	//获取角色信息
	private function getGamePlayerField($accountId, $serverId, $field='*' ){
		$conn = SetConn($serverId);
		$playerTable = subTable($accountId, 'u_player', 200);
		$sql = "select $field from $playerTable where account_id='$accountId' and serverid='$serverId'  limit 1";
		$query = @mysqli_query($conn,$sql);
		if($query == false)
			return false;
		$rs = @mysqli_fetch_assoc($query);
		return $rs ? $rs : false;
	}
	public function actionSealingSolution(){
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		$gameServer = GameServer::model()->getInfo();
		$info = '';
		if(!empty($_POST)){
			$type = intval($_POST['type']);
			$name = $_POST['name'];
			$operate = intval($_POST['operate']);
			$reason = trim($_POST['reason']);
			$gameId = $_POST['gameid'];
			if(!$gameId)
				$this->display('请选择游戏ID', 0);
			/*if(empty($_POST['serverId']) || !is_array($_POST['serverId']))
				$this->display('请选择区服', 0);*/
			$serverId = $_POST['serverId']; //array
			if(!$name)
				$this->display('请填写账号或者账号ID', 0);
			if($operate !=2 && $reason == '')
				$this->display('请填写封号/解号原因', 0);	
			$info = $this->checkAccount($name, $type, $gameId);
			if($info == false)
				$this->display('账号或者账号ID有误！', 0);
				
			$accountId = $info['id'];
			$gamePlayerInfo = $this->getGamePlayerField($accountId, $serverId[0], 'id');
			if($gamePlayerInfo == false)
				$this->display('角色信息有误！', 0);
			$gamePlayerId = $gamePlayerInfo['id'];
			if($operate != 2){
				if($info['limitType'] == $operate)
					$this->display('该账号状态与操作相同！', 0);
				
				$adminName = Yii::app()->user->name;
				$date = date('Y-m-d H:i:s');
				$sql = "insert into {{account_limit}} (operator, addtime, reason, account,server_id, type, status)";
				$sql .=" values('$adminName', '$date', '$reason','$accountId', '{$serverId[0]}' ,'$operate','$operate')";
				$connection = Yii::app()->db;
				$command = $connection->createCommand($sql);
				$command->execute();
				//写入下架标识
				if($operate == 1){
					$this->gameAccountLimit($gamePlayerId, $accountId);
				}
				if($this->updateAccount($accountId, $operate, $gameId) == false)
					$this->display('执行失败，请重试！', 0);
				
				$this->display('操作成功！', 1);	
			}
			
		}
		
		$this->renderPartial('sealingSolution', array('title' => '账号封解', 'game' => $game, 'gameServer' => $gameServer, 'info' => $info));
	}
	//客服命令
	public function actionServiceCommand(){
		$typeList = array(''=>'请选择...', 1=>'强制下线', 2=>'禁言', 3=>'禁言解封', 6=>'游戏公告',8=>'给玩家发送邮件', /*66=>'取消游戏公告'*/);
		if($this->mangerInfo['login_name'] == 'admin')
		    $typeList['7'] = '模拟充值';
        $gameId = isset($_POST['gameId']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
		if(!empty($_POST)){

			$serverid = intval($_POST['serverid']);
			$type = intval($_POST['type']);
			$playerName = trim($_POST['name']);
			$banTime = intval($_POST['banTime']);
			$message= trim($_POST['message']);
			$awardType1 = intval($_POST['awardType1']);
			/*if(!$serverid)
				$this->error('区服不能为空！');*/
			if(!$type)
				$this->error('命令类型不能为空！');
			$conn = SetConn($serverid);
			
			$table = $this->gmtoolTable;
			if(!in_array($type, array(6, 66))){
				$playerInfo = $this->selectByName($playerName);
				if(!$playerInfo)
					$this->error('角色不存在！');
				$playerId = $playerInfo['id'];
				$accountId = $playerInfo['account_id'];
			}
			if($type == 1 || $type == 3){
				$sql = "insert into $table(type, serverid, param) values('$type', '$serverid', '$playerId')";
				$tag = ($type == 1) ? "强制下线  玩家:$playerName($playerId) 区服:$serverid" : "禁言解封  玩家:$playerName($playerId) 区服:$serverid";
			}else if($type == 2){
				$talkEndtime = strtotime($_POST['talkEndtime']);
				if(!$talkEndtime)
					$this->error('禁言时间不能为空！');
				$sql = "insert into $table(type, serverid, param, award_type1) values('$type', '$serverid', '$playerId', '$talkEndtime')";
				$tag = "禁言  玩家:$playerName($playerId) 区服:$serverid 禁言时间:$talkEndtime";
			}else if($type == 6){
				if($banTime>60)
					$this->error('循环分钟数不能超过60！');
				if(!$message)
					$this->error('游戏公告不能为空！');
				$bulletinEndtime = strtotime($_POST['bulletinEndtime']);
				
				if(!$bulletinEndtime)
					$this->error('游戏公告到期时间不能为空！');

				$sql = "insert into $table(type, serverid, param, message, award_type1) values('$type','$serverid', '$banTime', '$message', '$bulletinEndtime')";
				$tag = "游戏公告  区服:$serverid 滚动间隔时间(分):$banTime 截至时间:$bulletinEndtime 消息:$message";
			} elseif($type == 8) {
				if(!$message)
					$this->error('信息不能为空！');
				$sql = "insert into $table(type, serverid, param, message) values('$type', '$serverid', '$playerId', '$message')";
				
				$tag = "玩家发邮件  玩家:$playerName($playerId) 区服:$serverid 消息:$message";
			} else if($type == 7){
				if($awardType1 <=0)
					$this->error('充值金额不能小等于0');
				$sql = "insert into $table(type, serverid, param, award_type1) values('$type', '$serverid', '$accountId', '$awardType1')";
				
				$tag = "模拟充值  玩家:$playerName($playerId) 账号ID:$accountId 区服:$serverid 金额:$awardType1";
			}
			$conn = SetConn($serverid);
			$queryR = @mysqli_query($conn,$sql);
			$ip = Yii::app()->request->getUserHostAddress();
			$adminName = Yii::app()->user->name;
			write_log(ROOT_PATH.'log', 'customer_command_view_', "$tag, ip=$ip, operator=$adminName ".date('Y-m-d H:i:s')."\r\n");
			@mysqli_close($conn);
			if($queryR)
				$this->success('客服命令执行成功！');
			write_log(ROOT_PATH.'log', 'customer_command_error_', "sql=$sql, ".mysqli_error($conn)." operator=$adminName ".date('Y-m-d H:i:s')."\r\n");
			$this->error('失败');
		}
		
		$this->renderPartial('serviceCommand', array(
		    'title'=>'客服命令','gameId'=>$gameId,'typeList'=>$typeList,
            'game' =>$this->getGame(),'gameServer'=>$this->getServer(),
        ));
	}
	//禁言、游戏公告查询
	public function actionServiceCommandLog(){
		$gameId = Yii::app()->request->getParam('gameid');
        $gameId = isset($gameId) ? $gameId : $this->mangerInfo['game_id'];
		$serverId =Yii::app()->request->getParam('serverid');
		$type = Yii::app()->request->getParam('type');
		$typeList = array(''=>'类型', 2=>'禁言', /*6=>'游戏公告'*/);
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		$gameServer = GameServer::model()->getInfo();
		$info = array();
		$count = 0;
		if(isset($_POST['pagination'])){
			$pagination=$_POST['pagination'];
			$offset=($_POST['pagination']-1)*20;
		}else {
			$pagination=1;
			$offset=0;
		}
		if($serverId >= 0 && !empty($type)){
			$conn = SetConn($serverId);
			$where = '1=1';
			if($type == 2){
				if($serverId)
					$where .=' and serverid='.$serverId;
			} else
				$where .= ($serverId == 0) ? ' and status=0' : ' and status=0 and serverid='.$serverId;
			$table = $this->gmtoolTable;
			$where .= $type ? " and type='$type'" : '';
			$sqlCount = "select * from $table where $where";		
			$queryCount = @mysqli_query($conn,$sqlCount);
			$count = @mysqli_num_rows($queryCount);
			$sql = "select * from $table where $where order by id desc limit 20 offset $offset";
			$query = @mysqli_query($conn,$sql);
			$i = 0;
			while (@$rows = mysqli_fetch_assoc($query)){
				$info[$i]['id'] = $rows['id'];
				$info[$i]['type'] = $rows['type'];
				$info[$i]['serverid'] = $rows['serverid'];
				$info[$i]['award_type1'] = $rows['award_type1'];
				if($type == 2){
					$result = $this->selectByName($playerName);
					$info[$i]['param'] = $rows['param'].'('.$result['name'].')';
				} else {
					$info[$i]['param'] = $rows['param'];
				}
				$info[$i]['message'] = $rows['message'];
				$info[$i]['status'] = $rows['status'];
				$i++;
			}
		}

		$this->renderPartial('serviceCommandLog', array(
            'title'=>'客服命令日志','info'=>$info,'pages'=>20,'count'=>$count,'pagination'=>$pagination,
            'type'=>$type,'gameId'=>$gameId, 'serverId'=>$serverId,'typeList'=>$typeList,
            'game' =>$this->getGame(), 'gameServer' =>$this->getServer(),
		));
	}
	//滚动消息取消
	public function actionCancelServiceCommand($id, $serverId, $gameId){
		$url = $this->createUrl('operators/bulletinLog/gameid/'.$gameId.'/serverid/'.$serverId.'/type/6');
        $conn = SetConn($serverId);
        $table = $this->gmtoolTable;
        $sql = "select type from $table where id='$id' limit 1";
        $query = @mysqli_query($conn,$sql);
        $info = @mysqli_fetch_assoc($query);
        if($info['type'] != 6)
            $this->display('非公告，不能禁用！', 0, $url);
        $sql = "update $table set status=1 where id='$id'";
        if(false != mysqli_query($conn,$sql))
            $this->display('禁用公告成功', 1, $url);
        else
            $this->display('禁用公告失败', 0, $url);
	}
	//账号信息查询
	public function actionAccountInfo(){
		$type = 0;
		$info = array();
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
		if(!empty($_POST)){
			$type = intval($_POST['type']);
			/*$serverId = intval($_POST['serverid']);
			$gameId = $_POST['gameid'];*/
			$serverId = 0;
			$gameId = 9;
			$name = trim($_POST['name']);
			
			
			/*if(!$serverId)
				$this->display('请选择区服...', 0);*/
			if(!$name)
				$this->display('请输入查询的角色！', 0);
	        if($type == 0 || $type == 1){
	        	$where = ($type ==0) ? " name='$name' and serverid='$serverId'" : " id='$name'";
	        } else if($type == 2){
	        	global $accountServer;
	        	$accountConn = $accountServer[$gameId];
	        	$conn = SetConn($accountConn);
	        	$sql = "select id,NAME,dwFenBaoID,channel_account,limitType,dwFenBaoUserID from account where NAME='$name' limit 1";
	        	$query2 = @mysqli_query($conn,$sql);
	        	$result = @mysqli_fetch_assoc($query2);

	        	@mysqli_close($conn);
	        	$accountId = intval($result['id']);        	    	
	        	$info['id'] = $accountId;
	        	$info['NAME'] = $result['NAME'];
	        	$info['dwFenBaoID'] = $result['dwFenBaoID'];
	        	$info['channel_account'] = $result['channel_account'];
	        	$info['limitType']	= $result['limitType'];
	        	$info['dwFenBaoUserID'] = $result['dwFenBaoUserID'];
	        	
	        	$where = " account_id='$accountId' and serverid='$serverId'";
	        }else {
	        	$where = " account_id='$name' and serverid='$serverId'";
	        }
	       $rs_player = $this->selectByName($name);
	       if(!$rs_player){
	       		$this->display('角色不存在！', 0);
	       }
	        $info['server_id'] = 0;
	        $info['name'] = $rs_player['name'];
	        $info['user_id'] = $rs_player['id'];
	        $accountId = intval($rs_player['account_id']);
	        if(!isset($info['id'])){
		     	global $accountServer;
		        $accountConn = $accountServer[$gameId];
		        $conn = SetConn($accountConn);
			    $sql = "select id,NAME,dwFenBaoID,channel_account,limitType,dwFenBaoUserID from account where id='$accountId' limit 1";
			    $query2 = @mysqli_query($conn,$sql);
			    if(@$result = mysqli_fetch_assoc($query2)){
			    	$info['id'] = $accountId;
			    	$info['NAME'] = $result['NAME'];
			    	$info['dwFenBaoID'] = $result['dwFenBaoID'];
			    	$info['channel_account'] = $result['channel_account'];	
			    	$info['limitType']	= $result['limitType'];
			    	$info['dwFenBaoUserID'] = $result['dwFenBaoUserID'];
			    }else {
			    	$info = array();
			    }
			    mysqli_close($conn);
	        }	
		}	
		$this->renderPartial('accountInfo', array(
		    'title'=>'账号信息查询','gameId'=>$gameId,'info'=>$info,'type'=>$type,
            'game'=>$this->getGame(),'gameServer' =>$this->getServer(),
        ));
	}
	//日志查看
	public function actionLog(){
		$logName = $info = '';
		$logList = array(
			''					        =>'请选择日志...',
			'add_good_buchang_view_'	=>'多服补偿',
			'customer_command_view_'	=>'客服命令',
			'logRecord_log_'			=>'异常日志',
            'card_err_'                 =>'游服充值未到账日志',
        );
		if(!empty($_POST)){
			$time =  $_POST['date'];
			$logName = trim($_POST['logName']);
			if(file_exists(ROOT_PATH.'log/'.date('ym', strtotime($time))."/$logName".date('ymd', strtotime($time)).'.txt')) {
				$fullFileName = ROOT_PATH.'log/'.date('ym', strtotime($time))."/$logName".date('ymd',strtotime($time)).'.txt';
				if($logName == 'logRecord_log_'){
					//异常日志下载
					header("Content-Type:application/force-download");
					header("Content-Disposition:attachment;filename=".basename($fullFileName));
					readfile($fullFileName);
					return ;
				} else {
					$handle = @fopen($fullFileName, 'r');
					while (!@feof($handle)) {
						$buffer = @fgets($handle, 4096);
						if($buffer)
							$info .= $buffer.'<br>';
					}
					fclose($handle);
				}
			}
		}	
		$this->renderPartial('log',  array('title'=>'日志查看', 'logName'=>$logName, 'logList' => $logList, 'info'=>$info));
	}
	//角色信息
	public function actionPlayerInfo(){
		$typeList = array('角色名'/*, '角色ID', '账号名', '账号ID'*/);
		$type = 0;
		$info = array();
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
		$gameServer = GameServer::model()->getInfo();
		if(!empty($_POST)){
			$type = intval($_POST['type']);
			//$serverId = intval($_POST['serverid']);
			$serverId = 0;
			$name = trim($_POST['name']);
			/*if(!$serverId)
				$this->display('请选择区服...', 0);*/
			if(!$name)
				$this->display('请输入查询的角色！', 0);
		
			if($type == 2 || $type == 3){
				$gameId = $_POST['gameid'];
				global $accountServer;
				$accountConn = $accountServer[$gameId];
				$conn = SetConn($accountConn);
				
				$where = ($type ==2) ? " NAME='$name'" : " id='$name'";
				$sql = "select id from account where $where limit 1";
				$query2 = @mysqli_query($conn,$sql);
				$result = @mysqli_fetch_array($query2);
				@mysqli_close($conn);
				if(!$result)
					$this->display('账号不存在！', 0);
				
				$accountId = intval($result['id']);
				
			} else if($type == 0 || $type == 1) {
				$result = $this->selectByName($name);
				if(!$result)
					$this->display('角色不存在！', 0);
				$accountId = intval($result['account_id']);
			}
			//获得角色表
			$conn = SetConn($serverId);
			$playerTable = subTable($accountId, 'u_player', 200);
			$sql1 = "select id,name,account_id,serverid, level,money,emoney,vip_lev,vip_exp from $playerTable where account_id='$accountId'  limit 1";
			$query1 = @mysqli_query($conn,$sql1);
			$rs1 = @mysqli_fetch_assoc($query1);
			if($rs1){
				$info['id'] = $rs1['id'];
				$info['name'] = $rs1['name'];
				$info['account_id'] = $rs1['account_id'];
				$info['serverid'] = $rs1['serverid'];
				$info['level'] = $rs1['level'];
				$info['money'] = $rs1['money'];
				$info['emoney'] = $rs1['emoney'];
				$info['vip_level'] = $rs1['vip_lev'];
				$info['user_recharge_num'] = $rs1['vip_exp'];
			}
		}
		
		$this->renderPartial('playerInfo', array(
		    'title'=>'角色信息查询','gameId'=>$gameId,'info'=>$info,'type'=>$type,'typeList'=>$typeList,
            'game'=>$this->getGame(),'gameServer'=>$this->getServer(),
        ));
	}
	//游戏公告
	protected function bulletin($serverId, $type, $banTime, $message, $bulletinEndtime, $index_id){
		$conn = SetConn($serverId);
		if($conn == false )
			return false;
		$table = $this->gmtoolTable;
		$sql = "insert into $table(index_id,type, serverid, param, message, award_type1) values('$index_id','$type', '$serverId', '$banTime', '$message', '$bulletinEndtime')";
        if(false == mysqli_query($conn,$sql))
			return false;
        return true;
	}
	public function actionBulletin(){
		$typeList = array(6=>'游戏公告');
        $indexId = IndexId::model()->getIndexId();
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		if(!empty($_POST)){
			$gameId = $_POST['gameid'];
			$serverId = $_POST['serverId'];		
			$banTime = $_POST['banTime'];
            $index_id = $_POST['index_id'];
			$bulletinEndtime = $_POST['bulletinEndtime'];
			if(!$gameId)
				$this->display('请选择游戏ID', 0);
			/*if(empty($serverId) || !is_array($serverId))
				$this->display('请选择区服', 0);*/
			if($banTime>60)
				$this->error('循环分钟数不能超过60！');
			
			$type = intval($_POST['type']);
			$message = trim($_POST['message']);
			$bulletinEndtime = strtotime($bulletinEndtime);
			foreach ($serverId as $v){
				$rs = $this->bulletin($v, $type, $banTime, $message, $bulletinEndtime, $index_id);
                if($rs === false)
                    echo "<script>alert('$v 滚动消息发送失败.')</script>";
			}
			$this->display('公告发送成功！', 1);
		}
		$this->renderPartial('bulletin', array('title' =>'游戏公告', 'game' =>$game, 'typeList'=>$typeList, 'indexId'=>$indexId));
	}
	public function actionBulletinCancel(){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        if(!empty($_POST)){
            $serverId = $_POST['serverId'];
            $indexId = $_POST['indexId'];
            //if(!$indexId)
            //    $this->display('请填写标识', 0);
            /*if(empty($serverId) || !is_array($serverId))
                $this->display('请选择区服', 0);*/

            foreach ($serverId as $v){
                $conn = SetConn($v);
                if($conn == false)
                    continue;
                $table = $this->gmtoolTable;
                $sql = "update $table set status=1 where type=6 and index_id='$indexId'";
                if(false == mysqli_query($conn,$sql)){
                    echo "<script>alert('$v 公告禁用失败.')</script>";
                }
            }
            $this->display('公告发送成功！', 1);
        }
        $this->renderPartial('bulletinCancel', array('title' => '游戏公告', 'game' => $game));
    }


	public function actionBulletinLog(){
        $gameId = Yii::app()->request->getParam('gameid');
        $gameId = isset($gameId) ? $gameId : $this->mangerInfo['game_id'];
        $serverId =Yii::app()->request->getParam('serverid');
        $type = Yii::app()->request->getParam('type');
        $typeList = array(6=>'游戏公告');
        $info = array();
        $count = 0;
        if(isset($_POST['pagination'])){
            $pagination=$_POST['pagination'];
            $offset=($_POST['pagination']-1)*20;
        }else {
            $pagination=1;
            $offset=0;
        }
        if($serverId >= 0 && !empty($type)){
            $conn = SetConn($serverId);
            $where = 'status=0 and serverid='.$serverId;
            $table = $this->gmtoolTable;
            $where .= $type ? " and type='$type'" : '';
            $sqlCount = "select * from $table where $where";
            $queryCount = @mysqli_query($conn,$sqlCount);
            $count = @mysqli_num_rows($queryCount);
            $sql = "select * from $table where $where order by id desc limit 20 offset $offset";
            $query = @mysqli_query($conn,$sql);
            $i = 0;
            while (@$rows = mysqli_fetch_assoc($query)){
                $info[$i]['id'] = $rows['id'];
                $info[$i]['index_id'] = $rows['index_id'];
                $info[$i]['type'] = $rows['type'];
                $info[$i]['serverid'] = $rows['serverid'];
                $info[$i]['award_type1'] = $rows['award_type1'];
                $info[$i]['param'] = $rows['param'];
                $info[$i]['message'] = $rows['message'];
                $info[$i]['status'] = $rows['status'];
                $i++;
            }
        }



        $this->renderPartial('bulletinLog', array(
            'info'=>$info,'pages'=>20,'count'=>$count,'pagination'=>$pagination, 'type'=>$type,
            'title'=>'客服命令日志', 'game' => $this->getGame(), 'gameServer' => $this->getServer(), 'typeList'=>$typeList,
            'gameId'=>$gameId, 'serverId'=>$serverId,
        ));
    }


	//角色封解Log
	public function actionPlayerLimitLog(){
		$model = new PlayerLimit;
		$criteria = new CDbCriteria;

        if($this->mangerInfo['server_id']) {
            $username = $this->mangerInfo['login_name'];
            $criteria->condition = "server_id like '{$this->mangerInfo['server_id']}%' and operator='$username'";
        }
        if(isset($_POST['playerId']) && !empty($_POST['playerId'])){
			$player = $_POST['playerId'];
            if(isset($criteria->condition))
			    $criteria->condition .= " and (player_id='$player' or player_name='$player')";
            else
                $criteria->condition = "player_id='$player' or player_name='$player'";
		}
			
		$count = $model->count($criteria); //统计
		$pages=new CPagination($count);
		$pages->pageSize=20;
		$pages->applyLimit($criteria);
			
		$criteria->limit=$pages->pageSize;
		
		if(isset($_POST['pagination'])){
			$pagination=$_POST['pagination'];
			$criteria->offset=($_POST['pagination']-1)*$criteria->limit;
		}else {
			$pagination=1;
			$criteria->offset=0;
		}
		//排序
		$criteria->order="id desc";
		
		$info=$model->findAll($criteria);
		//print_r(CJSON::encode($info));
		$this->renderPartial('playerLimitLog', array('info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination));
	}	
	
	public function actionPlayerLimit(){
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		$gameServer = GameServer::model()->getInfo();
		$info = '';
		if(!empty($_POST)){
			$type = intval($_POST['type']);
			$name = $_POST['name'];
			$operate = intval($_POST['operate']);
			$reason = trim($_POST['reason']);
			$gameId = $_POST['gameid'];
			$endtime = $_POST['endtime'] ? strtotime($_POST['endtime']) : 0;
			if(!$gameId)
				$this->display('请选择游戏ID', 0);
			/*if(empty($_POST['serverId']) || !is_array($_POST['serverId']))
				$this->display('请选择区服', 0);*/
			if(count($_POST['serverId']) > 1)
				$this->display('只能选择一个区服！', 0);
			$serverId = $_POST['serverId'][0];
			if(!$name)
				$this->display('请填写角色或者角色ID', 0);
			if($reason == '')
				$this->display('请填写封号/解号原因', 0);
			
			$info = $this->selectByName($name);
			if($info == false)
				$this->display('角色或角色ID有误！', 0);
			
			$playerId = $info['id'];
			$playerName = $info['name'];
			
			$adminName = Yii::app()->user->name;
			$date = date('Y-m-d H:i:s');
			$sql = "insert into {{player_limit}} (server_id, operator, endtime, addtime, reason, player_id, player_name, type)";
			$sql .=" values('$serverId', '$adminName', '$endtime', '$date', '$reason','$playerId', '$playerName', '$operate')";
			$connection = Yii::app()->db;
			$command = $connection->createCommand($sql);
			$command->execute();
            $insertId = Yii::app()->db->getLastInsertID();
			//写入下架标识
			if($operate == 1){
				//游服变跟游戏accountId改角色Id
				$status = $this->gamePlayerLimit($playerId, $serverId, $endtime);
                if($status == false)
                    PlayerLimit::model()->updateByPk($insertId,array('status'=>1));
			} else {
				//让记录失效
				if($this->gamePlayerLimit($playerId, $serverId, $endtime,5) == false)
					$this->display('执行失败，请重试！', 0);
			}
				$this->display('操作成功！', 1);
			}
		$this->renderPartial('playerLimit', array('title' => '角色封解', 'game' => $game, 'gameServer' => $gameServer, 'info' => $info));
	}
	
	private function updatePlayer($accountId, $serverId, $delFlag=1){
		$rs = false;
		$conn = SetConn($serverId);
        $table = subTable($serverId, 'u_accountlimit', '1000');
		$sql = "update $table set del_flag='$delFlag' where account_id='$accountId' and server_id='$serverId'";
		if(mysqli_query($conn,$sql) != false)
			$rs = true;
		@mysqli_close($conn);
		return $rs;
	}
	
	private function gamePlayerLimit($playerId, $serverId, $endtime, $type = 4){
		$conn = SetConn($serverId);//根据SvrID连接服务器
        if($conn == false)
            return false;
        $table = $this->gmtoolTable;
        $sql = "insert into $table(type, serverid, param, award_type1) values('$type', '$serverId', '$playerId', '$endtime')";
        $tag = ($type == 4) ? "玩家封号:$playerId 区服:$serverId" : "玩家解封:$playerId 区服:$serverId";
        write_log(ROOT_PATH.'log', 'player_limit_log_', "$tag ".date('Y-m-d H:i:s')."\r\n");
        $queryR = @mysqli_query($conn,$sql);
        @mysqli_close($conn);
        if($queryR){
        	return true;
        }
        return false;
	}
	//重新加载定制掉落表
	public function actionReloadDrop(){
		$typeList = array(100=>'重新加载定制掉落表');
		$game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
		//$sql = "insert into $this->gmtoolTable(type, serverid, param, message) values('$type', '$serverid', '$banTime', '$message')";
		if(!empty($_POST)){
			$gameId = $_POST['gameid'];
			$serverId = $_POST['serverId'];
			
			if(!$gameId)
				$this->display('请选择游戏ID', 0);
			/*if(empty($serverId) || !is_array($serverId))
				$this->display('请选择区服', 0);*/
				
			$type = intval($_POST['type']);
			
			foreach ($serverId as $v){
				$this->reloadDrop($v, $type);
			}
			$this->display('重新加载掉落设置成功！', 1);
		}
		$this->renderPartial('reloadDrop', array('title' => '游戏公告', 'game' => $game, 'typeList'=>$typeList));
	}
	private  function reloadDrop($serverId, $type = 100){
		$conn = SetConn($serverId);
		$sql = "INSERT INTO `$this->gmtoolTable` (`id`, `type`, `serverid`) VALUES (0, $type, $serverId);";
		if(mysqli_query($conn,$sql) == False)
			write_log(ROOT_PATH."log","reload_drop_error_", "serverId=$serverId, sql=$sql ".date("Y-m-d H:i:s")."\r\n".mysqli_error($conn));
		else
			write_log(ROOT_PATH."log","reload_drop_log_", "serverId=$serverId, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		@mysqli_close($conn);
	}
    //账号转换
    public function actionAccountChange(){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameId = isset($_POST['gameId']) ? intval($_POST['gameId']) : $this->mangerInfo['game_id'];
        $oldAccount = isset($_POST['oldAccount']) ? trim($_POST['oldAccount']) : '';
        $newAccount = isset($_POST['newAccount']) ? trim($_POST['newAccount']) : '';
        $accountInfo = array();
        if(!empty($oldAccount) && !empty($newAccount)){
            global $accountServer;
            $accountConn = $accountServer[$gameId];
            $conn = SetConn($accountConn);
            if($conn == false)
                $this->display('account connect errer.', 0);
            $oldSql = "select id,NAME,channel_account from account where NAME='$oldAccount' limit 1;";
            $oldQuery = @mysqli_query($conn, $oldSql);
            $oldRs = @mysqli_fetch_assoc($oldQuery);
            $accountInfo[] = array('type'=>'停用','NAME'=>$oldRs['NAME'], 'id'=>$oldRs['id'], 'channel_account'=>$oldRs['channel_account']);
            $newSql = "select id,NAME,channel_account,dwFenBaoID from account where NAME='$newAccount' limit 1;";
            $newQuery = @mysqli_query($conn, $newSql);
            $newRs = @mysqli_fetch_assoc($newQuery);
            $accountInfo[] = array('type'=>'启用','NAME'=>$newRs['NAME'], 'id'=>$newRs['id'], 'channel_account'=>$newRs['channel_account']);

            if(isset($_POST['action']) && $_POST['action'] == 'change' ) {
                $oldId = intval($_POST['oldId']);
                $newId = intval($_POST['newId']);

                if($oldId && $newId){
                    $oldName = $oldRs['NAME'];
                    $oldChannlAccount = $oldRs['channel_account'];
                    $newName = $newRs['NAME'];
                    $newChannlAccount = $newRs['channel_account'];
                    $newDwFenBaoID = $newRs['dwFenBaoID'];
                    //启用的账号被停用
                    $sql1 = " update account set  name='$oldName@tingyong',channel_account='$oldChannlAccount@tingyong' where id ='$newId';";
                    //停用的账号被启用
                    $sql2 = " update account set  name='$newName',channel_account='$newChannlAccount',dwFenBaoID='$newDwFenBaoID'  where id ='$oldId';";

                    $adminName = Yii::app()->user->name;
                    if(mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)){
                        write_log(ROOT_PATH."log","account_change_log_", "result=success,$sql1=$sql1, sql2=$sql2,$adminName ".date("Y-m-d H:i:s")."\r\n");
                        $this->display('账号转换成功.', 1);
                    }
                    write_log(ROOT_PATH."log","account_change_log_", "result=fail,sql=$sql1, sql2=$sql2,$adminName ".date("Y-m-d H:i:s")."\r\n");
                    $this->display('账号转换失败', 0);
                }else{
                    $this->display('账号转换失败', 0);
                }
            }
        }

        $this->renderPartial('accountChange', array('accountInfo'=>$accountInfo, 'game' =>$game, 'gameId'=>$gameId, 'oldAccount'=>$oldAccount, 'newAccount'=>$newAccount ));
    }
    private function  giQSAccountHash( $string )
    {
    	$length = strlen($string);
    	$result = 0;
    	for($i=0;$i<$length;$i++){
    		
//     		echo $result.'*397+'.ord($string[$i]).'=';
    		$result = bcadd(bcmul($result,397), ord($string[$i]));
//     		$result = sprintf('%u',$result*397)+ sprintf('%u',ord($string[$i]));
//     		$result = $result*397+ ord($string[$i]);
//     		$result = sprintf('%u',(bindec(substr(decbin($result),-64))));
//     		echo $result.PHP_EOL;
    	}
    	
    	return $result;
    }
    
    protected function selectByName($name){
    	$nameacc = $this->giQSAccountHash( $name );
    	$table = subTable($nameacc, 'u_playername', 200);
    	$sql = "select user_id as id,name,account_id from $table where name='$name' limit 1";
//     	echo $sql;die;
    	$conn = SetConn(99);
    	$query = @mysqli_query($conn,$sql);
    	$rows = @mysqli_fetch_assoc($query);
    	$rs = array();
    	if($rows)
    		$rs = $rows;
    	@mysqli_close($conn);
    	return $rs;
    }
    protected function checkPlayer($player, $type, $serverId){
    	return $this->selectByName($player);
    }
}