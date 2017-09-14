<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/28
 * Time: 上午10:19
 * 获取区服列表
 */
include_once 'config.php';
$conn = SetConn(88);
if($conn == false)
    exit(json_encode(array('err_code'=>1, 'desc'=>'mysql connect error.')));
$sql = "select server_id,server_name from web_game_server where game_id='8' order by server_id asc";
$query = @mysqli_query($conn, $sql);
$info = array();
$i = 0;
while($row = @mysqli_fetch_assoc($query)){
    if(mb_substr($row['server_id'],0,1) != 2)
        continue;
    $info [$i]['serv_id'] = $row['server_id'];
    $info [$i]['serv_name'] = $row['server_name'];
    $i ++;
}
$info [$i]['serv_id'] = '2999';
$info [$i]['serv_name'] = '测试服';
exit(json_encode(array('err_code'=>0,'serv'=>$info)));