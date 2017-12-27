<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 邮箱发送
* ==============================================
* @date: 2016-7-20
* @author: luoxue
* @version:
*/
include_once 'config.php';

$post = serialize($_POST);
write_log(ROOT_PATH."log","mail_sent_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$email = $_POST['email'];
$gameId = $_POST['game_id'];
$sign = $_POST['sign'];

$params = array(
		'email',
		'game_id',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
	}
}
if (!eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$',$email))
	exit(json_encode(array('status'=>1, 'msg'=>'email format error.')));
$appKey = $key_arr['appKey'];
if(!$appKey)
	exit(json_encode(array('status'=>1, 'msg'=>'appkey error.')));
$array['email'] = $email;
$array['game_id'] = $gameId;
ksort($array);
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

$conn = SetConn('88');
$sql = "select * from web_message where game_id='$gameId' and username='$email' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","mail_sent_error_log_","sql error.sql=$sql, ".mysqli_error($conn)." , ".date('Y-m-d H:i:s')."\r\n");
	//write_log(ROOT_PATH.'log','bindall_login_error_',"sql error.sql=$insert_sql, ".mysqli_error($conn)." , ".date('Y-m-d H:i:s')."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
}


$rs = @mysqli_fetch_assoc($query);
$nowTime = time();
if(!empty($rs['addtime'])){
	if($nowTime-$rs['addtime'] < 60)
		exit(json_encode(array('status'=>1, 'msg'=>'can not repeatedly transmitted within 60 seconds.')));
}
$code = rand(1000,9999);
$iSql= "insert into web_message(game_id, username, code, addtime) values('$gameId', '$email', '$code', '$nowTime')";
if(false == mysqli_query($conn,$iSql)){
	write_log(ROOT_PATH."log","mail_sent_error_log_","sql error.sql=$iSql, ".mysqli_error($conn)." , ".date('Y-m-d H:i:s')."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
}
	


$title = 'Логин письмо от компании Hainiu Seacow';
$message = "Дорогой $email
Код регистрации в Емайл: $code
Это код действуется в течении 15 минут!

Совет：
* Это письмо авто-отправляется через систему,  мы не будем отвечать на ваш вопрос онлайн. 
* Вы сможете связаться с нами через 0591-87678008. Спасибо вам! 

Это объяснение принадлежит Fuzhou Seacow технической компании";

$sendMail = SendMail($email, $title, $message);
write_log(ROOT_PATH."log","mail_sent_info_log_","post=$post, $sendMail, ".date("Y-m-d H:i:s")."\r\n");
if($sendMail)
	exit(json_encode(array('status'=>0, 'msg'=>'success')));
else{
	write_log(ROOT_PATH."log","mail_sent_error_log_","post=$post, $sendMail, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
}
function SendMail($address,$title,$message){
	include_once 'PHPMailer/class.phpmailer.php';
	$mail=new PHPMailer();
	// 设置PHPMailer使用SMTP服务器发送Email
	$mail->IsSMTP();
	// 设置邮件的字符编码，若不指定，则为'UTF-8'
	$mail->CharSet='UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
	$mail->AddAddress($address);
	// 设置邮件正文
	$mail->Body=$message;
	// 设置邮件头的From字段。
	$mail->From='pokemon_vs@163.com';
	// 设置发件人名字
	$mail->FromName='Seacowgame';
	// 设置邮件标题
	$mail->Subject=$title;
	// 设置SMTP服务器。
	$mail->Host='smtp.163.com';
	// 设置为"需要验证"
	$mail->SMTPAuth=true;
	// 设置用户名和密码。
	$mail->Username='pokemon_vs@163.com';
	$mail->Password='hainiu965';
	// 发送邮件。
	return($mail->Send());
}