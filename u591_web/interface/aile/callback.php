<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午7:38
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","aile_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:11:{s:6:"app_id";s:6:"362603";s:11:"cp_order_id";s:38:"8_8068_3265950_android_0_1488274424109";s:6:"mem_id";s:5:"72200";s:8:"order_id";s:23:"14882744270901522000001";s:12:"order_status";s:1:"2";s:8:"pay_time";s:10:"1488274427";s:10:"product_id";s:1:"1";s:12:"product_name";s:8:"60钻石";s:13:"product_price";s:1:"1";s:4:"sign";s:32:"31b20ae6d137857eb1acf9ee7129b709";s:3:"ext";s:24:"8_8068_3265950_android_0";}';
//$_REQUEST = unserialize($str);
$success = "SUCCESS";
$fail = "FAILURE";
// 缺少参数
if (empty($_REQUEST)) {
    write_log(ROOT_PATH."log","aile_callback_error_","params error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}
$cp_order_id = $_REQUEST['cp_order_id'];
$order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : '';
$mem_id = isset($_REQUEST['mem_id']) ? $_REQUEST['mem_id'] : '';
$app_id = isset($_REQUEST['app_id']) ? intval($_REQUEST['app_id']) : 0;

$product_id = $_REQUEST['product_id'];
$product_name = $_REQUEST['product_name'];
$product_price = isset($_REQUEST['product_price']) ? $_REQUEST['product_price'] : 0.00;
$order_status = isset($_REQUEST['order_status']) ? $_REQUEST['order_status'] : '';
$paytime = isset($_REQUEST['pay_time']) ? intval($_REQUEST['pay_time']) : 0;
$ext = isset($_REQUEST['ext']) ? $_REQUEST['ext'] : ''; //CP扩展参数
$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : ''; // 签名

$money = $product_price;
//1 校验参数合法性
if (empty($order_id) || empty($mem_id) || empty($app_id) || empty($money)
    || empty($order_status) || empty($paytime) || empty($ext) || empty($sign)){
    //CP添加自定义参数合法检测
    write_log(ROOT_PATH."log","aile_callback_error_","params2 error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}
$attachArr = explode('_', $ext);
$gameId = $attachArr[0];
$serverId = $attachArr[1];
$accountId = $attachArr[2];
$isgoods = $attachArr[4];
global $key_arr;
$appkey = $key_arr[$gameId]['appKey'];
$paramstr = "app_id=$app_id&cp_order_id=".urlencode($cp_order_id)."&mem_id=$mem_id&order_id=$order_id&order_status=$order_status&pay_time=$paytime&";
$paramstr .="product_id=".urlencode($product_id)."&product_name=".urlencode($product_name)."&product_price=".urlencode($product_price)."&app_key=$appkey";

$verrifysign = md5($paramstr);
if (0 != strcasecmp($verrifysign, $sign)){
    write_log(ROOT_PATH."log","aile_callback_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","aile_callback_error_","account mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","aile_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","aile_callback_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}
$sql = "select rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    write_log(ROOT_PATH."log","aile_callback_error_","order id exist. orderid=$order_id, ".date("Y-m-d H:i:s")."\r\n");
    exit($success);
}

$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
$sql=$sql." VALUES (145, $accountId,'$PayName','$serverId','$money','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";

if (mysqli_query($conn,$sql) == false){
    write_log(ROOT_PATH."log","aile_callback_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit($fail);
}

WriteCard_money(1,$serverId, $money,$accountId, $order_id,8,0,0,$isgoods);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$money,$order_id,1,$tjAppId);
exit($success);