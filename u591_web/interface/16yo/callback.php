<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/3
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';

write_log(ROOT_PATH."log","16yo_callback_info_","post=$post, get=$get, data=$urldata, ".date("Y-m-d H:i:s")."\r\n");

$success = "SUCCESS";
$fail = "FAILURE";
// 缺少参数
if (empty($urldata)) {
    exit($fail);
}

$urldata = get_object_vars(json_decode($urldata));
$order_id = isset($urldata['order_id']) ? $urldata['order_id'] : '';
$mem_id = isset($urldata['mem_id']) ? $urldata['mem_id'] : '';
$app_id = isset($urldata['app_id']) ? intval($urldata['app_id']) : 0;
$money = isset($urldata['money']) ? $urldata['money'] : 0.00;
$order_status = isset($urldata['order_status']) ? $urldata['order_status'] : '';
$paytime = isset($urldata['paytime']) ? intval($urldata['paytime']) : 0;
$attach = isset($urldata['attach']) ? $urldata['attach'] : ''; //CP扩展参数
$sign = isset($urldata['sign']) ? $urldata['sign'] : ''; // 签名
//money 参数为小数点后两位
$money = number_format($money,2);
//1 校验参数合法性
if (empty($urldata) || empty($order_id) || empty($mem_id) || empty($app_id) || empty($money)
    || empty($order_status) || empty($paytime) || empty($attach) || empty($sign)){
    //CP添加自定义参数合法检测
    exit($fail);

}
$attachArr = explode('_', $attach);
$gameId = $attachArr[0];
$serverId = $attachArr[1];
$accountId = $attachArr[2];

global $key_arr;
$appkey = $key_arr[$gameId]['appkey'];

$paramstr = "order_id=".$order_id."&mem_id=".$mem_id."&app_id=".$app_id."&money=".$money."&order_status=".$order_status."&paytime=".$paytime."&attach=".$attach."&app_key=".$appkey;
$verrifysign = md5($paramstr);
if (0 == strcasecmp($verrifysign, $sign)){
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId' limit 1";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","16yo_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit($fail);
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $sql = "select rpCode from web_pay_log where OrderID = '$order_id' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_array($query);

    if($result['rpCode']==1 || $result['rpCode']==10){
        write_log(ROOT_PATH."log","16yo_callback_error_", "order is exist.orderId=$order_id, ".date("Y-m-d H:i:s")."\r\n");
        exit($success);
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (148, $accountId,'$PayName','$serverId','$money','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";

    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","16yo_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit($fail);
    }
    WriteCard_money(1,$serverId, $money,$accountId, $order_id);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$money,$order_id,1,$tjAppId);
    exit($success);
}
exit($fail);