<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:12
 * 后台基本配置信息
 */
define('ROOT_PATH', str_replace('interface/getlog.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'../inc/config.php';
include_once ROOT_PATH.'../inc/config_account.php';
include_once ROOT_PATH."../inc/function.php";

$channel = '138'; //快发
$fenbao = '652001';
$ydate = '2017-05';
$conn = SetConn(88);
$t = date("t",strtotime("$ydate")); //月天数
$usql = '';
for ($i=1;$i<=$t;$i++){ //循环日期
	$handle = fopen("/data/u591/log/1707/btgetdata_{$ydate}-{$i}_170730.txt", "r");
	if ($handle) {
		while (!feof($handle)) {
			$buffer = fgets($handle,12096);
			//$data = json_decode($buffer,true);
			print_r($buffer);die;
			/*if($date<1492620300){
			 continue;
			 }
			 if($date>1492628400){
			 break;;
			}*/
			/*$time = date('Ymd',$date);
			parse_str($sarr[1], $arr);
			$json = base64_decode($arr['data_raw']);
			$lastdata = json_decode($json,true);
			$sql = "INSERT INTO u_register(`serverid`, `channel`, `appid`, `accountid`, client_type,ip,created_at,mac,client_version,regway,reg_date)
			VALUES ({$lastdata['serverid']},{$lastdata['channel']},10002,{$lastdata['accountid']}
			,'{$lastdata['client_type']}',".ip2long($arr['ip']).",{$date},'{$lastdata['mac']}','{$lastdata['client_version']}','{$lastdata['regway']}',{$time})
			ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`client_type`=VALUES(client_type),`ip`=VALUES(ip),
			`created_at`=VALUES(created_at),`mac`=VALUES(mac),`reg_date`=VALUES(reg_date),`regway`=VALUES(regway),`client_version`=VALUES(client_version)
			";
			$dbsdk->query($sql);
			//echo $sql;
			echo json_encode($dbsdk->error());
			$i++;
			PHP_EOL;
			//if($i>10)break;
			echo $i;
		}
	$mydatas = array();*/
	/*for ($j=0;$j<=23;$j++){ //循环小时
		$begintime = time();
		echo $ydate.'-'.$i.' '.$j.PHP_EOL;
		//小时内充值数据
		$sql = "select id,Add_Time,OrderID from `web_pay_log`  where
		CPID=$channel  and isbt=1 and Add_Time like '$ydate-".str_pad($i,2,'0',STR_PAD_LEFT)." ".str_pad($j,2,'0',STR_PAD_LEFT)."%' ;";
		$query = @mysqli_query($conn,$sql);
		$c = 0;
		while($row = @mysqli_fetch_assoc($query)){ //随机拆分数据
			$orderid = date('Ymd',strtotime($row['Add_Time'])).str_pad(rand(4,97),2,'0',STR_PAD_LEFT) . '001' . '6' . str_pad(rand(9,9999),4,'0',STR_PAD_LEFT);
			$updatesql = "update web_pay_log set OrderID='$orderid' where id={$row['id']} and CPID=$channel";
			$mquery = @mysqli_query($conn,$updatesql);
			if($mquery){ //更新成功
				$mydatas[$row['OrderID']] = $orderid;
				$usql .= "update u_paylog set orderid='$orderid' where orderid='{$row['OrderID']}' and isbt=1 and channel=$fenbao;\r\n";
				$c++;
			}
		}
		$endtime = time();
		echo '执行时间：'.($endtime-$begintime) .' ,执行条数：'.$c;
	}*/
	//write_log(ROOT_PATH."../log","btgetdata_{$ydate}-{$i}_",json_encode($mydatas) .date("Y-m-d H:i:s")."\r\n");
}
//write_log(ROOT_PATH."../log","btgetsql_{$ydate}_",$usql .date("Y-m-d H:i:s")."\r\n");
	}
}
