<?php
require_once("config.php");
require_once 'service/AlipayTradeService.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","alipay_wap_callback_all_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$arr=$_POST;
$alipaySevice = new AlipayTradeService($config); 
$result = $alipaySevice->check($arr);
if($result) {//验证成功
	$out_trade_no = $_POST['out_trade_no'];
	$outTradeNoArr = explode('_', $out_trade_no);
	$game_id = $outTradeNoArr[0];
	$server_id = $outTradeNoArr[1];
	$account_id = $outTradeNoArr[2];
	$trade_status = $_POST['trade_status'];
	if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//获取账号信息
		$money = intval($_POST['total_amount']);
		global $accountServer;
		$accountConn = $accountServer[$game_id];
		$conn = SetConn($accountConn);
		$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
		$query_account = mysqli_query($conn,$sql_account);
		$result_account = mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","alipay_wap_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
			exit("fail");//账号不存在
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		$conn = SetConn(88);
		//判断订单id情况
		$sql = "select id,rpCode from web_pay_log where OrderID ='$out_trade_no' limit 1";
		$query = @mysqli_query($conn,$sql);
		$result_count = @mysqli_fetch_assoc($query);
		if($result_count['id']){
			write_log(ROOT_PATH."log","alipay_wap_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
			exit("success");//订单已存在
		}
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
		$sql=$sql." VALUES (163,$account_id,'$PayName','$server_id','$money','$out_trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","alipay_wap_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
			exit("fail");
		}
		WriteCard_money(1,$server_id, $money,$account_id, $out_trade_no,8,0,1);
		//统计数据
		global $tongjiServer;
		$tjAppId = $tongjiServer[$game_id];
		sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$out_trade_no,1,$tjAppId);
		exit("success");
	}else{
		write_log(ROOT_PATH."log","alipay_wap_callback_error_", "sign error! result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit("fail");
	}

}else {
	//验证失败
	echo "fail";	//请不要修改或删除

}
