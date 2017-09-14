<?php
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/config.php");
if($_POST['action']=='select'){
    $_POST['name'] = trim($_POST['name']);
    if($_POST['name']==''){
       ErrMsg("查询账号不能为空！");
    }
   include("../inc/config.php");
   SetConn(81);
   $sql = ' select id,name,vip,reg_date,dwFenBaoID from  account where name=\''.$_POST['name'].'\' and dwFenBaoID !=0 ';
   $result=mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);

}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="">
  <input name="action" type="hidden" value="select" />
    <tr>
      <th height="22" colspan="2" align="center">经销商查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">账号：</td>
      <td width="85%" class="forumRow"><input name="name" type="input" value="" />
        </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">
         </td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22">
    <th width="51" height="22" align="center">id </th>
    <th width="153"  height="22" align="center">账号</th>
    <th width="153" height="22" align="center">VIP等级</th>
    <th width="200" align="center"><span class="forumRow">经销商</span></th>
    <th width="200" align="center"><span class="forumRow">注册日期</span></th>
  </tr>

  <tr>
    <td height="22" align="center" nowrap class="forumRow"><?php echo $result['id'] ?></td>
    <td nowrap class="forumRow"><?php echo $result['name'] ?></td>
    <td nowrap class="forumRow"><?php echo $result['vip'] ?></td>
    <td nowrap class="forumRow"><?php echo $result['dwFenBaoID'] ?></td>
    <td nowrap class="forumRow"><?php echo $result['reg_date'] ?></td>
  </tr>

</table>
</body>
</html>