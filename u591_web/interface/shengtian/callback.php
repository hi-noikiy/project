<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/20
 * Time: 上午11:09
 */
include_once 'config.php';
include_once "src/Notify.php";
$post = serialize($_POST);
$get = serialize($_GET);
//$str = 'a:9:{s:4:"sign";s:32:"782ba5981e066100ae8057fccda4cb09";s:4:"time";s:10:"1490086094";s:8:"gameCode";s:4:"poke";s:11:"gameOrderId";s:40:"8_8075_3751772_android_0_1_1490084502201";s:6:"userId";s:8:"64038022";s:5:"money";s:3:"1.0";s:8:"serverId";s:2:"s1";s:3:"ext";s:26:"8_8075_3751772_android_0_1";s:7:"orderId";s:24:"20170321162213F2R3FHZ7EL";}';
//$_REQUEST = unserialize($str);
write_log(ROOT_PATH."log","shengtian_callback_info_", "post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
// 获取post请求数据
$datas['orderId'] = $_REQUEST['orderId'];
$datas['userId'] = $_REQUEST['userId'];
$datas['money'] = $_REQUEST['money'];
$datas['gameCode'] = $_REQUEST['gameCode'];
$datas['serverId'] = $_REQUEST['serverId'];
$datas['gameOrderId'] = $_REQUEST['gameOrderId'];
$datas['ext'] = $_REQUEST['ext'];
$datas['sign'] = $_REQUEST['sign'];
$datas['time'] = $_REQUEST['time'];

if (empty($datas['orderId']) || empty($datas['userId']) || empty($datas['money']) || empty($datas['gameCode']) || empty($datas['serverId']) || empty($datas['sign']) || empty($datas['time'])) {
    write_log(ROOT_PATH."log","shengtian_callback_error_", "params error. post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    echo json_encode(array('errno' => "-1", 'errmsg' => '参数不全', 'data' => ''));
    exit;
}

$extArr = explode('_', $datas['ext']);
$gameId = $extArr[0];
$serverId = $extArr[1];
$accountId = $extArr[2];
$type = $extArr[3];
$gift = $extArr[4];

global $key_arr;
$appKey = $key_arr[$gameId][$type]['appKey'];


$notify = new Notify();
$notify->setAppKey($appKey);
$notify->setOrderId($datas['orderId']);
$notify->setUserId($datas['userId']);
$notify->setCoins($datas['money']);
$notify->setMoney($datas['money']);
$notify->setGameCode($datas['gameCode']);
$notify->setServerId($datas['serverId']);
$notify->setGameOrderId($datas['gameOrderId']);
$notify->setExt($datas['ext']);
$notify->setSign($datas['sign']);
$notify->setTime($datas['time']);
// 检查签名
if ($notify->checkSign()) {
    // Do your business logic...
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","shengtian_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
        echo json_encode(array('errno' => "-1", 'errmsg' => 'mysql connect error.', 'data' => ''));
        exit();
    }
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","shengtian_callback_error_", "account not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        echo json_encode(array('errno' => "-1", 'errmsg' => 'account not exist.', 'data' => ''));
        exit();
    }
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];

    $conn = SetConn(88);
    //判断订单id情况
    $payMoney = $notify->getMoney();
    $orderId  = $notify->getOrderId();
    $sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
    $query = @mysqli_query($conn,$sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","shengtian_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        echo json_encode(array(
            'errno' => 1,
            'errmsg' => '签名校验成功',
            'data' => $datas,
        ));exit;
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (150,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '1')";
    if (mysqli_query($conn,$sql) == false){
        write_log(ROOT_PATH."log","shengtian_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        echo json_encode(array('errno' => "-1", 'errmsg' => 'insert order error.', 'data' => ''));
        exit();
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$gift);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    echo json_encode(array(
        'errno' => 1,
        'errmsg' => '签名校验成功',
        'data' => $datas,
    ));exit;
} else {
    $signStr = $notify->getSignStr();
    $mySign = $notify->makeSign();
    $sign = $notify->getSign();
    write_log(ROOT_PATH."log","shengtian_callback_error_","sign error, signStr=$signStr, mySign=$mySign,sign=$sign, ".date("Y-m-d H:i:s")."\r\n");

    echo json_encode(array(
        'errno' => 0,
        'errmsg' => '签名校验失败',
        'data' => '',
    ));exit;
}
