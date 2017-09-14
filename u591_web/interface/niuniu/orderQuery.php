<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/14
 * Time: 下午2:21
 * 订单生成
 */
include_once 'config.php';
$servId = $_REQUEST['serv_id'];
$usrId = $_REQUEST['usr_id'];
$playerId = $_REQUEST['player_id'];
$orderId = $_REQUEST['order_id'];
$coin = $_REQUEST['coin'];
$money = $_REQUEST['money'];
$createTime = $_REQUEST['create_time'];
$goodCode = $_REQUEST['good_code'];
$sign = $_REQUEST['sign'];
$gameId = 8;


$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","niuniu_orderquery_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appId = $arr_key[$gameId]['app_id'];
$appKey = $arr_key[$gameId]['app_key'];

$md5Str = $appId.$servId.$playerId.$orderId.$coin.$money.urldecode($createTime).$appKey;
$mySign = md5($md5Str);

if($sign != $mySign){
    write_log(ROOT_PATH."log","niuniu_orderquery_error_","sign=$sign, str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('err_code'=>1,'desc'=>'sign error.')));
}

$conn = SetConn($servId);
if($conn == false){
    write_log(ROOT_PATH."log","niuniu_orderquery_error_","mysql error. post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('err_code'=>1,'desc'=>'mysql connect error.')));
}

$table = betaSubTable($servId, 'u_player', '1000');
$sql = "select id,account_id from $table where id='$playerId' limit 1";
$query = @mysqli_query($conn,$sql);
$playerInfo = @mysqli_fetch_assoc($query);
if(!isset($playerInfo['account_id']))
    exit(json_encode(array('err_code'=>1, 'desc'=>'没有该用户')));

$accountId = $playerInfo['account_id'];

$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id='$accountId' limit 1";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!isset($result_account['NAME'])){
    write_log(ROOT_PATH."log","niuniu_orderquery_error_", "account is not exist. sql=$sql_account, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('err_code'=>1, 'desc'=>'没有该用户')));
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = '870001';//$result_account['dwFenBaoID'];//防止跨平台充值
    $clienttype = $result_account['clienttype'];
}
$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$cpOrderId = $gameId.'_'.$servId.'_'.$accountId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode,IsUC)";
$sql=$sql." VALUES (142, $accountId,'$PayName','$servId','$money','$cpOrderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '2','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","niuniu_orderquery_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('err_code'=>1, 'desc'=>'生成订单失败')));
}
exit(json_encode(array('err_code'=>0, 'desc'=>$cpOrderId)));