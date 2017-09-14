<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:12
 * 后台基本配置信息
 */
define('ROOT_PATH', str_replace('interface/update.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'../inc/config.php';
include_once ROOT_PATH.'../inc/config_account.php';
include_once ROOT_PATH."../inc/function.php";
$rules = array(
	'3280'=>array('648'=>1,'328'=>3,'128'=>7,'30'=>14,'25'=>8,'6'=>22),		
	'648'=>array('328'=>1,'128'=>1,'30'=>1,'25'=>6,'6'=>2),
	'328'=>array('128'=>1,'30'=>4,'25'=>2,'6'=>5),
	'128'=>array('30'=>2,'25'=>2,'6'=>3),
	'30'=>array('6'=>5)
);

$channel = '35'; //快发
$fenbao = '612001';
$ydate = '2017-07';
//当月充值玩家
$sql = "SELECT PayID,PayName,ServerID,dwFenBaoID,CPID,clienttype FROM `web_pay_log` where
CPID=$channel and dwFenBaoID=$fenbao and Add_Time like '$ydate%' GROUP BY PayID,ServerID;";
$conn = SetConn(88);
$query = @mysqli_query($conn,$sql);
$info = array();
while($row = @mysqli_fetch_assoc($query)){
	$info[] = $row;
}
$t = date("t",strtotime("$ydate")); //月天数
$count = count($info);
$insertsql = "insert into web_pay_log(isbt,rpCode,game_id,PayID,PayName,ServerID,dwFenBaoID,CPID,clienttype,PayMoney,OrderID,Add_Time)values";
for ($i=1;$i<=$t;$i++){ //循环日期
	for ($j=0;$j<=23;$j++){ //循环小时
		$begintime = time();
		$msql = $insertsql;
		$mydatas = array();
		echo $ydate.'-'.$i.' '.$j.PHP_EOL;
		//小时内充值数据
		$sql = "select id,PayMoney,Add_Time from `web_pay_log`  where
		CPID=$channel  and isbt=1 and Add_Time like '$ydate-".str_pad($i,2,'0',STR_PAD_LEFT)." ".str_pad($j,2,'0',STR_PAD_LEFT)."%' 
				 and PayMoney=(select max(PayMoney) PayMoney from `web_pay_log` where 
		CPID=$channel  and isbt=1 and Add_Time like '$ydate-".str_pad($i,2,'0',STR_PAD_LEFT)." ".str_pad($j,2,'0',STR_PAD_LEFT)."%') ;";
		$query = @mysqli_query($conn,$sql);
		$m = rand(1,5);
		$mt = 0;
		$c = 0;
		while($mt <$m && $row = @mysqli_fetch_assoc($query)){ //随机拆分数据
			$mt ++;
			if(!isset($rules[intval($row['PayMoney'])])){
				continue;
			}
			$rule = $rules[intval($row['PayMoney'])];
			$updatesql = "update web_pay_log set isbt=3 where isbt=1 and CPID=$channel and id={$row['id']}";
			$query = @mysqli_query($conn,$updatesql);
			if($query){ //更新成功
				foreach ($rule as $k=>$v){ //选中规则分配人数
					for($y=0;$y<$v;$y++){
						$mydata = array();
						$c++;
						$n = rand(0,$count-1);
						$mtime = substr($row['Add_Time'], 0,14).str_pad(rand(0,59),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT);
						$mydata['PayName'] = $info[$n]['PayName'];
						$mydata['CPID'] = $info[$n]['CPID'];
						$mydata['clienttype'] = $info[$n]['clienttype'];
						$mydata['Add_Time'] = $mtime;
						$mydata['PayID'] = $info[$n]['PayID'];
						$mydata['ServerID'] = $info[$n]['ServerID'];
						$mydata['dwFenBaoID'] = $info[$n]['dwFenBaoID'];
						$mydata['payMoney'] = $k;
						//$mydata['orderId'] = '8_'.$mydata['ServerID'].'_'.$mydata['PayID'].'_android_3_'.random_my($mtime);;
						$mydata['orderId'] = date('Ymd',strtotime($row['Add_Time'])).str_pad(rand(4,97),2,'0',STR_PAD_LEFT) . '001' . '6' . str_pad(rand(9,9999),4,'0',STR_PAD_LEFT);
						$mydatas[] = $mydata;
						$msql .= "(6,1,8,{$mydata['PayID']},'{$mydata['PayName']}','{$mydata['ServerID']}','{$mydata['dwFenBaoID']}','{$mydata['CPID']}','{$mydata['clienttype']}'
						,'{$mydata['payMoney']}','{$mydata['orderId']}','{$mydata['Add_Time']}'),";
					}
				}
			}
		}
		if(!$mydatas){
			continue;
		}
		$query = @mysqli_query($conn,rtrim($msql,','));
		if($query){ //插入成功
			write_log(ROOT_PATH."../log","btupdatesql_{$ydate}-{$i}_",$msql .date("Y-m-d H:i:s")."\r\n");
			write_log(ROOT_PATH."../log","btupdatedata_{$ydate}-{$i}_",json_encode($mydatas) .date("Y-m-d H:i:s")."\r\n");
			foreach ($mydatas as $v){
				//myTongjiData(8,$v['PayID'],$v['ServerID'],$v['dwFenBaoID'],0,$v['payMoney'],$v['orderId'],1,10002,0,6,strtotime($v['Add_Time']));
			}
			$endtime = time();
			echo '执行时间：'.($endtime-$begintime) .' ,执行条数：'.$c;
		}else{
			echo mysqli_error($conn).$msql;
		}
	}
	
}

		


		function random_my($date) {
			$hash = '';
			$array = array ('~','!','@','#','$','%','^','&','*','(');
			shuffle ( $array );
			for($i = 0; $i < 3; $i ++) {
				$hash .= uniqid ( rand () ). $date . $array [$i];
			}
			return md5 ( md5 ( $hash ) );
		}
		
		function myTongjiData($gameId, $accountId,$serverId,$channel,$lev=0,$money,$orderId,$isNew=1,$appId,$isPay=0 , $isbt = '2' ,$created = 0){
			$tongjiArr = array();
			$tongjiArr['accountid'] = $accountId;
			$tongjiArr['serverid'] = $serverId;
			$tongjiArr['channel'] = $channel;
			$tongjiArr['lev'] = $lev;
			$tongjiArr['money'] = $money;
			$tongjiArr['orderid'] = $orderId;
			$tongjiArr['is_new'] = $isNew;
			$tongjiArr['is_pay'] = $isPay;
			$tongjiArr['isbt'] = $isbt;
			$tongjiArr['created_at'] = time();
			if($created){
				$tongjiArr['created_at'] = $created;
			}
			$tongjiArr['appid'] = $appId; //$tongjiServer[$gameId];
			//发送数据
			SAddData($tongjiArr);
			return true;
		}

