<?php
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/config.php");
if (!getFlag('801',$uFlag)){
	header("Location: Adm_Login.php");
}
?>
<html>
<head>
<title>导入推广商数据</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?php
if($_FILES['upfile']['name'])
{
    SetConn(87);  //链接数据库
    $pathtmp = $_FILES['upfile']['tmp_name'];
    $filename = $_FILES['upfile']['name'];
    $type = strtolower(substr($filename,-3));
    //echo $type;exit;
    if($type!='csv' && $type!='sql')   //判断文件类型
    ErrMsg("请导入.csv或.sql文件");

    $sql = '';
    $handle = fopen($pathtmp,"r");     //读入临时文件
    if($type=='sql')
    {
        $n = 0;
        while (!feof($handle)) {
            $sql = fgets($handle, 1000);
            if($sql)
            $query=mysql_query($sql);
            if(!$query){
            	echo 'SQL错误:',$sql,'</br>';
            }
            $n += mysql_affected_rows();
        }
        write_log('log','insert_csv_sql_',$AdminName.",".$filename.'影响条数'.$n.",".date("Y-m-d H:i:s")."\r\n");
        echo "<script>alert('执行完毕!成功影响条数:$n')</script>";
    }
    elseif($type=='csv')
    {
        if($filename{0}=='c')       //判断文件
        $sql1="replace into cpa(id,accountid,maxlev,MaxOnLineTime,numRole,Fenbao,FenBaoUserID,reg_date,fenbaomobile,account,clienttype) values";
        elseif($filename{0}=='f')
        $sql1="replace into fenbaouser(id,accountid,reg_date,total,cpa,pecent,cpa1,pecent1,backNum,nomove) values";
        else
        ErrMsg("文件名不对，看提示");
        $i = 0;
        while ($data = fgetcsv ($handle, 1000, ",")){
            if($i=='0'){$i++;continue;}
            $sql='(';
            foreach ($data as $val){
            	$sql.="'$val',";
            }
            unset($val);
            $sql=$sql1.substr($sql,0,-1).')'; 
            $query=mysql_query($sql);
			if(!$query){
				echo 'SQL错误:',$sql,'</br>';
			}    
        	$i += mysql_affected_rows();
        }
        write_log('log','insert_csv_sql_',$AdminName.",".$filename.'影响条数'.$i.",".date("Y-m-d H:i:s")."\r\n");
        fclose ($handle);
        echo "<script>alert('执行完毕!成功影响条数:$i')</script>";
    }
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>" method="post" enctype="multipart/form-data">
    <tr>
      <th height="22" colspan="2" align="center">导入推广商数据</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow">
          <input type=file name='upfile'>
          <input type=submit value='导入' class="bott01">
         </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow">
          注:文件名以c开头的csv文件对应cpa表 eg:cpa-12-27.csv,文件分以f开头csv文件对应fenbaouser表 eg:fenbaouser-12-27.csv,文件拓展名为.sql为修改fenbaouser表文件
         </td>
    </tr>
  </form>
</table>
</body>
</html>