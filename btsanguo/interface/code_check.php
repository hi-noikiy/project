<?php
include_once 'init.php';
$Code=trim($_REQUEST["code"]);
$UserName=trim($_REQUEST["userName"]);

 $str = "code=$Code,UserName=$UserName, ";
 write_log(ROOT_PATH."log","code_check_info_",$str.date("Y-m-d H:i:s")."\r\n");

SetConn(81);
if(($Code=="") || ($UserName=="")) {
    echo 1 ;exit;
	//echo "激活码或帐号不能为空！";
}
//判断帐号
$sql="select betaver from account where name='$UserName'";
//echo $sql;
$conn=mysql_query($sql);
$rs=mysql_fetch_array($conn);
if(!$rs) {
    echo 2;exit;
	//echo "您输入的帐号不存在！";
}else{
	//判断激活码
	$sql="select flag from code_account where code='$Code'";
	//echo $sql;
	$conn=mysql_query($sql);
	$rs2=mysql_fetch_array($conn);
	if(!$rs2) {
        echo 3;
		//echo "您输入的激活码不存在！";
		exit;
	}else{
		if ($rs2[flag] ==1){	//激活码标识
		echo 4;
        //echo "激活码失效。您输入的激活码已被激活使用，激活码只能使用一个账号！";
		exit;
		}
	}

	if ($rs[betaver] !=0){	//帐号标识
	 echo 5;
     //echo "您输入的帐号已被激活！";
	 exit;
	}
	//更新帐号标识
	  $sql="Update account Set betaver=16 Where name='$UserName'";
	
	mysql_query($sql);
	if (mysql_query($sql) == False){
		//写入失败日志
		$str=$UserName."  ".$Code."  ".date("Y-m-d H:i:s")."\r\n";
		write_log(ROOT_PATH."log","code_err_",$str);
		exit;
	}

	//更新激活码标识
	$sql="Update code_account Set flag=1 Where code='$Code'";
	mysql_query($sql);
	//写入激活记录log
	$AddTime=date("Y-m-d H:i:s");
	$sql="insert into code_log(UserName,Code,Add_Time) values('$UserName','$Code','$AddTime')";
	//echo $sql;
	mysql_query($sql);
    echo 6;
	//echo "恭喜您帐号已成功激活！";
    exit;
	//Msg();
}



?>
