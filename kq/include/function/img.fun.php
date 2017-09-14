<?php
/*
 * 从新闻html中读取第一张图片
 */
function getImgInHtml($html){
	global $rooturl;
	/*
	<IMG src="/eWebEditor/uploadfiles/20070626111357.JPG" border=0>
	*/
   $ex = '/<img.*?src="([^"]+)"/i';
   $ex = '/<img.*?src=["\']([^"]+)["\']/i'; 
   $result = array();
   preg_match($ex, $html, $result);

   if($result[1]=="") $result[1]=$rooturl.'images/none.png';
   return $result[1];
}
/*
 * 显示图片url
 */
function img($url){
	global $rooturl;
	if($url){
		if(substr($url,0,7)=='http://')	return $url;
		else return $rooturl.$url;
	}else return $rooturl.'style/images/holife_logo.gif';
}
?>