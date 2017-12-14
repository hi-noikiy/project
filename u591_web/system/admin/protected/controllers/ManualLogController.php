<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午10:08
 */
class ManualLogController extends CommonController{
    public function _condition(&$condition){
        //审核权限
        $typeArr = array('账号','角色名');
        $statusArr = $this->getStatusArr();
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $condition=array();
        if($this->mangerInfo['level'] == 0){
            $condition[] = "operator='{$this->mangerInfo['login_name']}'";
        } else {
            $condition[] = 'verify_level='.$this->mangerInfo['level']-1;
        }

        $condition[] = "status='$status'";
        $condition['param'] = array(
            'dwInfo'=>$this->getFenbao(),'status'=>$status,'statusArr'=>$statusArr,'typeArr'=>$typeArr,
            'gameInfo'=>$this->getGame(), 'gameServer'=>$this->getServer(),'emp'=>$this->getEmp()
            );

    }

    private function getEmp(){
    	$result = EmpAccount::model()->findAll();
    	$arr = array();
    	foreach ($result as $v){
			$arr[$v->accountid]=$v->name;
		}
    	return $arr;
    }
    //退回status -1
    public function actionRepass($id){
        $manualLogModel = ManualLog::model();
        $rs = $manualLogModel->findByPk($id);
        if(!$rs)
            $this->display('数据不存在.', 0);

        if($rs->status != 0)
            $this->display('已作废或已结束.', 0);
        /*if($this->mangerInfo['level'] == 0)
            $this->display('流程等级为0. 无权执行.', 0);*/

        $count = $manualLogModel->updateByPk($id, array('status'=>'-1', 'verify_level'=>0));
        if($count > 0 ) {
            $erpLogModel = new ErpLog;
            $erpLogModel->saveData($this->getId(), '退回', $rs->id);
            $this->display('退回成功.', 1);
        }
        $this->display('退回失败.', 0);
    }

    public function actionPass($id){
        $erpLogModel = new ErpLog;
        $erpLevel = new ErpLevel;
        $manualLogModel = ManualLog::model();

        $rs = $manualLogModel->findByPk($id);
        $maxLevel = $erpLevel->getMaxLevel();
        if(!$erpLevel)
            $this->display('获取流程等级出错.', 0);
        $msg = '';
        if($maxLevel == $this->mangerInfo['level']){
        	$serverId = $rs->server_id;
        	$sid = togetherServer($serverId);
        	$table = subTable($sid, 'u_card', 1000);
        	$conn = SetConn($sid);
        	if($conn == false)
        		$this->display('链接游服数据库失败.', 0);
            //流程已经到最后一步了，执行sql
            $accountId = $rs->account_id;
            $accountName = $rs->account_name;
            
            $emoney = $rs->emoney;
            $orderId = $rs->order_id;
            $gameId = $rs->game_id;
            $payCode = $rs->payCode;
            $addTime = date('Y-m-d H:i:s');
            $dwFenBaoID = $rs->dwFenBaoID;
            if(!in_array($rs->payCode, array('USD', 'TWD','VND','RUB','CNY'))){
            	$this->display('金额单位错误', 1);
            	die;
            }
            $sql = "insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,rpTime,game_id, rpCode)";
            $sql .=" VALUES ('9', '$payCode', '$accountId', '$accountName', '$serverId', '$emoney', '$orderId','$dwFenBaoID','$addTime', '$addTime', '$gameId', '1')";

            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            if($command->execute()){
                if(in_array($rs->payCode, array('USD', 'TWD','VND','RUB'))){
                    if($rs->payCode == 'TWD') //台湾币
                        $emoney = $rs->emoney*2;
                    elseif($rs->payCode == 'USD') //美元
                        $emoney = round($rs->emoney)*60;
                    elseif($rs->payCode == 'VND') //越南盾
                    	$emoney = round($rs->emoney/250);
                    elseif($rs->payCode == 'RUB'){
                    	$parr = [75=>80, 379=>390, 1490=>1690, 3790=>4290, 7490=>8500, 299=>200];
                    	$emoney = $parr[$rs->emoney];
                    }
                    	
                }
                $this->writeCard($orderId,$accountId, $serverId, $emoney);
                $manualLogModel->updateByPk($id, array('status'=>2));
                $erpLogModel->saveData($this->getId(), '流程结束', $rs->id);

                $msg .= "流程结束,审核通过.";
            } else
                $msg .='web insert order error.';
        } else {
            $msg .= "审核通过.";
            $manualLogModel->updateByPk($id, array('verify_level'=>$rs->verify_level+1));
            $erpLogModel->saveData($this->getId(), '通过', $rs->id);
        }
        $this->display($msg, 1);
    }

