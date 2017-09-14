<?php
/*
 * 使用说明
 */
// 参数说明(发送到, 邮件主题, 邮件内容, 用户名, 附加信息)
//smtp_mail('yourmail@cgsir.com', '欢迎来到cgsir.com！', 'NULL', 'cgsir.com', 'username');


/*
 * 开始
 * phpmailer类路径
 */
//$email_class_path=str_replace('\\function\\','\\class\\phpmailer\\',dirname(__FILE__).'\\');
//$email_class_path=str_replace('/function/','/class/phpmailer/',dirname(__FILE__).'/');
$email_class_path=dirname(__FILE__).'/../class/phpmailer/';
require($email_class_path."class.phpmailer.php");  

function smtp_mail ( $sendto_email, $subject, $body, $user_name='', $extra_hdrs=null) {
	global $rootpath;
	$conf = include($rootpath.'conf/email.conf.php');
	$smtp = $conf['smtp'];
	$user = $conf['user'];
	$pass = $conf['pass'];
	
	$host = $conf['host'];
	$form = $conf['form'];
	$name = $conf['name'];
	
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); // send via SMTP 
	
	$mail->Host = $smtp; // SMTP servers 
	
	$mail->SMTPAuth = true; // turn on SMTP authentication 
	
	$mail->Username = $user; // SMTP username 注意：普通邮件认证不需要加 @域名 
	
	$mail->Password = $pass; // SMTP password 
	
	$mail->From = $form; // 发件人邮箱
	$mail->FromName = $name; // 发件人 
	
	$mail->CharSet = "utf-8"; // 这里指定字符集！
	
	$mail->Encoding = "base64"; 
	$mail->AddAddress($sendto_email,"username"); // 收件人邮箱和姓名 www~phperz~com 
	
	$mail->AddReplyTo($form,$host); 
	//$mail->WordWrap = 50; // set word wrap 
	
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment 
	
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); 
	
	// 邮件主题
	$mail->Subject = $subject;
	//是否html
	$mail->IsHTML(true); // send as HTML 
	// 邮件内容 
//	$mail->Body = 	'
//					<html><head>
//					<meta http-equiv="Content-Language" content="zh-cn">
//					<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
//					</head>
//					<body>
//					        欢迎来到<a href="http://www.lele3.com">http://www.lele3.com</a> <br /><br />
//					感谢您注册为本站会员！<br /><br />
//					</body>
//					</html>
//					'; 
	$mail->Body = $body;
	
	$mail->AltBody ="text/html"; 
	if(!$mail->Send()){ 
	  $msg= "邮件发送有误 <p>"; 
	  $msg= "邮件错误信息: " . $mail->ErrorInfo; 
	} else {
	  $msg= "$user_name 邮件发送成功!<br />"; 
	}
	return $msg; 
} 

function send_mail_pass($sendto_email, $newpass){
	global $rootpath;
	$body='您的新密码是:'.$newpass;
	$subject='您修改了密码';
	return smtp_mail($sendto_email, $subject, $body);
}

?>