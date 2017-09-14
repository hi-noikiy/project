<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/6/12
 * Time: 下午8:40
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","iapppay_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:3:{s:9:"transdata";s:306:"{"appid":"3010423930","appuserid":"1000101","cporderid":"8_1990_1594740_ios_0_510489033.353417","cpprivate":"8_1990_1594740_ios_0_510489033.353417","currency":"RMB","feetype":0,"money":6.00,"paytype":4,"result":0,"transid":"32271703061830336331","transtime":"2017-03-06 18:31:46","transtype":0,"waresid":2}";s:4:"sign";s:172:"UKFC0ca+wyRKNcn9yPFqtU7NcdWSZmE7xDX2H/ph0P/JcoAXmv8gnZn5mChpXicjNflGurgm3DM6FFdvAtx8lQ9zqSO/BlErYLGVzsOzV8zkZ1kBlDi04GzALof679YjzP9hIao/YbJVw0XhSARK6jURFhZrkSahTm1nv1SD7xQ=";s:8:"signtype";s:3:"RSA";}';
//$_POST = unserialize($str);
global $key_arr, $accountServer;
$string = $_POST;//接收post请求数据
if($string ==null){
    exit('failed');
}else{
    $transdata = $string['transdata'];
    $arr = json_decode($transdata);
	if($arr->result != 0){
		exit('SUCCESS');
	}
    if(stripos("%22",$transdata)){
        //判断接收到的数据是否做过 Urldecode处理，如果没有处理则对数据进行Urldecode处理
        $string= array_map ('urldecode',$string);
    }
    $respData = 'transdata='.$string['transdata'].'&sign='.$string['sign'].'&signtype='.$string['signtype'];//把数据组装成验签函数要求的参数格式
    //  验签函数parseResp（） 中 只接受明文数据。数据如：transdata={"appid":"3003686553","appuserid":"10123059","cporderid":"1234qwedfq2as123sdf3f1231234r","cpprivate":"11qwe123r23q232111","currency":"RMB","feetype":0,"money":0.12,"paytype":403,"result":0,"transid":"32011601231456558678","transtime":"2016-01-23 14:57:15","transtype":0,"waresid":1}&sign=jeSp7L6GtZaO/KiP5XSA4vvq5yxBpq4PFqXyEoktkPqkE5b8jS7aeHlgV5zDLIeyqfVJKKuypNUdrpMLbSQhC8G4pDwdpTs/GTbDw/stxFXBGgrt9zugWRcpL56k9XEXM5ao95fTu9PO8jMNfIV9mMMyTRLT3lCAJGrKL17xXv4=&signtype=RSA
    $cpprivate = $arr->cpprivate;
    $cpprivateArr = explode('_', $cpprivate);
    $gameId = isset($cpprivateArr[0]) ? $cpprivateArr[0] : 0;
    $serverId = isset($cpprivateArr[1]) ? $cpprivateArr[1] : 0;
    $accountId = isset($cpprivateArr[2]) ? $cpprivateArr[2] : 0;
    $type = isset($cpprivateArr[3]) ? $cpprivateArr[3] : 'ios';

    $platpKey = $key_arr[$gameId][$type]['platpKey'];
    if(!parseResp($respData, $platpKey, $respJson)) {
        //验签失败
        write_log(ROOT_PATH."log","iapppay_callback_error_", "sign error! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit('failed');
    }
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","iapppay_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
        exit('failed');
    }
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","iapppay_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit('failed');
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clientType = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    //判断订单id情况
    $payMoney = $arr->money;
    $orderId = $arr->transid;
    $resultStatus=$arr->result;

    $sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
    $query = @mysqli_query($conn,$sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","iapppay_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit('SUCCESS');
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (146,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '2')";
    if (mysqli_query($conn,$sql) == false){
        write_log(ROOT_PATH."log","iapppay_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit('failed');
    }
    $isPay = 1;
    if($resultStatus == '0'){
        $isPay = 0;
        ChangPayLog($orderId, 1, $payMoney);
        WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    }
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId,$isPay);
    exit('SUCCESS');
}

function ChangPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime'";
    $sql=$sql." where OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","iapp_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}