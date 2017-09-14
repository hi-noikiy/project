<?php		//数据库导入程序 -- haogong, 2003.9.14
set_time_limit(0);

HtmlHead("数据库导入") ;

if ( !IsSet($_POST["host"]) )
{	
	echo login_form() ;
	HtmlFoot() ;
	exit ;
}

$host = $_POST["host"] ;
$user = $_POST["user"] ;
$pass = $_POST["pass"] ;
$db_name = $_POST["db_name"] ;

$link = @mysql_connect($host, $user, $pass) or hg_exit("无法连接数据库！") ;
@mysql_select_db($db_name) or hg_exit("数据库不存在！") ;

$filename = $_FILES['sql_filename']['tmp_name'] ;

echo printf("导入完成，成功执行了 %d 条SQL语句!\n", exec_sqlfile($filename)) ;
@unlink($filename) ;
HtmlFoot() ;


function login_form()
{
	return "<h3 align=center>数据库导入程序：</h3>\n"
		. "<center>\n"
		. "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\" enctype=\"multipart/form-data\">\n"
		. "主　机：　<input name=\"host\" value=\"localhost\"><p>\n"
		. "用户名：　<input name=\"user\"><p>\n"
		. "密　码：　<input name=\"pass\" type=\"password\"><p>\n"
		. "数据库名：<input name=\"db_name\"><p>\n"
		. "选择数据库文件:<input type=\"file\" size=\"15\" name=\"sql_filename\">\n"
		. "<input type=\"submit\" value=\"执行\">\n"
		. "</form>\n"
		. "</center>\n" ;
}

function exec_sqlfile($filename)
{
	$gzok = FALSE;
	if ( function_exists("gzopen") )
	{
		$gzok = TRUE;
	}
	$sql_array = "" ;
	$gzp = $gzok ? gzopen($filename, "rb") : fopen($filename, "rb");
	$data = "1" ;
	while ( $data )
	{
		$data = $gzok ? gzread($gzp, 1024*1024) : fread($gzp, 1024*1024);
		$sql_array .= $data ;	
	}
	$gzok ? gzclose($gzp) : fclose($gzp);
	
	$sql_array = split_sql_array( $sql_array ) ;

	$nCount = 0 ;
	foreach($sql_array as $sql)
	{
		if ( mysql_query($sql) )	$nCount++; 
	}
	return $nCount ;
}

function split_sql_array(&$sql_array)
{	
	$ret = array();
	$num = 0;
	$all_query = explode(";\n", trim($sql_array));
	foreach($all_query as $one_query)
	{
		$one_query = explode("\n", trim($one_query));
		$ret[$num] = "" ;
		foreach($one_query as $one_line) 
		{
			$ret[$num] .= ($one_line[0] == "#" ? "" : $one_line) ;
			$ret[$num] = str_replace("\\r", "\r", $ret[$num]) ;
			$ret[$num] = str_replace("\\n", "\n", $ret[$num]) ;
		}
		$num++;
	}			
	return $ret ;
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