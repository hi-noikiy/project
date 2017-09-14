<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/28
 * Time: 上午10:19
 * 获取区服列表
 */
include_once 'config.php';
$gameId = $_REQUEST['game_id'];
$sign = $_REQUEST['sign'];
$params = array(

    'game_id',
    'sign',
);
for ($i = 0; $i< count($params); $i++){
    if (!isset($_REQUEST[$params[$i]])) {
        exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
    } else {
        if(empty($_REQUEST[$params[$i]]))
            exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
    }
}

$appKey = $key_arr['appKey'];
$array = array();
$array['game_id'] = $gameId;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
    exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
$conn = SetConn(88);
if($conn == false)
    exit(json_encode(array('status'=>1, 'msg'=>'mysql connect error.')));
$sql = "select server_id,server_name from web_game_server where game_id='$gameId' order by server_id asc";
$query = @mysqli_query($conn, $sql);
$info = array();
while($row = @mysqli_fetch_assoc($query)){
    $info [] = $row;
}
exit(json_encode(array('status'=>0, 'msg'=>'success', 'data'=>$info)));