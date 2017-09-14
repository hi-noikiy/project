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
    SetConn(81);
    $account = strtolower(trim($_REQUEST['account']));
    $superpw = $_REQUEST['superpw'];
    $chsuperpw = $_REQUEST['chsuperpw'];
    $newsup = md5($superpw.$mdString);
    if(!$account||!$superpw||!$chsuperpw)
    {
        ErrMsg("请将信息填写完整！");
    }
    elseif($superpw!=$chsuperpw)
    {
        ErrMsg("两次输入超密不一致！");
    }
    elseif(strlen($superpw)<6)
    {
        ErrMsg("超密长度必须6位以上!");
    }
    else
    {
        $sql = "SELECT * FROM account where NAME='".$account."' limit 0,1";
        $res=mysql_query($sql);
        if(!$info = mysql_fetch_array($res))
        {
            ErrMsg("该账号不存在！");
        }
        else
        {
            $upsql = "update account set superpasswd='".$newsup."' where NAME='".$account."'";
            $res=mysql_query($upsql);
            if($res)
            {
                $strlog = "NAME=$account,who=$AdminName,new_superpw=$newsup, date=".date("Y-m-d H:i:s")."\r\n";
                write_log('log','reset_superpw',$strlog);
                ErrMsg("修改成功！");
            }
            else
            {
                ErrMsg("请重试！");
            }
        }
    }
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">重置超密</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">账号：</td>
      <td width="85%" class="forumRow"><input name="account" type="text" size="30" value="<?=$account?>" ></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">新超密：</td>
      <td width="85%" class="forumRow"><input name="superpw" type="password" size="30" value="" ></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">确认超密：</td>
      <td width="85%" class="forumRow"><input name="chsuperpw" type="password" size="30" value="" ></td>
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