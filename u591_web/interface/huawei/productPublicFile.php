<?php
header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey1.pem";
	
	@chmod($filename, 0777);
	@unlink($filename);

$devPubKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkez3voODnrTgSswdvVzS0FmMaFXaB0i+8nblq7vQn4eFbG6rhuqdEwaSOjSfbUrJIOz61b5lhKP8EXrbSEAzJeDkSVdTyTIL3hNX1gaYdig2Rj3jLoRcIqqTEuwNgjWB0tK8iIwr6TZQTDv/usynotnvqv5x1QnJhh4nWjxNQvAWNjxU2cz5K7z+AjbBXCKK4HAv30J99PborWNLARGLpBdP1wjYSQdPlBCuOeSDdBiF8G9tG+DgohMdsFaTb0IU+KnuywTI+esZ4Knt/dz25ScTRx+Ic/lX4f0Xg3kuoa+lVe9Bsw8+BhaWcoJs58HmArrQVM5wYWL++dgl1wAE1wIDAQAB";
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

