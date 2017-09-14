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
if($conn == false){
    write_log(ROOT_PATH."log","quickgame_server_error_","mysql connect error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error', 'message'=>'mysql connect error.')));
}
$sql = "select server_id,server_name from web_game_server where game_id='8' order by server_id asc";
$query = @mysqli_query($conn, $sql);
$info = array();
$i = 0;
while($row = @mysqli_fetch_assoc($query)){
    if(mb_substr($row['server_id'],0,1) != 5)
        continue;
    $info[] = array('id'=>$row['server_id'],'name'=>$row['server_name']);
}
exit(json_encode($info));