<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
?>
<html>
<head>
<title>payget</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?
$OrderID=CheckStr($_REQUEST["OrderID"]);
if ($OrderID != ""){
	$sql="select ServerID,PayType,PayID,OrderID,PayMoney from pay_log where OrderID='$OrderID' And rpCode in ('1','10')";
	$conn=mysql_query($sql);
	$rs=mysql_fetch_array($conn);
	if($rs) {
                //9表示手工充值渠道,8表示移动梦网渠道
                if($rs['CPID']=='9'||$rs['CPID']=='8')
                {
                    ErrMsg("手工充值和移动梦网渠道无需补单");
                }
		$ServerID=$rs["ServerID"];
		$PayType=$rs["PayType"];
		$PayID=$rs["PayID"];
		$OrderID=$rs["OrderID"];
        $PayMoney = $rs["PayMoney"];
		echo $ServerID." ".$PayType." ".$PayID." ".$OrderID;
		SetConn($ServerID);//根据SvrID连接服务器
		//$dwChkSum = rand(100000000,999999999);
		$time_stamp=date('ymdHi');
		//判断定单号是否重复
		$sql="select count(0) from u_card where ref_id='$OrderID'";
		$query=mysql_query($sql);
		$RowCount=mysql_result($query,0);
		if ($RowCount == 0){
//            if($PayType){
//                $sql="insert into u_card(type,account_id,ref_id,time_stamp,used)"." values($PayType,$PayID,'$OrderID',$time_stamp,0)";
//            }else{
                 $PayType = 8;
                 $sql="insert into u_card(type,account_id,ref_id,time_stamp,used,data,server_id)"." values($PayType,$PayID,'$OrderID',$time_stamp,0,'$PayMoney','$ServerID')";
//            }



			if (mysql_query($sql) == False){
				ErrMsg("操作失败！");
			}

            write_log(ROOT_PATH."log","admin_pay_get_log_"," $sql,  ".date("Y-m-d H:i:s")."\r\n");

			ErrMsg("操作成功！");
		}else{
			ErrMsg("您的定单已存在，无需处理！");
		}
	}else{
		ErrMsg('您的定单不存在或失败状态，不能作补单处理！');
	}
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">输入定单号</th>
    </tr>
    <tr>
      <td width="15%" align="right" class="forumRow">定单号：</td>
      <td width="85%" class="forumRow"><input name="OrderID" type="text" value="<?=$OrderID?>" size="30"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow"><input name="rpCode" type="hidden" value="<?=$rpCode?>"></td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 "></td>
    </tr>
  </form>
</table>

<?
mysql_close();	  
?>
</body>
</html>