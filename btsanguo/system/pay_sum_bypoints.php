<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
SetConn(88);
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?php
$StarPoints=CheckStr($_REQUEST["StarPoints"]);
if(!$StarPoints)$StarPoints=0;
$EndsPoints=CheckStr($_REQUEST["EndsPoints"]);
$strend = "";
if($EndsPoints)$strend=" and sum(PayMoney)<'$EndsPoints' ";
$sql = "SELECT count( * ) as total FROM (SELECT sum(PayMoney) FROM pay_log where rpCode='1' or rpCode='10' GROUP BY PayID HAVING sum(PayMoney) >='$StarPoints' $strend) AS c";
$sql_total = "SELECT count( * ) as total FROM (SELECT sum(PayMoney) FROM pay_log where rpCode='1' or rpCode='10' GROUP BY PayID) AS c limit 1";
//统计充值成功数据
//echo $sql;
$result_total=mysql_query($sql_total);   //总人数
$total = mysql_fetch_array($result_total);
$result=mysql_query($sql);               //该段人数
$re = mysql_fetch_array($result);

?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">充值段：</td>
      <td width="85%" class="forumRow"><input name="StarPoints" type="text" size="12" value="<?=$StarPoints?>" >
～
  <input name="EndsPoints" type="text" size="12" value="<?=$EndsPoints?>"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">
          <input type="button" name="ExitSearch" value="退出搜索" class="bott01" onClick="window.location='<?=getPath()?>'"></td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th width="51" height="22" align="center">该段人数</th>
    <th width="51" height="22" align="center">总人数</th>
    <th width="51" height="22" align="center">比例</th>
  </tr>
  <tr> 
    <td nowrap class="forumRow"><?=$re['total']?></td>
    <td nowrap class="forumRow"><?=$total['total']?></td>
    <td nowrap class="forumRow"><?=round($re['total']/$total['total']*100,2)."%"?></td>
  </tr>
</table>
</body>
</html>