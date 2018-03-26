<?php
header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";
	
	@chmod($filename, 0777);
	@unlink($filename);

$devPubKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkf62tdKJiRWvxZTC2y6TwCFEf+wYET4GyMsUn/4cvdBmSKS03mb29uv6OXj4JUsOifNWMkGrGZ7RC4D9FHGA0A2SaK2NDjyHtINVqkV/ocMqZWvHpvPzYHIl316DObnDYx/tTIWEi7GaCBVp9zwHLORndFZoeMHiQ7tvjW0E4aAfmN36BXEed1RMVZSjjkBZiLN+8nKUJEfI7n3XQJWxytIxfETarurEVCvmj6nIGbTzI04R3pSC0E2XPvCktPFLcDHRhrSIoqbFVjru5p7E3VIjfIwHi3oCesKX0Ptghyawt4jlKu+U0iVx6Vy/hev0tKhT4XW4U3PvnHcYlC2fqwIDAQAB";
$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
$end_public_key = "-----END PUBLIC KEY-----\r\n";
/*$begin_public_key = "-----BEGIN RSA PRIVATE KEY-----\r\n";
$end_public_key = "-----END RSA PRIVATE KEY-----\r\n";*/


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

