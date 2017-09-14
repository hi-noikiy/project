<?php
//过滤字符串
function gjj($str){
	$farr = array(
			"/\\s+/",
			"/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
			"/(<[^>]*)on[a-zA-Z]+\\s*=([^>]*>)/isU",
	);
	$str = preg_replace($farr,"",$str);
	return addslashes($str);
}
function DangerInput($array){
	if(get_magic_quotes_gpc()) return $array;
	if (is_array($array)){
		foreach($array AS $k => $v){
			$array[$k] = DangerInput($v);
		}
	}else{
		$array = gjj($array);
	}
	return $array;
}
//删除文件夹
function deldir($dir) {
	$dh=opendir($dir);
	while (@$file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
//密码加密
function md5_10($str){
	$str=md5(strrev($str));
	$strLeft=substr($str,0,16);
	$strRight=substr($str,16,16);
	$str=md5($strLeft).md5($strRight);
	return md5($str);
}
//获取操作
function operateName($action){
	$needs="";
	$arr=explode('/',$_SERVER['REQUEST_URI']);	
	foreach ($arr as $k => $v){
		if(preg_match("/$action/",$v)){
			$needs=$k;
		    break;
		}
	}
	if(empty($arr[$needs+1]))
		$arr='index';
	else
		$arr=$arr[$needs+1];
	$arr=explode('&', $arr);
	$operate = str_replace('.html', '', $arr[0]);
	
	return $operate;
	
	
}
//前台处理缩略图
function setThumb($string){
	$str=str_replace("./assets", ASSETS_URL, $string);
	return $str;
}

function htmlFilter($val,$len){
	$str=htmlspecialchars_decode($val);
	$strlen=strlen($str);
	$str= preg_replace("/<(.*?)>/","",$str);
	if ($len<$strlen)
		return mb_substr($str, 0,$len)."...";
	else
		return $str;
}
//冒泡排序
function BubbleSort($arr,$key=null){
	$len=count($arr);
	for($i=0;$i<$len-1;$i++){
		for($j=0;$j<$len-1;$j++){
			if(empty($key)){
				if($arr[$j]<$arr[$j+1]){
					$tmp=$arr[$j+1];
					$arr[$j+1]=$arr[$j];
					$arr[$j]=$tmp;
				}
			}else{
				if($arr[$j][$key]<$arr[$j+1][$key]){
					$tmp=$arr[$j+1];
					$arr[$j+1]=$arr[$j];
					$arr[$j]=$tmp;
				}
			}
		}
	}
	return $arr;
}
//object to array
function objectToArray($object){
	$_array = is_object($object) ? get_object_vars($object) : $object;
	foreach ($_array as $key => $value) {
		$value = (is_array($value) || is_object($value)) ? objectToArray($value) : $value;
		$array[$key] = $value;
	}
	return $array;
}
//快速排序法
function quickSort($arr){
	$len    = count($arr);
	if($len <= 1) 
		return $arr;
	$key 		  = $arr[0];
	$keywhere 	  = $arr[0]['overtime'];
	$left_arr     = array();
	$right_arr    = array();
	for($i=1; $i<$len; $i++){
		if($arr[$i]['overtime'] >= $keywhere){
			$left_arr[] = $arr[$i];
		} else {
			$right_arr[] = $arr[$i];
		}
	} 
	$left_arr		= quickSort($left_arr);
	$right_arr    	= quickSort($right_arr);
	return array_merge($left_arr, array($key), $right_arr);

    function httpBuidQuery($array, $appKey){
        if(!is_array($array))
            return false;
        if(!$appKey) return false;
        ksort($array);
        $md5Str = http_build_query($array);
        $mySign = md5(urldecode($md5Str).$appKey);
        return $mySign;
    }
//登陆
    function https_post($url, $data, $i = 0) {
        $i++;
        $str = '';
        if ($data) {
            foreach ( $data as $key => $value ) {
                $str .= $key . "=" . $value . "&";
            }
        }
        $curl = curl_init ( $url ); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        if ($str) {
            curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
        }
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 ); // 设置超时限制防止死循环
        // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec ($curl);
        $Err = curl_error($curl);
        if (false === $tmpInfo || !empty($Err)) {
            if($i == 1)
                return https_post ($url, $data, $i);
            curl_close ($curl);
            return $Err;
        }
        curl_close ($curl);
        return $tmpInfo;
    }
}
