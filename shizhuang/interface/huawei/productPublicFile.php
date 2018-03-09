<?php
header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";
	
	@chmod($filename, 0777);
	@unlink($filename);

$devPubKey = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCLh0g5DqI3s6bQxdIWFUtrQHy1Iu9QRN9L9qqHKhBaE5g/CzU1wcAhna03oxuNu6bUoZFWK5S0O9UGOhQXhlsImKKMjnYa4qk1EO79uVkf7ymThBRP32uV6MiiFQ+879iWSfjjXG+yF9ZqxK+UsSxIFCpmhL0ee+yD+pU5idoMdZrtLh/pl9bu1DCMEEa1sXR/XaNzAJzAHTTdvo8DHw9qcu7/SfFMp/Stz5e0s7WUB9ZbY9MZpjVJvXjon3E4ZHKero3EUbtaE+4zxBRex3h3xcxINWrmI8EUtL0Y7XLQGRzyLBL/4xETPGvTMcZZBYliQ503Ntf8G0KpAWt4QmO3AgMBAAECggEAdDlxKM4OxDVqzFcsI+cDFsj12HDMReHI218SN1sLmezekg1E+o10/Mt6IXFFn29jjZU0GkJdMTDxxqkUWXA6XlO+ezSkHssVxbVgTotZOSegS1fnD4188behgJnorSphEPd3UfSKuh/vJKrH0yUuTLA3jYQegMkbf+h46x/wORV2jKBuEt5mQwHV6gT4TSsKsADZjxxTX6U7fAeRMOCCUrd8E9+IqY5nivvNAD12nQZliJVd7X+7wudtbipKlTPil+Hwy8ps3aymRLfxGsF22gfVMnWIMPzMW0aaon3vhKDtH3ylFt9aWhjayB6coiXvLmpyl4+vgNcauyVj/iIHIQKBgQDGGm8SavKHwDUYxMxxok25XOqmDc9YEhY6wLdQUts7YHE0w91nXd0bSA8d8WndZF2l62J6V84822ekhNGhl0j8WI2l43a9JhlqKhB+6qkMq5ln/7Gjb1eHHGiMM8N6Rgw4R3jwQ+dBDjwGHpV+rMfb7IKUqrGy6qznREDDEvfC0QKBgQC0Tm855bxVb5aTyAVAI6HRzsTI4iR8wrueEXpfrS93RGx5QP386ri+LtZQd7dgOoVQTpDD9Ton/1mVlOri+b1hEcmV7DK2/5rQfNz8zOaiB0HBclFHzAJcBix9rxZs4Y2FOwNSb/TsOp1cruWcC8L/BXjsly66WAcEsJM/4B8QBwKBgQCycv9YzOPZ6wcpX6V2Oyjeb9eja086W1iQ5iZ7AZggfXicek8HnOLef2O2qk+dsZo2KrnT0SKjaVSMO2SGtjt1rOMz6pl8O0SrgGbJnOJY2n7e0tKWkWZ7+9glaz+L9mttitxjOhenX4YsUQKDUGrcLclW2AgoTgMgdDgOQRjT8QKBgCaYx6yAJjGvwm7+GUmilwg/l9ZcumzOjrUGjsieXRPN3N6T8ArNACEOC6iMCAAcZt9LAihAfWOEgNvJVgKibUyzLxDdz/lme/B3WiLvhctXfFb5rraM0FB8rdd1vHowSkgj2tEx0B8laPwnIFWoGuTWeg1DS6huIYp6dfrtchjPAoGBALwTb3DF4uXp9N3vxxH1qY600GhadJg/GmmeguKflsFwa6AAjcxfwtg7aCKxW76NF5TiWu3qrUZ87DNgotRvexj5ybZC9ceGJkvBlvLphZhw/U+0B8rTzVxWQWuzzN38dzVkta9y5ggNT5bTiOjmBJtQqP72ncncJIvv6GW5zZli";
/*$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
$end_public_key = "-----END PUBLIC KEY-----\r\n";*/
$begin_public_key = "-----BEGIN RSA PRIVATE KEY-----\r\n";
$end_public_key = "-----END RSA PRIVATE KEY-----\r\n";


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

