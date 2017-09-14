<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/5/16 上午10:59
 * @desc:
 * @param:
 * @return:
 */

include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","1pay_order_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$serverId = $_REQUEST['server_id'];
$accountId = $_REQUEST['account_id'];

if(!$serverId || !$accountId) {
    return false;
}

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","1pay_order_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
    return false;
}
$sql = "select CPID,OrderID,PayMoney,Add_Time,rpCode,CardNO,CardPwd from web_pay_log where ServerID='$serverId' and PayID='$accountId' order by Add_Time desc;";
$query = @mysqli_query($conn, $sql);
$arr = array();
while ($row = mysqli_fetch_array($query)){
    $arr[] = $row;
}
exit(json_encode($arr));