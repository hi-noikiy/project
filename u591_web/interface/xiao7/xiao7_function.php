<?php
function ConvertPublicKey($public_key){
	$public_key_string = "";
	$count=0;
	for($i=0;$i<strlen($public_key);$i++){
		if($count<64){
			$public_key_string.=$public_key[$i];
			$count++;
		}else{
			$public_key_string.=$public_key[$i]."\r\n";
			$count=0;
		}
	}
	$public_key_header = "-----BEGIN PUBLIC KEY-----\r\n";
	$public_key_footer = "\r\n-----END PUBLIC KEY-----";
	$public_key_string = $public_key_header.$public_key_string.$public_key_footer;
	return $public_key_string;
}

function Verify($sourcestr, $sign_dataature, $publickey){
	$pkeyid = openssl_get_publickey($publickey);
	$verify = openssl_verify($sourcestr, $sign_dataature, $pkeyid);
	openssl_free_key($pkeyid);
	return $verify;
}

function PublickeyDecodeing($crypttext, $publickey){
	$pubkeyid = openssl_get_publickey($publickey);
	if (openssl_public_decrypt($crypttext, $sourcestr, $pubkeyid, OPENSSL_PKCS1_PADDING)){
		return $sourcestr;
	}
	return FALSE;
}

function decode_http_build_query($str){
	if(empty($str)){
		return array();
	}
	$tempArr=explode("&",$str);
	$returnArr=array();
	foreach($tempArr as $k => $v){
		$tmpArr=explode("=",$v);
		if(count($tmpArr)==2){
			$returnArr[urldecode($tmpArr[0])]=urldecode($tmpArr[1]);
		}
	}
	return $returnArr;
}
function ReturnResult($text){
	echo $text;
	exit();
}
?>