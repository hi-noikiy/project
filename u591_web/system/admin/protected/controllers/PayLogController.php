<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 充值管理
* ==============================================
* @date: 2016-4-29
* @author: luoxue
* @version:
*/
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class PayLogController extends Controller{

	public function _condition(&$condition){
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
		$channelId = isset($_POST['channel'])? $_POST['channel']:0;// $this->mangerInfo['channel_id'];

        $condition=array();
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date('Y-m-d').' 00:00:00';
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date('Y-m-d').' 23:59:59';
		$condition[] = "Add_Time >='$startTime' and Add_Time<='$endTime'";
        if(!empty($channelId)){
        	$condition[]="CPID = '$channelId'";
        }elseif($this->mangerInfo['channel_id']){
        	$condition[]="CPID in ({$this->mangerInfo['channel_id']})";
        }
			
        if(!empty($this->mangerInfo['dwFenbao']))
        	$condition[] =  "dwFenBaoID in ({$this->mangerInfo['dwFenbao']})";
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="PayName = '{$_POST['name']}'";
		if(isset($_POST['accountId']) && !empty($_POST['accountId']))
			$condition[]="PayID = '{$_POST['accountId']}'";
		if(isset($_POST['orderid']) && !empty($_POST['orderid']))
			$condition[]="OrderId = '{$_POST['orderid']}'";
		//如果账号游戏id不为0 查询该游戏ID的订单
		if(!empty($gameId))
			$condition[] =  "game_id = '$gameId'";
		
		if(isset($_POST['serverid']) && !empty($_POST['serverid']))
			$condition[]="ServerID = '{$_POST['serverid']}'";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="payCode = '{$_POST['payCode']}'";
		if(isset($_POST['rpCode']) && !empty($_POST['rpCode']))
			$condition[]="rpCode = '{$_POST['rpCode']}'";
		
		if(isset($_POST['gamefenbao']) && !empty($_POST['gamefenbao'])){
			if($_POST['gamefenbao'] == 1){
				$isnewgame  = 1;
			}else{
				$isnewgame  = 0;
			}
			$sql = "SELECT GROUP_CONCAT(fenbao_id) as fenbaoids FROM `web_dwFenbao` where isnewgame=$isnewgame;";
			$command = Yii::app()->db->createCommand($sql);
			$result = $command->queryAll();
			$condition[]=" dwFenBaoID in({$result[0]['fenbaoids']})";
		}
		
		if(isset($_POST['IsUC']) && !empty($_POST['IsUC']))
			$condition[]="IsUC = '{$_POST['IsUC']}'";
		
		if(isset($_POST['rpCode']) && !empty($_POST['rpCode']))
			$condition[]="rpCode = '{$_POST['rpCode']}'";
		
		if(isset($_POST['clientType']) && !empty($_POST['clientType']))
			$condition[]="clienttype = '{$_POST['clientType']}'";

		if(isset($_POST['sMoney']) && !empty($_POST['sMoney'])  &&  isset($_POST['eMoney']) && !empty($_POST['eMoney'])){
			$sMoeny = intval($_POST['sMoney']);
			$eMoney = intval($_POST['eMoney']);
			$condition[]="PayMoney >= '$sMoeny' and PayMoney <= '$eMoney'";
		}
        if(isset($_POST['dwFenBaoID']) && !empty($_POST['dwFenBaoID']))
            $condition[]="dwFenBaoID = '{$_POST['dwFenBaoID']}'";
		$totalPayMoney = 0;
		if(!empty($condition)){
			$condition1 = implode($condition,' and ');
			$sql = "select sum(PayMoney) as money from {{pay_log}} where $condition1";
			$command = Yii::app()->db->createCommand($sql);
			$result = $command->queryAll();
			$totalPayMoney = $result[0]['money'];
		}
		if(isset($_POST['dwFenBaoID']) && !empty($_POST['dwFenBaoID']))
			$condition[]="dwFenBaoID = '{$_POST['dwFenBaoID']}'";
		$condition['param'] = array(
		    'channel'=>$this->getChannel(), 'game'=>$this->getGame(), 'gameId'=>$gameId,
            'gameServer'=>$this->getServer(), 'startTime'=>$startTime, 'endTime'=>$endTime,
            'totalPayMoney'=>$totalPayMoney, 'channelId'=>$channelId,
        );

        $condition['excelLoad'] = array(
            'A'=>array('field'=>'game_id', 'name'=>'游戏'),
            'B'=>array('field'=>'ServerID', 'name'=>'区服'),
            'C'=>array('field'=>'PayName', 'name'=>'帐号'),
            'D'=>array('field'=>'PayCode', 'name'=>'货币类型'),
            'E'=>array('field'=>'OrderID', 'name'=>'订单号'),
            'F'=>array('field'=>'PayMoney', 'name'=>'金额'),
            'G'=>array('field'=>'Add_Time', 'name'=>'提交时间'),
        );
	}
	
	public function _field(&$field){
		$field = array('CPID, IsUC,PayCode, PayID, PayMoney, PayName, OrderID, CardNO, CardPwd, BankID, game_id, ServerID, rpCode, Add_Time,dwFenBaoID,SubStat');
	}
    /**
     * 一键补单
     */
    public function actionOneKeyPass(){
        $payLog = PayLog::model();
        $info = $payLog->getGameOrderFailInfo();
        if($info){
            foreach ($info as $v){
                $id = $v->id;
                $orderId = $v->OrderID;
                $serverId = $v->ServerID;
                $accountId = $v->PayID;
                if($v->PayCode == 'TWD') //台湾币
                    $payMoney = $v->PayMoney*2;
                elseif($v->PayCode == 'USD') //美元
                    $payMoney = round($v->PayMoney)*60;
                elseif($v->PayCode == 'VND') //越南盾
                	$payMoney = round($v->PayMoney/250);
                elseif($v->PayCode == 'RUB'){
                	$parr = [75=>80, 379=>390, 1490=>1690, 3790=>4290, 7490=>8500, 299=>200];
                	$payMoney = $parr[$v->PayMoney];
                }
                elseif($v->PayCode == 'CNY') 
                    $payMoney = $v->PayMoney;
                if(!$payMoney){
                	break;
                }
                $rsCard = $this->writeCard($orderId, $accountId, $serverId, $payMoney);
                if($rsCard === null){
                    $payLog->updateByPk($id, array('IsUC'=>'0'));
                } elseif ($rsCard === false){
                    //$this->display('游服连接错误或者缺失表.', 0);
                    continue;
                }else {
                    $payLog->updateByPk($id, array('IsUC'=>'0'));
                }
            }
            $this->display('补单成功!', 1);
        }
        $this->display('无需补单.', 0);
    }
	//游服订单状态查询
	public function actionGameOrderList(){
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
		$info = array();
		if(!empty($_POST['orderid'])){
			$serverId = intval($_POST['serverid']);
			$orderId = $_POST['orderid'];
			$conn = SetConn($serverId);
			$table = subTable($serverId, 'u_card', 1000);
			$sql = "select * from $table where ref_id='$orderId' limit 1";
			$query = @mysqli_query($conn,$sql);
			$info = @mysqli_fetch_assoc($query);
			@mysqli_close($conn);
		}
	
		$this->renderPartial('gameOrderList',  array('game'=>$this->getGame(), 'gameServer'=>$this->getServer(), 'gameId'=>$gameId,'info'=>$info));
	}
    //充值补单
    public function actionSupplement(){
        if(isset($_REQUEST['orderid'])){
            $orderId = trim($_REQUEST['orderid']);
            $payLog = PayLog::model();
            $sql = "select id,ServerID,PayType,PayID,OrderID,PayMoney,PayCode from {{pay_log}} where OrderID='$orderId' And rpCode in ('1','10')";
            $rs = $payLog->findBySql($sql);
            if(empty($rs))
                $this->display('订单号不存在', 0);

            if($rs->CPID == '9' || $rs->CPID == '8')
                $this->display('手工充值和移动梦网渠道无需补单', 0);

            $serverId = $rs->ServerID;
            $accountId = $rs->PayID;
            if($rs->PayCode == 'TWD') //台湾币
            	$payMoney = $rs->PayMoney*2;
            elseif($rs->PayCode == 'USD') //美元
            $payMoney = round($rs->PayMoney)*60;
            elseif($rs->PayCode == 'VND') //越南盾
            $payMoney = round($rs->PayMoney/250);
            elseif($rs->PayCode == 'RUB'){
            	$parr = [75=>80, 379=>390, 1490=>1690, 3790=>4290, 7490=>8500, 299=>200];
            	$payMoney = $parr[$rs->PayMoney];
            }
            elseif($rs->PayCode == 'CNY')
            	$payMoney = $rs->PayMoney;
            $payId = $rs->id;
            $rsCard = $this->writeCard($orderId, $accountId, $serverId, $payMoney);
            if($rsCard === null){
                $payLog->updateByPk($payId, array('IsUC'=>'0'));
                $this->display('您的订单已存在，无需处理！', 1);
            } else if($rsCard === false)
                $this->display('补单失败！', 0);
            write_log(ROOT_PATH.'log', 'admin_pay_supplement_log_', $sql.', '. date('Y-m-d H:i:s').'\r\n');
            $payLog->updateByPk($payId, array('IsUC'=>'0'));
            $this->display('补单成功!', 1);
        }
    }
    //批量补单
    private function writeCard($orderId, $accountId, $serverId, $payMoney, $type = 8){
    	$sid = togetherServer($serverId);
        $conn = SetConn($sid);
        if($conn == false)
            return false;
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
            if($msg == false)
                write_log (ROOT_PATH."log", "card_err_", "sql=$sql_insert, ".mysqli_error($conn)." ".date ("Y-m-d H:i:s")."\r\n");

        }
        @mysqli_close($conn);
        return $msg;
    }
	//充值统计
	public function actionStatistics(){
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        $channelId = isset($_POST['channel'])? $_POST['channel']:0;// $this->mangerInfo['channel_id'];
        $unityCurrency = isset($_POST['unityCurrency']) ? $_POST['unityCurrency'] : 'CNY';
        $condition=array();
		$condition[] = "rpCode in ('1','10')";
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date('Y-m-d 00:00:00', time()-7*86400);
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date('Y-m-d').' 23:59:59';
		$condition[] = "Add_Time between '$startTime' and '$endTime'";
		if(!empty($channelId)){
        	$condition[]="CPID = '$channelId'";
        }elseif($this->mangerInfo['channel_id']){
        	$condition[]="CPID in ({$this->mangerInfo['channel_id']})";
        }
        if(!empty($this->mangerInfo['dwFenbao']))
        	$condition[] =  "dwFenBaoID in ({$this->mangerInfo['dwFenbao']})";
		//如果账号游戏id不为0 查询该游戏ID的订单
		if(!empty($gameId))
			$condition[] =  "game_id = '$gameId'";
		
		if(isset($_POST['gamefenbao']) && !empty($_POST['gamefenbao'])){
			if($_POST['gamefenbao'] == 1){
				$isnewgame  = 1;
			}else{
				$isnewgame  = 0;
			}
			$sql = "SELECT GROUP_CONCAT(fenbao_id) as fenbaoids FROM `web_dwFenbao` where isnewgame=$isnewgame;";
			$command = Yii::app()->db->createCommand($sql);
			$result = $command->queryAll();
			$condition[]=" dwFenBaoID in({$result[0]['fenbaoids']})";
		}
		if(isset($_POST['serverid']) && !empty($_POST['serverid']))
			$condition[]="ServerID = '{$_POST['serverid']}'";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="PayCode = '{$_POST['payCode']}'";
		
		if(isset($_POST['clientType']) && !empty($_POST['clientType']))
			$condition[]="clienttype = '{$_POST['clientType']}'";
		
		if(isset($_POST['dwFenBaoID']) && !empty($_POST['dwFenBaoID']))
			$condition[]="dwFenBaoID = '{$_POST['dwFenBaoID']}'";

		if(isset($_POST['testServer']) && !empty($_POST['testServer']))
			$condition[]="9 = SUBSTR(ServerID,-3,1)";
		
        $model = PayLog::model();
		$criteria = new CDbCriteria;
		$condition=implode($condition,' and ');
		
		$criteria->condition =$condition;
		//print_r($condition);
		if($type == 1){
			$criteria->select = "CPID,sum(PayMoney) as payTotal,count(distinct PayID ) as payIdTotal,PayCode";
			$criteria->group = "CPID,PayCode";
			$criteria->order = 'payTotal desc';
		} else {
			$criteria->select = "sum(PayMoney) as payTotal,CAST(Add_Time AS date) as today,count(distinct PayID ) as payIdTotal,PayCode";
			$criteria->group = 'today,PayCode';
			$criteria->order = 'Add_Time desc';
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
		$info=$model->findAll($criteria);
        $newInfo = array();
        foreach ($info as $k => $v){
            if($type == 1) {
                $newInfo[$v->CPID]['payTotal'][] = $v->PayCode . '：' . $v->payTotal;
                $newInfo[$v->CPID]['payIdTotal'][] = $v->PayCode . '：' . $v->payIdTotal;
                $newInfo[$v->CPID]['total'][] = $this->exchangeToCurrency($v->PayCode, $unityCurrency, $v->payTotal);

            }else {
                $newInfo[$v->today]['payTotal'][] = $v->PayCode.'：'.$v->payTotal;
                $newInfo[$v->today]['payIdTotal'][] = $v->PayCode . '：' .$v->payIdTotal;
                $newInfo[$v->today]['total'][] = $this->exchangeToCurrency($v->PayCode, $unityCurrency, $v->payTotal);
            }
        }
        //总额
        $totalMoney = $this->toCNYMoney($model, $condition, $unityCurrency);
		$this->renderPartial('statistics', array(
				'info'=>$newInfo,'totalMoney'=>$totalMoney,'pages'=>$pages,'count'=>$count,
                'pagination'=>$pagination,'channel'=>$this->getChannel(),'game'=>$this->getGame(),
                'gameId'=>$gameId,'gameServer'=>$this->getServer(),'startTime'=>$startTime,
                'endTime' =>$endTime,'type' => $type,'channelId'=>$channelId,
		));
	}

	private function toCNYMoney($model, $condition, $unityCurrency){
        $condition = is_array($condition) ? implode($condition,' and ') : $condition;
        $sql = "select sum(PayMoney) as payTotal,PayCode from {{pay_log}} where $condition group by PayCode";
        $rs = $model->findAllBySql($sql);
        $money = 0;
        if($rs){
            foreach ($rs as $v){
                $money += $this->exchangeToCurrency($v->PayCode, $unityCurrency, $v->payTotal);
            }
        }
        return $unityCurrency.": ".sprintf("%.2f", $money);
    }

    private function exchangeToCurrency($currency, $toCurrency, $totalMoney){
        $info = Config::model()->getInfo();
        $rate = isset($info[$toCurrency]) ? $info[$toCurrency] : 1;
        //人民币兑美元汇率
        $USDRate = isset($info['USD']) ? $info['USD'] : 1;
        if($currency == $toCurrency){
            return $totalMoney;
        } else if($currency == 'CNY'){
            if($toCurrency == 'USD')
                return $totalMoney/$rate;
            else if($toCurrency == 'TWD')
                return $totalMoney*$rate;
        } else if($currency == 'USD') {
            if ($toCurrency == 'CNY') {
                return $totalMoney * $USDRate;
            } else if ($toCurrency == 'TWD') {
                //1、$totalMoney * $USDRate 转RMB
                //2、$rate 台币兑RMB汇率
                return $totalMoney * $USDRate * $rate;
            }
        }else if($currency == 'TWD'){
            $TWD_rate = $info['TWD'];
            if($toCurrency == 'CNY'){
                return $totalMoney/$TWD_rate;
            } elseif($toCurrency == 'USD') {
                //1、$totalMoney/$TWD_rate 转RMB
                //2、$rate 人民币兑美元汇率
                return ($totalMoney/$TWD_rate)/$rate;
            }
        }else if($currency == 'VND'){
        	$VND_rate = $info['VND'];
       		if ($toCurrency == 'CNY') {
                return $totalMoney * $VND_rate;
            } elseif($toCurrency == 'USD') {
                //1、$totalMoney/$TWD_rate 转RMB
                //2、$rate 人民币兑美元汇率
                return ($totalMoney*$VND_rate)/$rate;
            }
        }
        return '';
    }
	//玩家充值排行
	public function actionPlayerStatistics(){
		$condition=array();
		$condition[] = "rpCode in ('1','10')";
	    $gameId = isset($_POST['gameid']) ? $_POST['gameid'] : $this->mangerInfo['game_id'];
        $channelId = isset($_POST['channel'])? $_POST['channel']:0;// $this->mangerInfo['channel_id'];

		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date('Y-m-d 00:00:00', time()-7*86400);
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date('Y-m-d').' 23:59:59';
		$condition[] = "Add_Time >='$startTime' and Add_Time<='$endTime'";
		//如果账号游戏id不为0 查询该游戏ID的订单
		if(!empty($gameId))
			$condition[] =  "game_id = '$gameId'";
		if(!empty($channelId)){
        	$condition[]="CPID = '$channelId'";
        }elseif($this->mangerInfo['channel_id']){
        	$condition[]="CPID in ({$this->mangerInfo['channel_id']})";
        }
        if(!empty($this->mangerInfo['dwFenbao']))
        	$condition[] =  "dwFenBaoID in ({$this->mangerInfo['dwFenbao']})";
		if(isset($_POST['serverid']) && !empty($_POST['serverid']))
			$condition[]="ServerID = '{$_POST['serverid']}'";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="PayCode = '{$_POST['payCode']}'";

		$model = PayLog::model();
		$criteria = new CDbCriteria;
		$condition=implode($condition,' and ');
		$criteria->condition =$condition;
		$criteria->select = "PayID, sum(if(PayCode='USD',PayMoney*22725,PayMoney)) as payTotal, ServerID,'VND' as PayCode";
		$criteria->group = 'PayID, ServerID';
		$criteria->order = 'payTotal desc';
		
		$count = $model->count($criteria); //统计

        if(isset($_POST['excelLoad'])){
            $info=$model->findAll($criteria);
            $newInfo = array();
            $newInfo['tag'] = array('账号ID', '区服', '金额');
            foreach ($info as $k => $v){
                $newInfo['data'][$k]['A'] = $v->PayID;
                $newInfo['data'][$k]['B'] = $v->ServerID;
                $newInfo['data'][$k]['C'] = $v->payTotal;
            }
            $this->download($newInfo, '.xls');
        }
		
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
		$info=$model->findAll($criteria);
		foreach ($info as $k => $v){
			$newinfo[] = $this->exchangeToCurrency($v->PayCode, 'CNY', $v->payTotal);
		}
		$this->renderPartial('playerStatistics', array(
		        'info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination,'channelId'=>$channelId,
                'gameId'=>$gameId,'channel'=>$this->getChannel(),'game'=>$this->getGame(),
                'gameServer'=>$this->getServer(),'startTime'=>$startTime,'endTime' =>$endTime,'newinfo'=>$newinfo
            )
        );
	}
	
	//分包渠道统计
	public function actionFenbaoStatistics(){
		$fenbao = $this->getFenbao();
        $accessTableM = new AccessTable;

        $gameId = isset($_POST['gameid']) ? $_POST['gameid'] : $this->mangerInfo['game_id'];
        $unityCurrency = isset($_POST['unityCurrency']) ? $_POST['unityCurrency'] : 'CNY';

        $condition=array();
		$condition[] = "rpCode in ('1','10')";
		
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date('Y-m-d 00:00:00', time()-7*86400);
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date('Y-m-d').' 23:59:59';
		$condition[] = "Add_Time between '$startTime' and '$endTime'";
		//如果账号游戏id不为0 查询该游戏ID的订单
		if(!empty($gameId))
			$condition[] =  "game_id = '$gameId'";
        if(!empty($this->mangerInfo['dwFenbao']))
            $condition[] =  "dwFenBaoID in ({$this->mangerInfo['dwFenbao']})";
        else {

            if(!empty($_POST['channel']))
                $condition[] =  "dwFenBaoID = '{$_POST['channel']}'";
            else {
                if(!empty($_POST['name'])){
                    $fenbaoModel = Dwfenbao::model();
                    $fenbaoArr = $fenbaoModel->getLikeInfo($_POST['name']);
                    if(!empty($fenbaoArr)){
                        $fenbaoC = implode(',', $fenbaoArr);
                        $condition[] =  "dwFenBaoID in ($fenbaoC)";
                    }
                }
            }
        }

		if(isset($_POST['serverid']) && !empty($_POST['serverid']))
			$condition[]="ServerID = '{$_POST['serverid']}'";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="PayCode = '{$_POST['payCode']}'";

/*         $config = Config::model()->getInfo();
	if(in_array($config['openbt'], array('0','1'))){
			if($config['openbt'] == 0){
				$condition[]="isbt = 0";
			}else{
				$condition[]="isbt in(0,1,6)";
			}
		}	 */
		$model = PayLog::model();
		$criteria = new CDbCriteria;
		$condition=implode($condition,' and ');
		$criteria->condition =$condition;
		$criteria->select = "dwFenBaoID ,sum(PayMoney) as payTotal, count(distinct PayID ) as payIdTotal,PayCode";
		$criteria->group = 'dwFenBaoID,PayCode';
		$criteria->order = 'payTotal desc';
		
		$info=$model->findAll($criteria);
        $dwAccessArr = $accessTableM->getAccess('web_dwFenbao', $this->mangerInfo['id']);
        $newInfo = array();
        if (!empty($info)) {
            foreach ($info as $v) {
                $newInfo[$v->dwFenBaoID]['payTotal'][] = $v->PayCode . '：' . $v->payTotal;
                $newInfo[$v->dwFenBaoID]['payIdTotal'][] = $v->PayCode . '：' . $v->payIdTotal;
                $otherInfo = $this->gameIncome($v->payTotal, $v->dwFenBaoID);
                $newInfo[$v->dwFenBaoID]['remark'] = in_array('remark', $dwAccessArr) ? $otherInfo['remark'] : '';
                $newInfo[$v->dwFenBaoID]['income_split'] = in_array('income_split', $dwAccessArr) ? $otherInfo['income_split'] : '';
                $newInfo[$v->dwFenBaoID]['hainiu_income'] = in_array('hainiu_income', $dwAccessArr) ? $otherInfo['hainiu_income'] : '';
                $newInfo[$v->dwFenBaoID]['channel_income'] = in_array('channel_income', $dwAccessArr) ? $otherInfo['channel_income'] : '';
                $newInfo[$v->dwFenBaoID]['total'][] = $this->exchangeToCurrency($v->PayCode, $unityCurrency, $v->payTotal);
            }
        }
		if(!empty($info) && isset($_POST['excelLoad'])){
            $downInfo = array();
            $downInfo['tag'] = array('充值渠道', '金额(元)', "转{$unityCurrency}金额", '充值人数','备注','分成比例','海牛收入','渠道收入');
			$i = 0;
            foreach ($newInfo as $k => $v){
                $downInfo['data'][$i]['A'] = isset($fenbao[$k]) ? $fenbao[$k] : $k;
                $downInfo['data'][$i]['B'] = sortOutput($v['payTotal']);
                $downInfo['data'][$i]['C'] = addTogether($v['total']);
                $downInfo['data'][$i]['D'] = sortOutput($v['payIdTotal']);
                $downInfo['data'][$i]['E'] = $v['remark'];
                $downInfo['data'][$i]['F'] = $v['income_split'];
                $downInfo['data'][$i]['G']= $v['hainiu_income'];
                $downInfo['data'][$i]['H'] = $v['channel_income'];
                $i ++;
			}
			$this->download($downInfo, '.xls');
		}

        //总额
        $totalMoney = $this->toCNYMoney($model, $condition, $unityCurrency);
		$this->renderPartial('fenbaoStatistics', array(
				'info'=>$newInfo, 'totalMoney'=>$totalMoney ,'count'=>count($info),'fenbao'=>$fenbao,
				'game'=>$this->getGame(),'gameId'=>$gameId,'gameServer'=>$this->getServer(),'startTime'=>$startTime,'endTime' =>$endTime,
		));
	}
    /*
     * 计算渠道分成收入
     */
	private function gameIncome($amount, $fenbaoId){
        $fenbao = Dwfenbao::model();
        $info = $fenbao->find(array('condition'=>'fenbao_id=:fenbaoId','params'=>array(':fenbaoId'=>$fenbaoId)));
        $returnArr = array();

        $returnArr['remark'] = ''; //备注
        $returnArr['income_split'] = ''; //分成比例
        $returnArr['hainiu_income'] = ''; //海牛收入
        $returnArr['channel_income'] = ''; //渠道收入
        if($info){
            $channel_cost = $info->channel_cost ? 100-($info->channel_cost) : 100;
            $tariff = $info->tariff;
            if($info->income == 0 || empty($info->income)){
                //没使用阶梯收费
                $returnArr['remark'] = $info->remark;
                $returnArr['income_split'] = $info->income_split.'<br>'.$info->income;
                $splitArr = explode(':', $returnArr['income_split']);
                $hainiuSplit = isset($splitArr[0]) ? $splitArr[0] : 0;
                //$channelSplit = isset($splitArr[1]) ? $splitArr[1] : 0;
                if($info->income_split) {
                    $hainiuIncome = ($amount*$channel_cost/100)*$hainiuSplit/100*((100-$tariff)/100);
                    $returnArr['hainiu_income'] = sprintf("%.2f", $hainiuIncome);
                    $returnArr['channel_income'] = sprintf("%.2f", $amount - $hainiuIncome);
                }
            } else {
                //使用阶梯收费
                $returnArr['remark'] = nl2br($info->remark);
                $returnArr['income_split'] = $info->income_split.'<br>'.$info->income;

                $splitArr = explode('#', $returnArr['income_split']);
                $incomeArr = explode('#', $info->income);
                $index = 999; //定义下表
                foreach ($incomeArr as $k => $v){
                    $pos = strpos($v, '~');
                    if($pos == false){
                        if($amount < $v*10000){
                            $index = $k;
                            break;
                        } elseif (count($incomeArr) == ($k + 1) && $amount>$v*10000){
                            $index = $k;
                            break;
                        }
                    } else {
                        $ve = explode('~', $v);
                        if($amount >=$ve[0]*10000 && $amount <= $ve[1]*10000){
                            $index = $k;
                            break;
                        }
                    }
                }
                if (isset($splitArr[$index])){
                    $splitResult = $splitArr[$index];
                    $splitArr = explode(':', $splitResult);
                    $hainiuSplit = isset($splitArr[0]) ? $splitArr[0] : 0;
                    //$channelSplit = isset($splitArr[1]) ? $splitArr[1] : 0;
                    $hainiuIncome = ($amount*$channel_cost/100)*$hainiuSplit/100*((100-$tariff)/100);
                    $returnArr['hainiu_income'] = sprintf("%.2f", $hainiuIncome);
                    $returnArr['channel_income'] = sprintf("%.2f", $amount-$hainiuIncome);
                }

            }
        }
        return $returnArr;
    }



	//分包查询
    public function actionFenbaoIndex(){
        $model = PayLog::model();
        $condition = $this->_search();
        $goodInfo = $this->_list($model,$condition);
        $fenbao = $this->getFenbao();
        $goodInfo['fenbao'] = $fenbao;


        $this->render('fenbaoIndex',$goodInfo);
    }


	//区服统计
    public function actionServerStatistics(){
        $gameId = isset($_POST['gameid']) ? $_POST['gameid'] : $this->mangerInfo['game_id'];
        $gameServer = $this->getServer();
        $condition=array();
        $condition[] = "rpCode in ('1','10')";
        if(!empty($this->mangerInfo['channel_id']))
        	$condition[]="CPID in ({$this->mangerInfo['channel_id']})";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="PayCode = '{$_POST['payCode']}'";
        $startTime = isset($_POST['startTime']) ? $_POST['startTime'].' 00:00:00' : date('Y-m-d 00:00:00');
        $endTime = isset($_POST['startTime']) ? $_POST['startTime'].' 23:59:59' : date('Y-m-d 23:59:59');
        $condition[] = "Add_Time >='$startTime' and Add_Time<='$endTime'";
        //如果账号游戏id不为0 查询该游戏ID的订单
        if(!empty($gameId))
            $condition[] =  "game_id = '$gameId'";
        $model = PayLog::model();
        $criteria = new CDbCriteria;
        $condition = implode($condition,' and ');
        $criteria->condition =$condition;
        $criteria->select = "ServerID, sum(PayMoney) as payTotal, count(distinct PayID ) as payIdTotal";
        $criteria->group = 'ServerID';
        $criteria->order = 'ServerID asc';

        $info=$model->findAll($criteria);
        $totalMoney = 0;
        if(!empty($info)){
            foreach ($info as $v){
                $totalMoney +=$v->payTotal;
            }
        }
        if(!empty($info) && isset($_POST['excelLoad'])){
            $newInfo = array();
            $newInfo['tag'] = array('区服', '金额(元)', '充值人数');
            foreach ($info as $k => $v){
                $newInfo['data'][$k]['A'] = isset($gameServer[$v->ServerID]) ? $gameServer[$v->ServerID] : $v->ServerID;
                $newInfo['data'][$k]['B'] = $v->payTotal;
                $newInfo['data'][$k]['C'] = $v->payIdTotal;
            }
            $this->download($newInfo, '.xls');
        }

        $this->renderPartial('serverStatistics', array(
            'info'=>$info, 'totalMoney'=>$totalMoney ,'count'=>count($info), 'gameId' => $gameId,
            'game'=>$this->getGame(),'gameServer'=>$gameServer,'startTime'=>$startTime,
        ));
    }
    //区间统计
    public function actionAreaStatistics(){
        $gameId = isset($_POST['gameid']) ? $_POST['gameid'] : $this->mangerInfo['game_id'];

        $condition=array();
        $condition[] = "rpCode in ('1','10')";
        if(!empty($this->mangerInfo['channel_id']))
            $condition[] =  "CPID in ({$this->mangerInfo['channel_id']})";
        $startTime = isset($_POST['startTime']) ? $_POST['startTime'].' 00:00:00' : date('Y-m-d 00:00:00');
        $endTime = isset($_POST['endTime']) ? $_POST['endTime'].' 23:59:59' : date('Y-m-d 23:59:59');
        $condition[] = "Add_Time between '$startTime' and '$endTime'";
        //如果账号游戏id不为0 查询该游戏ID的订单
        if(!empty($gameId))
            $condition[] =  "game_id = '$gameId'";
        if(isset($_POST['serverid']) && !empty($_POST['serverid']))
            $condition[]="ServerID = '{$_POST['serverid']}'";
        if(isset($_POST['payCode']) && !empty($_POST['payCode']))
            $condition[]="PayCode = '{$_POST['payCode']}'";
        $model = PayLog::model();
        $condition=implode($condition,' and ');

        $areaPrice  = array('0~24','25~25', '26~100', '101~300', '301~500', '501~1000', '1001~2000', '2001~3000', '3001~6000', '6001~10000', '10000');
        $data = array();
        foreach ($areaPrice as $k => $v){
            $where = $condition;
            $v1_v2 = explode('~', $v);
            if(count($v1_v2) == 1){
                $v1 = $v1_v2[0];
                $where .= " and PayMoney>=$v1";
            }else {
                $v1 = $v1_v2[0];
                $v2 = $v1_v2[1];
                $where .= " and PayMoney>=$v1 and PayMoney<=$v2";
            }
            $sql = "select sum(PayMoney) as payTotal, count(distinct PayID) as payIdTotal from {{pay_log}} where $where limit 1";
            $info = $model->findBySql($sql);
            $data[$v]['money'] = intval($info->payTotal);
            $data[$v]['count'] = intval($info->payIdTotal);

        }
        $totalMoney = 0;
        foreach ($data as $v){
            $totalMoney += $v['money'];
        }

        if(!empty($data) && isset($_POST['excelLoad'])){
            $newInfo = array();
            $newInfo['tag'] = array('区间', '金额(元)', '充值人数');
            foreach ($data as $k => $v){
                $newInfo['data'][$k]['A'] = $k;
                $newInfo['data'][$k]['B'] = $v['money'];
                $newInfo['data'][$k]['C'] = $v['count'];
            }
            $this->download($newInfo, '.xls');
        }




        $this->renderPartial('areaStatistics', array(
            'data'=>$data, 'totalMoney'=>$totalMoney ,'count'=>count($data),'gameId'=>$gameId,
            'game'=>$this->getGame(),'gameServer'=>$this->getServer(),'startTime'=>$startTime,'endTime' =>$endTime,
        ));
    }

	

}