    //一健审核
    public function actionOneKeyPass(){
    	$erpLogModel = new ErpLog;
    	$manualLogModel = ManualLog::model();
    	$erpLevel = new ErpLevel;
    	$info = $manualLogModel->getInfo();
    	if(!$info)
    		$this->display('没有要审核的记录.', 0);
    	$maxLevel = $erpLevel->getMaxLevel();
    	if($maxLevel != $this->mangerInfo['level'])
    		$this->display('你没有该权限.', 0);
    	$msg = '';
    	foreach ($info as $rs){
    		$serverId = $rs->server_id;
    		$sid = togetherServer($serverId);
    		$conn = SetConn($sid);
    		if($conn == false)
    			$this->display('链接游服数据库失败.', 0);
    		$id = $rs->id;
    		$accountId = $rs->account_id;
            $accountName = $rs->account_name;
            $emoney = $rs->emoney;
            $orderId = $rs->order_id;
            $gameId = $rs->game_id;
            $payCode = $rs->payCode;
            $addTime = date('Y-m-d H:i:s');
            $dwFenBaoID = $rs->dwFenBaoID;
            if(!in_array($rs->payCode, array('USD', 'TWD','VND','RUB','CNY'))){
            	$this->display('金额单位错误', 1);
            	die;
            }
            $sql = "insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,rpTime,game_id, rpCode)";
            $sql .=" VALUES ('9', '$payCode', '$accountId', '$accountName', '$serverId', '$emoney', '$orderId','$dwFenBaoID','$addTime', '$addTime', '$gameId', '1')";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            if($command->execute()){
            	if(in_array($rs->payCode, array('USD', 'TWD','VND','RUB'))){
            		if($rs->payCode == 'TWD') //台湾币
            			$emoney = $rs->emoney*2;
            		elseif($rs->payCode == 'USD') //美元
            			$emoney = round($rs->emoney)*60;
            		elseif($rs->payCode == 'VND') //越南盾
                    	$emoney = round($rs->emoney/250);
            		elseif($rs->payCode == 'RUB'){
                    	$parr = [75=>80, 379=>390, 1490=>1690, 3790=>4290, 7490=>8500, 299=>200];
                    	$emoney = $parr[$rs->emoney];
                    }
            	}
            	$this->writeCard($orderId,$accountId, $serverId, $emoney);
            	$manualLogModel->updateByPk($id, array('status'=>2));
            	$erpLogModel->saveData($this->getId(), '流程结束', $rs->id);
            
            	$msg .= "流程结束,审核通过.";
            } else
            	$msg .='web insert order error.';
    	}
    	$this->display('一键审核通过.',1);
    }
    public function actionAdd(){
        $playerList = $accountInfo = array();
        $type = isset($_POST['type']) ? intval($_POST['type']) : 0;
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        $serverId = isset($_POST['serverid']) ? intval($_POST['serverid']) : 0;
        $username =  isset($_POST['username']) ? trim($_POST['username']) : '';
        if(!empty($_POST)){
            if(empty($gameId))
                $this->display('游戏ID不能为空！', 0);
            if(empty($serverId))
                $this->display('服务器ID不能为空！', 0);
            if(empty($username))
                $this->display('账号或角色名任填一个！', 0);

            if($type == 0){
                $accountInfo = $this->checkAccount($username, false, $gameId);
            } else {
                $palyerInfo = $this->checkPlayer($username, 0 , $serverId);
                if($palyerInfo == false)
                    $this->display('角色不存在！', 0);
                $accountInfo = $this->checkAccount($palyerInfo['account_id'], true, $gameId);
            }
            if($accountInfo == false)
                $this->display('账号不存在！', 0);

            $accountId = $accountInfo['id'];
            $accountName = $accountInfo['NAME'];
            $dwFenBaoID = $accountInfo['dwFenBaoID'];

            if(isset($_POST['action']) && $_POST['action'] == 'pay' ) {
                $emoney = $_POST['emoney'];
                //if(!is_numeric($emoney) || strpos($emoney,".") !== false)
                //    $this->display('金额格式错误,请用整数', 0);
                $orderId = (isset($_POST['order_id']) && !empty($_POST['order_id'])) ? $_POST['order_id'] : date("ymdHis").floor(microtime()*1000).rand(10000,99999);

                $gameId = intval($_POST['gameid']);
                if(isset($_POST['dwFenBaoID']) && !empty($_POST['dwFenBaoID']))
                    $dwFenBaoID = $_POST['dwFenBaoID']; //页面有传就取页面的

                $manualLogModel = new ManualLog;
                $manualLogModel->server_id = $serverId;
                $manualLogModel->game_id = $gameId;
                $manualLogModel->type = $type;
                $manualLogModel->name = $username;
                $manualLogModel->order_id = $orderId;
                $manualLogModel->dwFenBaoID = $dwFenBaoID;
                $manualLogModel->emoney = $emoney;
                $manualLogModel->account_id = $accountId;
                $manualLogModel->account_name = $accountName;
                $manualLogModel->payCode = $_POST['payCode'];
                $manualLogModel->verify_level = $this->mangerInfo['level'];;
                $manualLogModel->remark = $_POST['remark'];
                if($manualLogModel->save())
                    $this->display('手工充值提交成功.', 1, $this->createUrl('manualLog/index'));
                $this->display('手工充值提交失败.'.CHtml::errorSummary($manualLogModel), 0, $this->createUrl('manualLog/index'));

            } else {
                /**
                 * 由于u_player合服并未合表
                 * 所以表还是原来的表
                 */
                //$sid = togetherServer($serverId);
                $sid = $serverId;
                $conn = SetConn($sid);
                $table = subTable($serverId, 'u_player', 1000);
                $sql = "select id,name,serverid from $table where serverid='$serverId' and account_id='$accountId'";
                $query = @mysqli_query($conn,$sql);
                $playerList = @mysqli_fetch_array($query);
                @mysqli_close($conn);
            }
        }

        $this->renderPartial('add', array(
            'game'=>$this->getGame(),'gameServer'=>$this->getServer(),'playerList' => $playerList,'accountInfo' => $accountInfo,
            'gameId' => $gameId,'username' => $username,'serverId' => $serverId,'type' => $type,'dwInfo' => $this->getFenbao(),
        ));
    }

