<?php	// 数据库导出程序，  haogong, 2003.9.13
set_time_limit(0);

session_start() ;

if ( (!IsSet($_POST["host"])) && (!IsSet($_SESSION["host"])) )
{
	HtmlHead("数据库导出") ;
	echo login_form() ;
	HtmlFoot() ;
	exit ;
}

if ( !IsSet($_SESSION["host"]) )
{
	@mysql_connect($_POST["host"], $_POST["user"], $_POST["pass"]) or hg_exit("无法连接数据库！") ;
	@mysql_select_db($_POST["db_name"]) or hg_exit("数据库不存在！") ;
	
	$_SESSION["host"] = $_POST["host"] ;
	$_SESSION["user"] = $_POST["user"] ;
	$_SESSION["pass"] = $_POST["pass"] ;
	$_SESSION["db_name"] = $_POST["db_name"] ;
	
	show_download_page() ;
}

$link = @mysql_connect($_SESSION["host"], $_SESSION["user"], $_SESSION["pass"]) or hg_exit("无法连接数据库！") ;
@mysql_select_db($_SESSION["db_name"]) or hg_exit("数据库不存在！") ;

$res_tables = mysql_list_tables($_SESSION["db_name"]) or hg_exit("查询出错!") ;

$sqldata = "" ;
while ( @list($tb_name) = mysql_fetch_array($res_tables) )
{
	$sqlData .= dump_table($tb_name) ;
}

$sqlData = "# 数据库导出程序 -- haogong 作品(http://www.isphp.net/) <body>\n" . $sqlData ;

$filename = "dump_" . date("Ymd") . ".sql" ;
if ( function_exists("gzencode") )
{
	$sqlData = gzencode($sqlData) ;
	$filename = "dump_" . date("ymd") . ".gz" ;
}

Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
Header("Accept-Length: " . strlen($sqlData));
Header("Content-Disposition: attachment; filename=$filename");

printf("%s", $sqlData) ;

function login_form()
{
	return "<h3 align=\"center\">数据库导出程序：</h3>\n"
		. "<center>\n"
		. "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n"
		. "主　机：　<input name=\"host\" value=\"localhost\"><p>\n"
		. "用户名：　<input name=\"user\"><p>\n"
		. "密　码：　<input name=\"pass\" type=\"password\"><p>\n"
		. "数据库名：<input name=\"db_name\"><p>\n"
		. "<input type=\"submit\" value=\"提交，开始导出数据\">\n"
		. "</form>\n"
		. "</center>\n" ;
}

function show_download_page()
{
	HtmlHead("下载数据库文件：") ;
	$ext = ".sql" ;
	if ( function_exists("gzencode") )
	{
		$ext = ".gz" ;
	}
	echo "<center>\n"
		. "<br><br>\n"
		. "<font face=\"verdana\" size=\"3\">\n"
		. "右键点击下面的链接，选择“目标另存为”，注意，要指定扩展名为<font color=\"red\">$ext</font>\n<br><br>"
		. "<a href=\"{$_SERVER['PHP_SELF']}\"><h2>右键点击此处,选择“目标另存为”</h2></a>\n"
		. "</font>\n"
		. "</center>\n" ;
	HtmlFoot() ;
	exit ;
}

function dump_table($tb_name, $with_struct=1)
{
	$sqlData = "" ;
	if ( 1 == $with_struct )
	{
		$sqlData = "DROP TABLE IF EXISTS `$tb_name`;\n" ;
		$result=mysql_query("SHOW CREATE TABLE $tb_name");
		$row = mysql_fetch_array($result);
		$sqlData .= $row[1] ;
		$sqlData .= ";\n" ;
	}
	$result = mysql_query("Select * from $tb_name") ;
	$nFields = mysql_num_fields($result) ;
	while ( @$oneLineData = mysql_fetch_array($result) )
	{
		$oneLine = "INSERT INTO `$tb_name` VALUES(" ;
		$comma = "" ;
		for ( $i=0; $i<$nFields; $i++ )
		{
			$oneLine .= $comma . "'" . addcslashes($oneLineData[$i], "'") . "'" ;
			$comma = "," ;
		}
		$oneLine .= ");" ;
		$oneLine = str_replace("\r", "\\r", $oneLine) ;
		$oneLine = str_replace("\n", "\\n", $oneLine) ;
		$oneLine .= "\n" ;
		$sqlData .= $oneLine ;
	}
	return $sqlData ;
}

function HtmlHead($title="", $css_file="", $css_str="", $forward_url="", $time=2)
{
	echo "<html>\n\n<head>\n"
		. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n" ;
	if ( $forward_url )
	{
		echo "<meta http-equiv=\"refresh\" content=\"$time; url='$forward_url'\">\n" ;
	}
	if ( $title )
	{
		echo "<title>$title</title>\n" ;
	}
	if ( $css_file )
	{
		echo "<link rel=\"stylesheet\" href=\"$css_file\">\n" ;
	}
	else
	{
		if ( $css_str )
		{
			echo $css_str ;
		}
		else
		{
			echo "<style type=\"text/css\">\n"
				. "body,input {font-size:12px; font-family:verdana,Tahoma,Arial}\n"
				. "</style>\n" ;
		}
	}
	echo "</head>\n\n<body>\n\n" ;
}

function HtmlFoot()
{
	echo copyright() . "\n</body>\n\n</html>\n" ;
}

function hg_exit($str)
{
	HtmlHead("出错啦!") ;
	echo "<BR><BR><center>$str</center><BR><BR>\n" ;
	echo "<center><a href=\"JavaScript:history.go(-1)\">点此返回</a></center>\n" ;
	HtmlFoot() ;
	exit ;
}

function copyright()
{
	return "<br><br><hr color=\"#003388\">\n"
		. "<center>\n"
		. "<p style=\"font-family:verdana; font-size:12px\">Contact us: \n"
		. "<a href=\"http://www.isphp.net/\" target=\"_blank\">http://www.isphp.net/</a></p>\n"
		. "</center>\n" ;
}

?>