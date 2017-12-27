<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","dl_callback_info_log_","post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$result = $_GET['result'];
$money = $_GET['money'];
$order = $_GET['order'];
$cpOrder = $_GET['cpOrder'];
$mid = $_GET['mid'];
$time = $_GET['time'];
$signature = $_GET['signature'];
$ext = $_GET['ext'];
$ext_arr = explode("_", $ext);
$game_id = intval($ext_arr[0]);
$server_id = intval($ext_arr[1]);
$account_id = intval($ext_arr[2]);
$isgoods = intval($ext_arr[4]);
global $key_arr;
$payment_key = $key_arr[$game_id]['payment_key'];
$addCpOrder = isset($cpOrder) ? "&cpOrder=$cpOrder" : '';
$my_sign_str = "order=$order&money=$money&mid=$mid&time=$time&result=$result".$addCpOrder."&ext=$ext&key=$payment_key";
$my_sign = strtolower(md5($my_sign_str));
if($signature!=$my_sign){
    write_log(ROOT_PATH."log","dl_callback_error_", "sign error. get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit();
}
if(!$game_id||!$server_id||!$account_id){
    write_log(ROOT_PATH."log","dl_callback_error_",  "param error. get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit();
}
//获取账号信息
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id='$account_id' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","dl_callback_error_", "account not exist. get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit();//账号不存在
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}

$order_id = $order;
$PayMoney = intval($money);
$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query=mysqli_query($conn,$sql);
$result_count=mysqli_fetch_assoc($query);
if($result_count['id']){
    $str= "订单已存在 ".date("Y-m-d H:i:s")."\r\n";
    write_log(ROOT_PATH."log","dl_callback_error_",$str);
    exit("success");//订单已存在
}

$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
$sql=$sql." VALUES (21,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","dl_callback_error_", "sql=$sql,  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit;
}
if($result==1){
	$isPay = 0;
    $rpCode =1;
    dlPayLog($order_id,$rpCode,$PayMoney);//更新充值记录
    WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
}else{
	$isPay = 1;
    $rpCode =2;
    dlPayLog($order_id,$rpCode,$PayMoney);//更新充值记录
    WritePayMsg(1,$server_id,$account_id,$order_id,$PayMoney,$game_id);
}
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$game_id];
sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
exit("success");

function dlPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
    $sql=$sql." where OrderID='$OrderID'";
    //echo $sql;
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","dl_callback_error_", "sql error. sql=$sql,  ".date("Y-m-d H:i:s")."\r\n");
        exit;
    }
}
?>