    public function actionUpdate($id){
        $playerList = array();
        $accountInfo = array();
        $rs = ManualLog::model()->findByPk($id);
        if($rs->status >0)
            $this->display('流程进行中... 不能编辑.', 0);
        $type = isset($_POST['type']) ? intval($_POST['type']) : $rs->type;
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $rs->game_id;
        $serverId = isset($_POST['serverid']) ? intval($_POST['serverid']) : $rs->server_id;
        $username =  isset($_POST['username']) ? trim($_POST['username']) : $rs->name;
        if($type == 0){
            $accountInfo = $this->checkAccount($username, false, $gameId);
        } else {
            $palyerInfo = $this->checkPlayer($username, 0 , $serverId);
            if($palyerInfo == false)
                $this->display('角色不存在！', 0);
            $accountInfo = $this->checkAccount($palyerInfo['account_id'], true, $gameId);
        }
        if($accountInfo == false)
            $this->display('账号不存在！', 0);

        $conn = SetConn($serverId);
        $table = subTable($serverId, 'u_player', 1000);
        $sql = "select id,name,serverid from $table where serverid='$serverId' and account_id='{$accountInfo['id']}'";
        $query = @mysqli_query($conn,$sql);
        $playerList = @mysqli_fetch_array($query);
        @mysqli_close($conn);

        if(!empty($_POST)){
            if(empty($gameId))
                $this->display('游戏ID不能为空！', 0);
            if(empty($serverId))
                $this->display('服务器ID不能为空！', 0);
            if(empty($username))
                $this->display('账号或角色名任填一个！', 0);
            $accountId = $accountInfo['id'];
            $accountName = $accountInfo['NAME'];
            $dwFenBaoID = $accountInfo['dwFenBaoID'];
            if(isset($_POST['action']) && $_POST['action'] == 'pay' ) {
                $emoney = $_POST['emoney'];
                $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : date("ymdHis").floor(microtime()*1000).rand(10000,99999);
                $gameId = intval($_POST['gameid']);
                if(isset($_POST['dwFenBaoID']) && !empty($_POST['dwFenBaoID']))
                    $dwFenBaoID = $_POST['dwFenBaoID']; //页面有传就取页面的

                $rs->server_id = $serverId;
                $rs->game_id = $gameId;
                $rs->type = $type;
                $rs->name = $username;
                $rs->order_id = $orderId;
                $rs->dwFenBaoID = $dwFenBaoID;
                $rs->emoney = $emoney;
                $rs->account_id = $accountId;
                $rs->account_name = $accountName;
                $rs->payCode = $_POST['payCode'];
                $rs->verify_level = $this->mangerInfo['level'];
                $rs->status = 0;
                $rs->remark = $_POST['remark'];
                if($rs->save())
                    $this->display('手工充值提交成功.', 1, $this->createUrl('manualLog/index'));
                $this->display('手工充值提交失败.', 0, $this->createUrl('manualLog/index'));

            }
        }


        $this->renderPartial('update', array(
            'model'=>$rs,'game'=>$this->getGame(),'gameServer'=>$this->getServer(),'playerList' => $playerList,'accountInfo' => $accountInfo,
            'gameId' => $gameId,'username' => $username,'serverId' => $serverId,'type' => $type,'dwInfo' => $this->getFenbao(),
        ));
    }

