<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","itools_login_info_","post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$sessionid = $_REQUEST['notify_data'];
$game_id = $_REQUEST['game_id'];

if(!$sessionid||!$game_id){
    write_log(ROOT_PATH."log","itools_login_error_log_"," 参数异常, post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}

$sessionid_arr = explode("_", $sessionid);
$uid = $sessionid_arr[0];

$appid = $key_arr[$game_id]['appid'];
$sign = md5("appid=$appid&sessionid=$sessionid");
$data = array();
$url = "https://pay.itools.cn/?r=auth/verify&appid=$appid&sessionid=$sessionid&sign=$sign";
$result = https_post($url,$data);
write_log(ROOT_PATH."log","itools_login_result_log_"," url=$url,result=$result,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = @json_decode($result, true);

if($result_arr['status']=="success"){
    $accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $channel_account=mysqli_real_escape_string($conn,$uid.'@itools');
    $username = rand(10000,99999).time().'@itools';
    $sql = " select id from account where channel_account = '$channel_account'";
    $query=mysqli_query($conn,$sql);
    $result=array();
    if($query){
        $result=mysqli_fetch_assoc($query);
    }else{
        exit('3 0');
    }
    if($result){
        $insert_id = $result['id'];
        exit("0 $insert_id");
    }
    $insert_id='';
    $password=random_common();
    $reg_time=date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
    mysqli_query($conn,$sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_itools_log_"," itools 新登陆 ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    write_log(ROOT_PATH."log","itools_login_result_log_","result=$result,url=".$url.",sql_game=$sql_game, error=".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");

}else{
    write_log(ROOT_PATH."log","itools_login_error_log_"," url=$url,result=$result,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>