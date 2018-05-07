<?php
define('ROOT_PATH', str_replace('interface/xiaomi/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		9=>array(
				'android'=>array("appId"=>"2882303761517777512","appSecret"=>"DLM2G5UZPmo4QxFn7DrKpg=="),
		),
);


function get_signature($verifyData,$key){
    $sign = hash_hmac("sha1",$verifyData,$key,false);
    $bytes=getBytes($sign);
    return decto_bin($bytes);
   // echo decto_bin($bytes).'<br />';
}

function decto_bin($datalist){
    $result="";
    foreach($datalist as $v){
        $demo = base_convert($v , 10 , 16);
        $res[]=$demo;
        if(strlen($demo)==1) {
            $demo="0".$demo;
        }
        $result .=$demo;
    }
    return $result;
}

function getBytes($str){
    $str = iconv('ISO-8859-1','UTF-16BE',$str);
    $len = strlen($str);
    $bytes = array(); 
    for($i=0;$i<$len;$i++) {
        if($i%2) {
            $bytes[] =  ord($str[$i]) ;
        }
    }
    return $bytes;
}
?>
