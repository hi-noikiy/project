<?
$db_host="localhost";
$db_user="root";
//$db_pass="u591,889";
$db_pass="root";
$db_database="u591";
$server_host="wenschan.gicp.net";//域名
$db=mysql_connect($db_host,$db_user,$db_pass) or die("数据库连接失败，请检查！");//打开MySQL服务器连接
mysql_select_db($db_database,$db) or die("数据库出错！");//链接数据库

mysql_query("set names 'utf8'");
date_default_timezone_set('Asia/Shanghai');
$NewsPath="../NewsFile/";

$server_host_1 = "wenschan1.xicp.net";

$hand_ip_arr = array("127.0.0.1","192.168.1.149","192.168.1.121","192.168.1.67","192.168.1.103","116.24.143.132");

define('ROOT_PATH', str_replace('system/inc/config.php', '', str_replace('\\', '/', __FILE__)));


$hand_ip_arr = array("121.201.16,15","117.135.138.91","218.17.158.136","219.134.64.77","218.17.157.176","218.17.157.69","14.23.168.18","14.23.168.10","119.131.244.178","119.131.244.202","120.31.68.66","218.5.2.91","121.201.16.15","58.215.137.242","183.53.54.7","116.24.143.132");
$ip=getIP_config();
 if(in_array($ip, $hand_ip_arr)){
     
 }else{
   //  exit;
 }

//$ip=getIP_config();
//$ip1 = gethostbyname($server_host);
//$ip2 = gethostbyname($server_host_1);
//if(!strstr($ip,$ip1)&&!strstr($ip,$ip2))
//{
//    if(in_array($ip, $hand_ip_arr)){
//
//    }else{
//        $fs=fopen(ROOT_PATH."system/inc/ip_config".".txt","a");
//        $str = "wenschan.gicp.net_ip:$ip1,wenschan1.xicp.net_ip:$ip2,ip=$ip ".date("Y-m-d H:i:s")." \n\t";
//        fwrite($fs,$str);
//        fclose($fs);
//        echo"<script>alert('出错了，联系管理员');history.go(-1);</script>";exit;
//    }
//
//}

function getIP_config(){
    $ip=getenv('REMOTE_ADDR');
    $ip_ = getenv('HTTP_X_FORWARDED_FOR');
    if (($ip_ != "") && ($ip_ != "unknown"))
    {
        $ip=$ip_;
    }
    return $ip;
}

session_start();
check_login();
function check_login() {
    if(!$_SESSION['admin_name']||!$_SESSION['u_flag']) {
        //记录日志
        $dirName = ROOT_PATH.'log';
        $logName = 'admin_session_error';
        $ip=getenv('REMOTE_ADDR');
        $ip_ = getenv('HTTP_X_FORWARDED_FOR');
        if (($ip_ != "") && ($ip_ != "unknown"))
        {
            $ip=$ip_;
        }
        $str = $_COOKIE["AdminName"]."  IP=$ip  ".date("Y-m-d H:i:s")."\r\n";

        $path_name=$dirName."/".date("ym");//年月
        if( !is_dir($path_name)==0 );{
            @mkdir($path_name);
        }
        $fs=fopen($path_name."/".$logName.date("ymd").".txt","a");//年月日
        fwrite($fs,$str);
        fclose($fs);

        if($_SERVER['SCRIPT_NAME']!='/system/Adm_Login.php') {
            //            $url = "Location: ".WEB_URL_ADMIN.'Adm_Login.php';
            //            header($url);exit;
            echo"<script>alert('出错了.联系管理员!');history.go(-1);</script>";exit;
        }
    }
}






?>