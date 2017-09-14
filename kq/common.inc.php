<?php
ini_set('display_errors', false);
//if(!$charset) {$charset='utf-8';}
header('Content-Type: text/html; charset=utf-8');
$developer=true;
if(!isset($_SESSION)) @session_start();
/*
 * 定义根路
 */
////如需子域�?则开启下列部分并指定域名
//$domain='hl.com';
//if(substr($_SERVER['HTTP_HOST'],0,4)=='www.'){
//	$rooturl 	= 'http://www.'.$domain.'/';
//}else{
//	$rooturl='http://'.$domain.'/';
//}
//ini_set('session.cookie_domain',$domain);
$rooturl = 'http://'.$_SERVER['HTTP_HOST'].'/kq/';

$rootpath = dirname(__FILE__).'/';
include($rootpath.'conf/db.conf.php');
include_once('common.fun.php');

$webdb=new mysql($host);

function data_check($val){
	if(is_array($val)){
		foreach($val as $k=>$v)	$val[$k]=data_check($v);
	}else{
		if(!get_magic_quotes_gpc()){
			$val=addslashes($val);
		}
		$dstr='select|insert|update|delete|union|into|load_file|outfile';
		$val=eregi_replace($dstr, '', $val);
	}
	return $val;
}
foreach($_GET as $key => $val) $_GET[$key]=data_check($val);
foreach($_POST as $key => $val) $_POST[$key]=data_check($val);
?>