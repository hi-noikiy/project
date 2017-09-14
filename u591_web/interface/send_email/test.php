<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 11-4/0004
 * Time: 20:39
 */
include 'mail.php';
//use Nette\Mail\Message;
//use Nette\Mail\SendmailMailer;

$mail = Mail::to(array('10001@qq.com'))
    ->from('test <test@qq.com>')
    ->title('Me!')
    ->content('<h1>Hello~~</h1>');

$mailer = new Nette\Mail\SmtpMailer($mail->config);
try {
    $mailer->send($mail);
} catch(Exception $e) {
    echo $e->getMessage();
}
