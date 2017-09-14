<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/22
 * Time: 下午7:48
 *
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","play800_android_orderId_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];
$rolename = $_REQUEST['rolename'];
$site = $_REQUEST['site'];
$key = $_REQUEST['key'];
$channelId = $_REQUEST['channelId'];
$timeStamp = $_REQUEST['timeStamp'];
$sign = $_REQUEST['sign'];
$gameId = 8;

$md5Str = $gid.$sid.$rolename.$site.$key.$channelId.$timeStamp;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","play800_android_orderId_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'sign error.')));
}
$table = betaSubTable($sid, 'u_player', '1000');

$conn = SetConn($sid);

$sql = "select id,account_id,name,level from $table where serverid='$sid' and name='$rolename' limit 1;";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","play800_android_orderId_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
}
$rs = @mysqli_fetch_assoc($query);
if($rs){
    $accountId = $rs['account_id'];
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype,channel_account from account where id='$accountId' limit 1";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!isset($result_account['NAME'])){
        write_log(ROOT_PATH."log","play800_android_orderId_error_", "account is not exist. sql=$sql_account, ".date("Y-m-d H:i:s")."\r\n");
        exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
    }else{
        //$PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];//防止跨平台充值
        //$clienttype = $result_account['clienttype'];
        $channelAccountArr = explode('@',$result_account['channel_account']);
    }
    global $fenbao_arr;
    $site = $fenbao_arr[$dwFenBaoID];

    $servId = $sid;
    $number = str_replace('kdyg_ios','', $site);
    $type = 'ios'.($number+1);
    $gift = 0;
    $lev = intval($rs['lev']);
    $cpOrderId = $gameId.'_'.$servId.'_'.$accountId.'_'.$type.'_'.$gift.'_'.$lev.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $roleId = $rs['id'];

    exit(json_encode(
        array(
            'code'=>'success',
            'cp_order_id'=>$cpOrderId,
            'roleid'=>$roleId,
            'rolename'=>$rolename,
            'uid'=>$channelAccountArr[0],
            'fenbao_id'=>$dwFenBaoID,
            'fenbao_site'=>$site,
        )
    ));
} else {
    exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}