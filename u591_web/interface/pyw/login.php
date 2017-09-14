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
write_log(ROOT_PATH."log","pyw_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","pyw_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$data['uid'] = $userToken;      
$data['tid'] = time().mt_rand(100000,999999);
$data['token'] = $appUid;  //获取的user_token
$url = "http://api.sdk.pyw.cn/Cpapi/check";
$jsondata = json_encode($data);
$rdata = http_post_data1($url, $jsondata);//return_code是http状态码
write_log(ROOT_PATH."log","pyw_result_log_","$url,$jsondata,result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = json_decode($rdata,true);
    if('200' == $rdata['ack']){
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","pyw_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$userToken.'@pyw');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","pyw_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_pyw_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","pyw_login_error_","result=".$rdata.date("Y-m-d H:i:s")."\r\n");
exit('4 0');

function http_post_data($url, $data_string) {  
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_POST, 1);  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
        "Content-Type: application/json; charset=utf-8",  
        "Content-Length: " . strlen($data_string))  
    );  
    ob_start();  
    curl_exec($ch);  
    $return_content = ob_get_contents();  
    ob_end_clean();  
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    return array($return_code, $return_content);  
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