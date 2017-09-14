<?php
include("inc/CheckUser.php");
//include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/config.php");
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?php
SetConn(88);
//异常积分sql
$orderid = $_REQUEST['orderid'];
$addtag = $_REQUEST['addtag'];
if($orderid)
{
    $sql = "SELECT PayID,PayName,PayMoney,LinkID,Add_Time FROM pay_sms where tag='0' and rpCode='DELIVRD' and LinkID='".$orderid."' limit 0,1";
    $res=mysql_query($sql);
    if($info = mysql_fetch_array($res))
    {
        if($addtag != '1')
        {
            $upsql = "update pay_sms set tag='1' where LinkID='".$orderid."'";
            mysql_query($upsql);
        }
        else
        {
            $rets = updatePoints($info['PayID'],$info['PayMoney'],'dx',$info['LinkID'],'admin');
            if($rets!='ok')
            {
                if($rets=='okadd_err')
                ErrMsg("加海牛积分成功，但修改状态失败！");
                elseif($rets=='okuptag_err')
                ErrMsg("加海牛积分失败，但修改状态成功！");
                elseif($rets=='okadd_erruptag_err')
                ErrMsg("加海牛积分失败，修改状态失败！");
            }
            else
            {
                updateRankUp($info['PayID'],'dx',"admin");
            }
        }
    }
    else
    {
        ErrMsg("该订单".$orderid."无需处理！");
    }
}
SetConn(88);
$sql1 = "SELECT id,PayID,PayName,PayMoney,LinkID,Add_Time FROM pay_sms where tag='0' and rpCode='DELIVRD'";
//echo $sql;
$result=mysql_query($sql1);

?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">短信补积分</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">订单号：</td>
      <td width="85%" class="forumRow"><input name="orderid" type="text" size="30" value="<?=$orderid?>" ></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">加积分：</td>
      <td width="85%" class="forumRow">
          <select name="addtag">
              <option value="0">不需要</option>
              <option value="1">需要</option>
          </select>
          使用‘积分等级查询’查询玩家积分，若‘玩家积分’和‘应加积分’相等，则选择不需要
      </td>
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
    <th width="15%" height="22" align="center">编号</th>
    <th width="15%" height="22" align="center">账号</th>
    <th width="25%" height="22" align="center">订单</th>
    <th width="15%" height="22" align="center">金额</th>
    <th width="25%" height="22" align="center">日期</th>
  </tr>
  <?php
    $i = true;
    while($rs = mysql_fetch_array($result))
    {
        $i = false;
  ?>
  <tr>
    <td nowrap class="forumRow"><?=$rs['id']?></td>
    <td nowrap class="forumRow"><?=$rs['PayName']?></td>
    <td nowrap class="forumRow"><?=$rs['LinkID']?></td>
    <td nowrap class="forumRow"><?=$rs['PayMoney']?></td>
    <td nowrap class="forumRow"><?=$rs['Add_Time']?></td>
  </tr>
  <?php
    }
    if($i)
    {
      ?>
  <tr>
    <td nowrap class="forumRow" colspan="5">没有需要处理的记录</td>
  </tr>
  <?php
    }
  ?>
</table>
</body>
</html>