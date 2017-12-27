<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","49you_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$packageName = $_REQUEST['user_token'];
$gameId = $_REQUEST['game_id'];
$access_token = $_REQUEST['uid'];
if(!$access_token || !$gameId || !$packageName){
    write_log(ROOT_PATH."log","49you_login_error_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$datas = explode('_', $packageName);
$uid = $datas[0];
$token = $datas[1];
$time = $datas[2];
$type = isset($datas[3])?$datas[3]:'android';
global $key_arr;
$appid = $key_arr[$gameId][$type]['appid'];
$secret = $key_arr[$gameId][$type]['appSecret'];
$signstr = $appid.$uid.$token.$time.$secret;
$sign = strtolower(md5($signstr));
$datastr = "appid=$appid&uid=$uid&token=$token&time=$time&sign=$sign";
$url = "http://$appid.checklogin.sdk.49app.com";
$rdata = http_post_data($url, $datastr);

write_log(ROOT_PATH."log","49you_result_log_","$url,$datastr,result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    if('success' == $rdata){
    	$memId = $uid;
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","49you_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@49you');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","49you_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $result = @mysqli_fetch_assoc($query);
        if($result){
            $insert_id = $result['id'];
            exit("0 $insert_id");
        }
        $insert_id = '';
        $password = random_common();
        $reg_time = date("ymdHi");
        $sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
        @mysqli_query($conn, $sql_game);
        $insert_id = @mysqli_insert_id($conn);
        if($insert_id){
            write_log(ROOT_PATH."log","new_account_49you_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","49you_login_error_","result=". json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');

function http_post_data($url, $data_string) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$r = curl_exec($curl);
	curl_close($curl);
	return $r;
}

function http_post_data1($url, $data_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	$response = curl_exec($ch);

	return $response;
}