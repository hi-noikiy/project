<?php
header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";
	
	@chmod($filename, 0777);
	@unlink($filename);

$devPubKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjxIdCZv3NALmKmP0F7+OysvvijphsxRD++BhS7Sl97YhWRemVjpMSBaA/w6DDGCZrpIQdolyDU3JUqlkLfyoZE/V9qzroJtbcbwinOWvOWD/EcOKsKt8i2AWXDzwkktpHxTJPe8P4wtfYX4chD+3wh9I3NePsQZnilnrgBxVmNBU2xpvU+vFmMOev93AR6zzn/YJegzopgzYz/+35qGZ/3XD0bUqy93iHLYsX0UEuUa+Q2+WKa1INmDwrVl9l6Su35dDekBgyjM8P+8GXDOKRdvsiFbT+IMPDWIod7zSiMM9qkXmhD340k6zr0rOkb2cEWvcZUgL6M24eSSSMfecVwIDAQAB";
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

