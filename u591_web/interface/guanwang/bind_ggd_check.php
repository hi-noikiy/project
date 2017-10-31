<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/15
 * Time: 下午1:48
 * 越南绑定查询接口
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$array = array();

$array['game_id'] = $_REQUEST['game_id'];
$array['account_id'] = $_REQUEST['account_id'];
$sign = $_REQUEST['sign'];
write_log(ROOT_PATH."log","bind_ggd_check_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

global $key_arr;
$appKey = $key_arr['appKey'];
ksort($array);
$md5Str = http_build_query($array);
$mySign = md5($md5Str.$appKey);

if($sign != $mySign)
    exit(json_encode(array('status'=>2, 'msg'=>'sign error.')));

global $accountServer;
$accountConn = $accountServer[$array['game_id']];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","bind_ggd_check_error_","mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('status'=>1, 'msg'=>'mysql connect error.')));
}
$sql = "select ggp_account from account_ggp where account_id='{$array['account_id']}'";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","bind_ggd_check_error_",$sql."mysql query error. ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('status'=>1, 'msg'=>'mysql query error.')));

}
$result = array();
while ($row = mysqli_fetch_assoc($query)){
    $result[] = $row;
}
$returnArr = array();
if(!empty($result)){
    foreach ($result as $v){
        if(stripos($v['ggp_account'],'@google')){
            $returnArr['google'] = 'google';
        } elseif (stripos($v['ggp_account'],'@fb')){
            $returnArr['fb'] = 'fb';
        }elseif (stripos($v['ggp_account'],'@vk')){
        	$returnArr['vk'] = 'vk';
        }
    }
}
exit(json_encode(array('status'=>0,'msg'=>'success','data'=>$returnArr)));

