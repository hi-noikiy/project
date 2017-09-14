<?php
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?php
SetConn(81);
$account = strtolower($_REQUEST['account']);

if($account&&$account!='')
{
    $sql = "SELECT * FROM account where NAME='".$account."' limit 0,1";
    $res=mysql_query($sql);
    if(!$info = mysql_fetch_array($res))
    {
        ErrMsg("该账号不存在！");
    }
    else
    {
        $sqlt = "SELECT * FROM account_bind where NAME='".$account."' and tag='1' limit 0,1";
        $rest=mysql_query($sqlt);
        if(!$infos = mysql_fetch_array($rest))
        {
            ErrMsg("该账号未绑定邮箱！");
        }
    }
}

?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">账号绑定查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">账号：</td>
      <td width="85%" class="forumRow"><input name="account" type="text" size="30" value="<?=$account?>" ></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
         </td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22">
    <th width="15%" height="22" align="center">账号</th>
    <th width="25%" height="22" align="center">邮箱</th>
    <th width="25%" height="22" align="center">时间</th>
    
  </tr>
<?
    if($infos)
    {
       ?>

  <tr>
    <td nowrap class="forumRow" align="center"><?=$infos['NAME']?></td>
    <td nowrap class="forumRow" align="center"><?=$infos['email']?></td>
    <td nowrap class="forumRow" align="center"><?=date('Y-m-d H:i:s',$infos['bind_time'])?></td>
  </tr>
  <? }?>
</table>
</body>
</html>