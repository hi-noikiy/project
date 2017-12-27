<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* pp支付回调
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
include(ROOT_PATH.'interface/pp/Rsa.php');
include(ROOT_PATH.'interface/pp/MyRsa.php');
//$str = 'a:10:{s:8:"order_id";s:16:"2016112836267343";s:6:"billno";s:30:"8_8001_142089_502039838.737296";s:7:"account";s:10:"cjtest1001";s:6:"amount";s:4:"1.00";s:6:"status";s:1:"0";s:6:"app_id";s:4:"7649";s:4:"uuid";s:0:"";s:4:"zone";s:1:"0";s:6:"roleid";s:7:"1000165";s:4:"sign";s:344:"jCBpRFAP9NkVsT0dU8QIZASVGL/3iYJSvVGLl6nAi1hHaL6XjZcbPqkBNzoQt9FUVO0G/RFalrzwp0XnmojbQxdzdgH9QlbfNogckEmaqtgQkKV88VDJUzvWqn/wey7Tb74pLflsuaS2YMUQkYbQkDy/koIv6pY2mAsJ7B898N3kNgUPR0iapG2HqLGgPrEW110MDZNpFaWnGMKdnngQsFa0D/RAfzFkJT0upV7mbi2V+NmiKDvfHMrmyK9ooSEgwMqlrBBD4ufLy5GLINlM2T1j0UUDiD5LpwJ5PVnCwq9TlYFugGy0lPr4WZMaZ6sxBGzC6ydemxxrXYpm6YnGpQ==";}';
//$_POST = unserialize($str);
$post = serialize($_POST);
$get = serialize($_GET);
$result = testController::Appreturn();

$order_id = trim($_POST['order_id']);
$billno = trim($_POST['billno']);
$account = trim($_POST['account']);
$amount = trim($_POST['amount']);
$status = trim($_POST['status']);
$app_id = trim($_POST['app_id']);
$sign = trim($_POST['sign']);
$ip = getIP_front();

$extraInfo_arr = explode("_", $billno);
$game_id = intval($extraInfo_arr[0]);
$server_id = intval($extraInfo_arr[1]);
$account_id = intval($extraInfo_arr[2]);
$isgoods = intval($extraInfo_arr[4]);

write_log(ROOT_PATH."log","pp_callback_info_log_", "post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
/*if(!in_array($ip, $pp_ip)){
    write_log(ROOT_PATH."log","ip_error_", "pp callbak error! ip= $ip ".date("Y-m-d H:i:s")."\r\n");
    exit("0");
}*/
if(!$game_id || !$server_id || !$account_id){
	write_log(ROOT_PATH."log","pp_callback_error_", "param error! game_id=$game_id, server_id=$server_id, accountid=$account_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("2");
}
//获取账号信息
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
$query_account = @mysqli_query($conn,$sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","pp_callback_error_", "account is not exist! accountid=$account_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("4");//账号不存在
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query= @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","pp_callback_error_", "order is exist! order_id=$order_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("success");//订单已存在
}
$PayMoney = intval($amount);
if($order_id && $result=='success'){
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
    $sql=$sql." VALUES (22,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","pp_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit('6');
    }
    $isPay = 0;
    if($status==0){
        ppPayLog($order_id,1,$PayMoney);//更新充值记录
        WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);    
    }else{
        $isPay = 1;
        ppPayLog($order_id,2,$PayMoney);//更新充值记录
    }
    //统计
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
    exit("success");
}else{
	write_log(ROOT_PATH."log","pp_callback_error_", "sign error! result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("fail");
}

function ppPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
    $sql=$sql." where OrderID='$OrderID'";
    //echo $sql;
    if (mysqli_query($conn,$sql) == False){
        //写入失败日志
        write_log(ROOT_PATH."log","pp_callback_error_", "sql=$sql".mysqli_error($conn)."   ".date("Y-m-d H:i:s")."\r\n");
        exit;
    }
}

class testController
{
    public function Appreturn()
    {
        $notify_data = $_POST;
        //	$notify_data = $_GET;//测试
        $chkres = self::chk($notify_data);
        //	error_log(date("Y-m-d h:i:s")."result ".serialize($chkres)."\r\n",3,'rsa.log');
        if($chkres) {
            //验证通过
            //--------业务处理----------
            //
            return "success";
        }else{
            return "fail";
        }

    }

    public function chk($notify_data)
    {
        $privatedata = $notify_data['sign'];
        //	error_log(date("Y-m-d h:i:s")." ".serialize($privatedata)."\r\n",3,'rsa.log');

        $privatebackdata = base64_decode($privatedata);
        //	error_log(date("Y-m-d h:i:s")."base64_decode ".serialize($privatebackdata)."\r\n",3,'rsa.log');
        $MyRsa = new MyRsa;
//        if($game_id==4){
//            $data = $MyRsa->publickey_decodeing($privatebackdata, MyRsa::public_key_4);
//        }else{
            $data = $MyRsa->publickey_decodeing($privatebackdata, MyRsa::public_key);
//        }
        
        //	error_log(date("Y-m-d h:i:s")."publickey_decodeing ".$data."\r\n",3,'rsa.log');
        /*
        $ex_data = explode("&",$data);
        $rs = array();
        foreach($ex_data as $v){
            $k_v = explode("=",$v);
            $rs[$k_v[0]] = $k_v[1];
        }
        */
        $rs = json_decode($data,true);
        //	error_log(date("Y-m-d h:i:s")."rs ".serialize($rs)."\r\n",3,'rsa.log');

        if($rs["billno"] == $notify_data['billno']&&$rs["amount"] == $notify_data['amount']&&$rs["status"] == $notify_data['status']) {
            return true;
        }else{
            return false;
        }
    }

}
?>