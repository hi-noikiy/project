<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/15
 * Time: 下午2:53
 */
$table = 'web_welfare';
include("./inc/config.php");
include("./inc/function.php");
$ip = getIP_front();
write_log("log","welfare_all_log_","ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

$conn = SetConn(88);
if($conn == false)
    exit('connect web mysql error.');
$sql = "select * from $table";
$query = @mysqli_query($conn, $sql);
$data = array();
while ($row = mysqli_fetch_assoc($query)){
    $data[] = $row;
}
$usedDate = date('Ym');
foreach ($data as $v){
    if($v['pay_used_date'] == $usedDate || $v['pay_date'] >$usedDate) {
        continue;
    }
    $serverId = $v['server_id'];
    $playerId = $v['player_id'];
    $playerName = $v['player_name'];
    $amount1 = intval($v['emoney']);
    $flag = checkPlayer($serverId, $playerId, $playerName);
    if($flag == false){
        write_log("log","welfare_error_log_","serverId=$serverId, playerId=$playerId, playerName=$playerName, ".date("Y-m-d H:i:s")."\r\n");
    }
    updateWelfare($v['id'], $table);
    addGood($serverId, $playerId, $amount1);
}

function addGood($serverId, $playerId, $amount1){
    $table = betaSubTable($serverId, 'u_gmtool', 1000);
    $sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2,  award_type3, award_param3, award_amount3,  award_type4, award_param4, award_amount4)";
    $sql .= " values(8, '$serverId', '$playerId' ,'每月福利邮件.', '7', '0', '$amount1', '0', '0', '0', '0', '0', '0', '0', '0', '0') ";
    $conn = SetConn($serverId);
    if($conn == false){
        write_log("log","welfare_error_log_","connect gamel sql error. ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    $query = @mysqli_query($conn, $sql);
    write_log("log","welfare_result_log_","result=$query, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
}

function updateWelfare($id, $table){
    $conn = SetConn(88);
    $usedDate = date('Ym');
    $sql="update $table set pay_used_date='$usedDate' where id='$id'";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","welfare_error_log_", "sql error. sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}

function checkPlayer($serverId, $playerId, $playerName){
    $conn = SetConn($serverId);
    if($conn == false) return false;
    $table = betaSubTable($serverId, 'u_player', '1000');
    $sql = "select id,name from $table where id='$playerId' limit 1";

    if(false == $query = @mysqli_query($conn,$sql))
        return false;
    $rows = @mysqli_fetch_assoc($query);
    if($rows['name'] == $playerName)
        return true;
    return false;
}

