<?php
include_once 'config.php';


//$sql = "insert into u_gmtool006(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2, award_type3, award_param3, award_amount3, award_type4, award_param4, award_amount4) values(8, '5006', '1036899' ,'补发双倍未领取', '7', '0', '5832', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
//$query = mysqli_query($conn, $sql);
//var_dump($query);
//echo '<br>';


//$conn = SetConn(5005);
//$sql = "insert into u_gmtool005(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2, award_type3, award_param3, award_amount3, award_type4, award_param4, award_amount4) values(8, '5005', '1035925' ,'补发双倍未领取', '7', '0', '5832', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
//$query = mysqli_query($conn, $sql);
//var_dump($query);
//echo '<br>';
//
//
//$conn = SetConn(5003);
//$sql = "insert into u_gmtool003(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2, award_type3, award_param3, award_amount3, award_type4, award_param4, award_amount4) values(8, '5003', '1016656' ,'补发双倍未领取', '7', '0', '2952', '0', '0', '0', '0', '0', '0', '0', '0', '0');,";
//$query = mysqli_query($conn, $sql);
//var_dump($query);
//echo '<br>';
//
//$conn = SetConn(5002);
//$sql = "insert into u_gmtool002(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2, award_type3, award_param3, award_amount3, award_type4, award_param4, award_amount4) values(8, '5002', '1012825' ,'补发双倍未领取', '7', '0', '1152', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
//$query = mysqli_query($conn, $sql);
//var_dump($query);
//echo '<br>';


$conn = SetConn(85);
$sql = "select id,NAME,channel_account from account where id='1710126' limit 1;";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);

print_r($row);
//$id = intval($row['id']);
//$name = $row['channel_account'];
//$sql = "update account set NAME='$name' where id='$id';";
//$query = mysqli_query($conn, $sql);

//var_dump(mysqli_error($conn));

//var_dump($query);

//echo '<br>';
//$sql = "update account set NAME='49b6509ba1a7b37124d3ee9387fd0c0d@kuaifa',channel_account='49b6509ba1a7b37124d3ee9387fd0c0d@kuaifa' where id='189426';";
//$query = mysqli_query($conn, $sql);


//var_dump($query);
/*set_time_limit(600); //cli无效
$conn = SetConn(88);
$sql = "select PayID,ServerID,PayMoney from web_pay_log where rpCode=1 and Add_Time >='2017-01-27 00:00:00' and Add_Time<='2017-01-27 08:10:00' and PayMoney<=6 order by PayMoney desc";
$query = mysqli_query($conn, $sql);
$result = array();
while ($rows1 = mysqli_fetch_array($query)){
    $result[] = $rows1;
}
$sql2 = "select PayID,ServerID,PayMoney from web_pay_log where rpCode=1 and Add_Time >='2017-01-27 08:05:00' and Add_Time<='2017-02-06 18:00:00' and PayMoney<=6";
$query2 = mysqli_query($conn, $sql2);
$result2 = array();
while ($rows2 = mysqli_fetch_array($query2)){
    $result2[] = $rows2;
}
foreach ($result as $v){
    $serverId = $v['ServerID'];
    $accountId = $v['PayID'];
    $payMoney = $v['PayMoney'];
    $status = false;
    foreach ($result2 as $v2){
        if($accountId == $v2['PayID'] && $payMoney == $v2['PayMoney']){
            $status = true;
            break;
        }
    }
    if($status == false) {
        $info = getPlayer($serverId, $accountId);
        $playerId = $info['id'];
        $playerName = $info['name'];
        if($playerId)
            write_log(ROOT_PATH . "log", "player_info_", "$playerId  $playerName $serverId $payMoney \r\n");
    }
}
exit('success');
function subTable($accountId, $table, $sum){
    $suffix = $accountId%$sum;
    $s = sprintf('%03d', $suffix);
    return $table.$s;
}

function getPlayer($serverId, $accountId){
    $playerTable = subTable($serverId, 'u_player', 1000);
    $sql = "select id,name,serverid from $playerTable where account_id='$accountId' and serverid='$serverId'  limit 1";

    $conn = SetConn($serverId);
    $query = @mysqli_query($conn,$sql);
    $rs = @mysqli_fetch_array($query);
    return $rs;
}
*/

/*$table = betaSubTable(8001, 'u_player', '1000');
$sql = "select * from $table where serverid='8001' limit 1";
$conn = SetConn(8001);

$query = @mysqli_query($conn,$sql);
$playerList = @mysqli_fetch_assoc($query);
echo '<pre>';
print_r($playerList);*/