    private function writeCard($orderId, $accountId, $serverId, $payMoney, $type = 8){
        /**
         * 由于u_card合服并表
         * 所以表还是原来的表
         * 入库的表还是$serverid
         * 连接库的的区服为$sid
         */
        $sid = togetherServer($serverId);
        $conn = SetConn($sid);
        $table = subTable($sid, 'u_card', 1000);
        $sql="select count(id) as count from $table where ref_id='$orderId' limit 1";
        $query = @mysqli_query($conn,$sql);
        $count = @mysqli_fetch_assoc($query);
        $msg = null;
        if ($count['count'] == 0) {
            $time_stamp=date('ymdHi');
            $sql_insert="insert into $table(data, account_id, ref_id, time_stamp, used, type, server_id)";
            $sql_insert=$sql_insert." values('$payMoney', $accountId, '$orderId',$time_stamp, 0, '$type', '$serverId')";
            $msg = @mysqli_query($conn,$sql_insert) ? $orderId : false;
            if($msg){
            	write_log(ROOT_PATH.'log', 'add_order_success_', "sql=$sql_insert".date('Y-m-d H:i:s')."\r\n");
            }else{
            	write_log(ROOT_PATH.'log', 'add_order_fail_', mysqli_error($conn).",sql=$sql_insert".date('Y-m-d H:i:s')."\r\n");
            }
        }
        @mysqli_close($conn);
        return $msg;
    }

}