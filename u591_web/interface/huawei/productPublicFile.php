<?php
header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey1.pem";
	
	@chmod($filename, 0777);
	@unlink($filename);

$devPubKey = "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAOAZ4rFUbVmfeoLvy7Fv6rQfo8Mqg7mEZnF5v0jq8MNOQ1YFqISUFoMQM6Z+zbJYJUFWBGv8Qd0R/js24wrExOECAwEAAQ==";
$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
$end_public_key = "-----END PUBLIC KEY-----\r\n";


$fp = fopen($filename,'ab');
fwrite($fp,$begin_public_key,strlen($begin_public_key)); 

$raw = strlen($devPubKey)/64;
$index = 0;
while($index <= $raw )
{
	$line = substr($devPubKey,$index*64,64)."\r\n";
	if(strlen(trim($line)) > 0)
	fwrite($fp,$line,strlen($line)); 
	$index++;
}
fwrite($fp,$end_public_key,strlen($end_public_key)); 
fclose($fp);
?>

