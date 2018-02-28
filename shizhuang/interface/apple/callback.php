<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午2:08
 */
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","apple_all_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$ext = $_REQUEST['ext'];
$data = $_REQUEST['data'];
//url自动转码把+号转换成空格了
$data = str_replace(" ","+", $data);
$sendData = array("receipt-data"=>$data);
$sendDataJson = json_encode($sendData);

$extArr = explode('_', $ext);
$game_id= $extArr[0];
$server_id = $extArr[1];
$player_id = $extArr[2];
$account_id = $extArr[3];
$type = $extArr[4];
$isgoods = $extArr[5];
$mac = $extArr[6];
$url = "https://buy.itunes.apple.com/verifyReceipt";
$sandboxurl = "https://sandbox.itunes.apple.com/verifyReceipt";  //测试的
$result = common_json_post($url, $sendDataJson);
write_log(ROOT_PATH."log","apple_result_log_","result=$result, ".$ext.date("Y-m-d H:i:s")."\r\n");
$resultArr = json_decode($result,true);
if($resultArr['status'] == '21007'){
	$result = common_json_post($sandboxurl, $sendDataJson);
	write_log(ROOT_PATH."log","apple_result_log_","result=$result, ".$ext.date("Y-m-d H:i:s")."\r\n");
	$resultArr = json_decode($result,true);
}
if(!isset($resultArr['status']) && !isset($resultArr['receipt']['in_app'])){
    echo 'fail';
    exit();
}
$snum = giQSModHash($account_id);
$conn = SetConn($game_id,$snum,1);//account分表
if($conn == false){
	echo 'fail';
	write_log(ROOT_PATH."log","apple_error_log_","account connect error., ".date("Y-m-d H:i:s")."\r\n");
	exit();
}
$acctable = betaSubTable($account_id,'account',999);
$sql = "select NAME,dwFenBaoID,clienttype from $acctable where id=$account_id limit 1;";
$query = mysqli_query($conn, $sql);
$rs = @mysqli_fetch_array($query);
if(!isset($rs['NAME'])){
    echo 'fail';
    write_log(ROOT_PATH."log","apple_error_log_","account not exist. sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
    exit();
}
$PayName=$rs["NAME"];
$dwFenBaoID=$rs["dwFenBaoID"];
$clienttype=$rs['clienttype'];

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","apple_error_log_","web db connect error. ".date("Y-m-d H:i:s")."\r\n");
    echo 'fail';
    exit();
}
$status = $resultArr['status'];
$orderInfo = $resultArr['receipt']['in_app'];
$returnMsg = 'fail';

foreach ($orderInfo as $v){
    $quantity = $v['quantity'];
    $productId = $v['product_id'];
    $orderId = $v['transaction_id'];
    $money = $appleIdVal[$productId][0];
    $yuanbao = $appleIdVal[$productId][1];
    $currency = $appleIdVal[$productId][2];
    
    $sql = " select count(id) count from web_pay_log where OrderID='$orderId' limit 1;";
    $query = @mysqli_query($conn, $sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['count']){
        write_log(ROOT_PATH."log","apple_error_log_","order exist. orderId=$orderId, ".date("Y-m-d H:i:s")."\r\n");
        $returnMsg = 'success';
        continue;
    }
    if($status==0 && $quantity>0 && $money){
        $Add_Time = date("Y-m-d H:i:s");
        $rpCode = 1;
        $insert_sql = " insert into web_pay_log(CPID,ServerID,PayCode,PlayerID,PayMoney,data,PayName,dwFenBaoID,Add_Time,rpCode,PayID,OrderID,game_id) values('19',$server_id,'$currency','$player_id','$money','$yuanbao','$PayName','$dwFenBaoID','$Add_Time','$rpCode','$account_id','$orderId','$game_id') ";
        if(mysqli_query($conn, $insert_sql)){
            WriteCard_money(1,$server_id, $yuanbao,$player_id, $orderId,8,0,0,$isgoods);//写入游戏库
            //统计数据
            sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$orderId,1);
            appData(array('accountid'=>$account_id,'serverid'=>$server_id,'channel'=>$dwFenBaoID,'money'=>$money,'orderid'=>$orderId,'created_at'=>time(),'mac'=>$mac,'gameid'=>$game_id));
            $returnMsg = 'success';
        }else{
            write_log(ROOT_PATH."log","apple_error_log_","sql error,sql=$insert_sql, ".date("Y-m-d H:i:s")."\r\n");
            $returnMsg = 'fail';
        }
    } else {
        $msg = json_encode(array('status'=>$status,'quantity'=>$quantity,'money'=>$money));
        write_log(ROOT_PATH."log","apple_error_log_","msg=$msg, ".date("Y-m-d H:i:s")."\r\n");
        $returnMsg = 'fail';
    }
}
exit($returnMsg);
//统计数据处理
function appData($data){
	global $tongjiServer;
	$url = $tongjiServer[$data['gameid']].'ApiPay/ApplePaylog';
	$jsonData = base64_encode(json_encode($data));
	$postData = array();
	$postData['data'] = $jsonData;
	$postData['sign'] = Sign($data);

	$rs = https_post($url, $postData);
	$rows = json_decode($rs, true);
	if(is_array($rows) && $rows['errcode'] != 0){
		write_log(ROOT_PATH."log","appData_error_","result=$rs, ".date("Y-m-d H:i:s")."\r\n");
	}
}


