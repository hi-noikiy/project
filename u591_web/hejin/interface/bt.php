<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:12
 * 后台基本配置信息
 */
define('ROOT_PATH', str_replace('interface/bt.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'../inc/config.php';
include_once ROOT_PATH.'../inc/config_account.php';
include_once ROOT_PATH."../inc/function.php";
$channel = '138'; //快发
$fenbao = '652001';
$ydate = '2017-07';
$rate = 9;
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
for ($i=30;$i<=30;$i++){ //循环日期
	$mydatas = array();
	$msql = $insertsql;
	$begintime = time();
	$c = 0;
	for ($j=0;$j<=23;$j++){ //循环小时
		echo $ydate.'-'.$i.' '.$j.PHP_EOL;
		//小时内充值数据
		$sql = "SELECT PayMoney,Add_Time FROM `web_pay_log` where
		CPID=$channel and dwFenBaoID=$fenbao and isbt=0 and Add_Time like '$ydate-".str_pad($i,2,'0',STR_PAD_LEFT)." ".str_pad($j,2,'0',STR_PAD_LEFT)."%';";
		$query = @mysqli_query($conn,$sql);
		while($row = @mysqli_fetch_assoc($query)){
			$mydata = array();
			for($z=0;$z<$rate;$z++){ // 插入倍数相同数据
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
				$mydata['payMoney'] = $row['PayMoney'];
				//$mydata['orderId'] = '8_'.$mydata['ServerID'].'_'.$mydata['PayID'].'_android_4_'.random_my($mtime);;
				$mydata['orderId'] = date('Ymd',strtotime($row['Add_Time'])).str_pad(rand(4,97),2,'0',STR_PAD_LEFT) . '001' . '6' . str_pad(rand(5,9999),4,'0',STR_PAD_LEFT);
				$mydatas[] = $mydata;
				$msql .= "(1,1,8,{$mydata['PayID']},'{$mydata['PayName']}','{$mydata['ServerID']}','{$mydata['dwFenBaoID']}','{$mydata['CPID']}','{$mydata['clienttype']}'
				,'{$mydata['payMoney']}','{$mydata['orderId']}','{$mydata['Add_Time']}'),";
			}
		}
		}
		if(!$mydatas){
			continue;
		}
		$query = @mysqli_query($conn,rtrim($msql,','));
		if($query){ //插入成功
			write_log(ROOT_PATH."../log","btsql_{$ydate}-{$i}_",$msql .date("Y-m-d H:i:s")."\r\n");
			write_log(ROOT_PATH."../log","btdata_{$ydate}-{$i}_",json_encode($mydatas) .date("Y-m-d H:i:s")."\r\n");
			foreach ($mydatas as $v){
				myTongjiData(8,$v['PayID'],$v['ServerID'],$v['dwFenBaoID'],0,$v['payMoney'],$v['orderId'],1,10002,0,1,strtotime($v['Add_Time']));
			}
			$endtime = time();
			echo '执行时间：'.($endtime-$begintime) .' 总条数：'.$c;
		}else{
			echo mysqli_error($conn).$msql;
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
			if(in_array($isbt, array(0,1))){
				$tongjiArr['isbt'] = $isbt;
			}
			$tongjiArr['created_at'] = time();
			if($created){
				$tongjiArr['created_at'] = $created;
			}
			$tongjiArr['appid'] = $appId; //$tongjiServer[$gameId];
			//发送数据
			SAddData($tongjiArr);
			return true;
		}

