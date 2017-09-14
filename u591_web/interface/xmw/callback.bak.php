<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/6
 * Time: 下午6:13
 */
require_once 'common.php';
require_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xmw_callback_all_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:8:{s:6:"serial";s:25:"2016120715235047322761415";s:6:"amount";s:1:"1";s:6:"status";s:7:"success";s:12:"app_order_id";s:30:"8_8027_793997_2016120754535710";s:11:"app_user_id";s:16:"XMWC1081U3748340";s:11:"app_subject";s:17:"8_8027_793997_ios";s:8:"app_ext1";s:34:"8_8027_793997_ios_502788230.066118";s:4:"sign";s:32:"44da15a5afa8534c18cdb0c8436c8ff0";}';
//$_REQUEST = unserialize($str);
$appExt1Arr = explode("_", $_REQUEST['app_ext1']);
if(!isset($appExt1Arr[0]) || !isset($appExt1Arr[1]) || !isset($appExt1Arr[2])){
    write_log(ROOT_PATH."log","xmw_callback_error_","params error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$gameId = $appExt1Arr[0];
$serverId = $appExt1Arr[1];
$accountId = $appExt1Arr[2];
$appId = $key_arr[$gameId]['appkey'];
$appSecret = $key_arr[$gameId]['appsecret'];
try
{
    $purchase = new XMWPurchase($appId, $appSecret);
    // 验证参数签名
    if(!$purchase->checkSign($_REQUEST))
    {
        $msg = new XMWException(XMWException::CODE_PARAM_ERROR, '支付通知签名验证失败.');
        write_log(ROOT_PATH."log","xmw_callback_error_","post=$post,get=$get,msg=$msg, ".date("Y-m-d H:i:s")."\r\n");
        exit('fail');
    }
    // 请求熊猫玩平台服务器 验证订单状态
    $response = $purchase->verifyPurchase($_REQUEST);
    $orderId = $_REQUEST['app_order_id'];
    $payMoney = intval($_REQUEST['amount']);
    // 如果熊猫玩平台服务器返回该订单为成功状态
    if(is_array($response) && array_key_exists('status', $response) && $response['status'] === 'success')
    {
        // 执行游戏逻辑
        // TODO: 执行游戏逻辑，请允许重复接收通知。
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","xmw_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
            exit('fail');
        }
        $sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
        $query_account = @mysqli_query($conn, $sql_account);
        $result_account = @mysqli_fetch_assoc($query_account);

        if(!$result_account['NAME']){
            write_log(ROOT_PATH."log","xmw_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit('FAIL');
        }else{
            $PayName = $result_account['NAME'];
            $dwFenBaoID = $result_account['dwFenBaoID'];
            $clientType = $result_account['clienttype'];
        }

        $conn = SetConn(88);
        //判断订单id情况
        $sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1;";
        $query = @mysqli_query($conn,$sql);
        $result_count = @mysqli_fetch_assoc($query);
        if($result_count['id']){
            write_log(ROOT_PATH."log","xmw_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit('success');
        }
        $Add_Time=date('Y-m-d H:i:s');
        $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
        $sql=$sql." VALUES (131,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType','1')";
        if (mysqli_query($conn,$sql) == False){
            write_log(ROOT_PATH."log","xmw_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
            exit('fail');
        } else {
            WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
            //统计数据
            global $tongjiServer;
            $tjAppId = $tongjiServer[$gameId];
            sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
        }
        exit('success');
    }
    exit('fail');

} catch(XMWException $exception) {
    $errorMsg = $exception->getMessage();
    write_log(ROOT_PATH."log","xmw_callback_error_", "error=$errorMsg, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}