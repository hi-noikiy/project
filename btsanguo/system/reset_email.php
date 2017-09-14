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
if($_REQUEST['account'])
{
    $account = strtolower(trim($_REQUEST['account']));
    if(!$account)
    {
        ErrMsg("请填写账号！");
    }
    else
    {
        SetConn(81);
        $sql = "SELECT * FROM account where NAME='".$account."' limit 0,1";
        $res=mysql_query($sql);
        if(!$info = mysql_fetch_array($res))
        {
            ErrMsg("该账号不存在！");
        }
        else
        {//绑定账号是否绑定邮箱
            $sqlck = "SELECT * FROM account_bind where NAME='".$account."' and email<>'' limit 0,1";
            $resck=mysql_query($sqlck);
            if(!$infock = mysql_fetch_array($resck))
            {
                ErrMsg("该账号未绑定邮箱！");
            }
            $upsql = "update account_bind set email='',code='',tag='0' where NAME='".$account."'";
            $res=mysql_query($upsql);
            if($res)
            {
                $strlog = "NAME=$account,who=$AdminName,date=".date("Y-m-d H:i:s")."\r\n";
                write_log('log','reset_email',$strlog);
                ErrMsg("修改成功！");
            }
            else
            {
                ErrMsg("请重试!");
            }
        }
    }
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">重置邮箱</th>
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
</body>
</html>