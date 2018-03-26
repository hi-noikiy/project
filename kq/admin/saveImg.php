<?php
session_start();
$hosturl = 'http://'.$_SERVER['HTTP_HOST'].'/';
if(strpos($_SERVER['REQUEST_URI'],'kq/' )){
	$hosturl .= 'kq/';
}
$uid = $_SESSION['ADMIN_ID'];
$hosturl .= "admin/image/$uid";
define('ROOT_PATH', str_replace('saveImg.php', '', str_replace('\\', '/', __FILE__)));
$url = ROOT_PATH.'image/'.$uid;
if(!is_dir($url))mkdir($url, 0777);
$file = (isset($_POST["file"])) ? $_POST["file"] : '';
if($file)
{
	$data = base64_decode(str_replace('data:image/png;base64,', '', $file)); //截图得到的只能是png格式图片，所以只要处理png就行了
	$name = md5(time()) . '.png'; // 这里把文件名做了md5处理
	file_put_contents($url.'/'.$name, $data);
	
	echo"$hosturl/$name";
	die;
}
?>