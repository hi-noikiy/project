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
write_log(ROOT_PATH."log","vivo_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","vivo_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$data['authtoken'] = $userToken;  //获取的user_token
$url = "https://usrsys.vivo.com.cn/sdk/user/auth.do";
$rdata = https_post($url, $data);//return_code是http状态码
write_log(ROOT_PATH."log","vivo_result_log_","result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $tdata = json_decode($rdata,true);
    if('0' == $tdata['retcode']){
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","vivo_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$tdata['data']['openid'].'@vivo');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","vivo_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_vivo_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }else{
        	write_log(ROOT_PATH."log","vivo_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        }
    }
}
write_log(ROOT_PATH."log","vivo_login_error_","result=".$rdata.date("Y-m-d H:i:s")."\r\n");
exit('4 0');
