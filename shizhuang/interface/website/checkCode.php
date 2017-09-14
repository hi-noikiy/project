<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 官网注册接口 兼容普通账号注册 手机、邮箱验证码注册
 * 暂时邮箱没有 预留着.
 * ==============================================
 * @date: 2016-5-6
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$code = $_POST['code'];
$username = $_POST['username'];
$gameId = $_POST['game_id'];
$params = array(
    'username',
    'game_id',
    'code'
);
for ($i = 0; $i< count($params); $i++){
    if (!isset($_POST[$params[$i]])) {
        exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
    } else {
        if(empty($_POST[$params[$i]]))
            exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
    }
}
$conn = SetConn('88');
$sql = "select id,code from web_message where username='$username' and game_id='$gameId' order by id desc limit 1";
if(false == $query = mysqli_query($conn,$sql))
    exit(json_encode(array('status'=>1, 'msg'=>'web sql error.')));
$rs = @mysqli_fetch_assoc($query);
if(empty($rs))
    exit(json_encode(array('status'=>1, 'msg'=>'code does not exist.')));
$nowTime = time();
//if($nowTime-$rs['addtime'] > 900)
//    exit(json_encode(array('status'=>1, 'msg'=>'code is invalid.')));
if($rs['code'] != $code)
    exit(json_encode(array('status'=>1, 'msg'=>'code is error.')));
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>