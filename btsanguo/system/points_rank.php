<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");
include("inc/page.php");
exit("20100224已经追加过,暂时禁用");
$EndsTime=CheckStr($_REQUEST["EndsTime"]);
if ($EndsTime != ""){
$et = strtotime($EndsTime."235959");
$re_et = date('Y-m-d H:i:s',$et);

	$sql=" And Add_Time <= '$re_et' ";
	$PURL="EndsTime=$EndsTime&";
//非短信充值sql
$SqlStr="SELECT PayName,sum(PayMoney) as points FROM pay_log WHERE (rpCode='1' or rpCode='10')".$sql." group by PayID";
//echo $SqlStr;
SetConn(88);
$res = mysql_query($SqlStr);
$ary = array();
while($rs = mysql_fetch_array($res))
{
	$ary[$rs['PayName']]['PayName'] = $rs['PayName'];
	$ary[$rs['PayName']]['points'] = $rs['points'];
}
//print_r($ary);exit;
//短信充值sql
$SqlStr_dx="SELECT PayName,sum(PayMoney) as points FROM pay_sms WHERE (rpCode='DELIVRD')".$sql." group by PayID";
$res_dx = mysql_query($SqlStr_dx);

while($rs_dx = mysql_fetch_array($res_dx))
{
	$ary[$rs_dx['PayName']]['PayName'] = $rs_dx['PayName'];
	$ary[$rs_dx['PayName']]['points'] += $rs_dx['points'];
}

//print_r($ary);exit;
        $uprankdate = date('Y-m-d');
	//SetConn(81);
	foreach($ary as $val)
	{
                SetConn(81);
		if($val['points']>=0&&$val['points']<500)
		$rank = 0;
		elseif($val['points']>=500&&$val['points']<1500)
		$rank = 1;
		elseif($val['points']>=1500&&$val['points']<3000)
		$rank = 2;
                elseif($val['points']>=3000&&$val['points']<5000)
		$rank = 3;
                elseif($val['points']>=5000)
		$rank = 4;
                /*需要5，6级时开启
                elseif($val['points']>=5000&&$val['points']<8000)
		$rank = 4;
                elseif($val['points']>=8000&&$val['points']<15000)
		$rank = 5;
		elseif($val['points']>=15000)
		$rank = 6;
                 *
                 */
                 if($val['points']>0)
                 {
                     $insert = "insert into `vipPoints`(`account_id`,`NAME`) select `id`,`NAME` from `account` where NAME='".$val['PayName']."'";
                     mysql_query($insert);
                    $upsql_vip = "update vipPoints set points='".$val['points']."',pointstotal='".$val['points']."',vip='".$rank."',uprankdate='".$uprankdate."',topVip='".$rank."' where NAME='".$val['PayName']."'";
                    $rets_v = mysql_query($upsql_vip);
                    $upsql_account = "update account set points='".$val['points']."',vip='".$rank."' where NAME='".$val['PayName']."'";
                    $rets_a = mysql_query($upsql_account);
                    if(!$rets_v)
                    {
                        write_log('../log','add_old_points_v_error_',$upsql.date('Ymd His')."\r\n");
                    }
                    if(!$rets_a)
                    {
                        write_log('../log','add_old_points_a_error_',$upsql.date('Ymd His')."\r\n");
                    }
                    updateBbsVip($val['PayName'],$rank);
                 }
	}
}

	SetConn(81);
	$SqlStr2="SELECT account_id,NAME,pointstotal,vip FROM vipPoints order by points desc";
	$pagesize=15;
	$page=new page($SqlStr2,$pagesize,getPath()."?".$PURL);//分页类
	$SqlStr2=$page->PageSql();//格式化sql语句
        //echo $SqlStr2;
	$result=mysql_query($SqlStr2);

?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/ActionFrom.js"></script>

</head>
<body class="main">

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">追加积分和等级</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">时间：</td>
       <td class="forumRow"><input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"> 之前
      </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 提交 "></td>
    </tr>
  </form>
</table>

<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr>
    <th width="60"  height="22" align="center">玩家编号</th>
    <th width="100" height="22" align="center">玩家名称</th>
    <th width="93" height="22" align="center">玩家积分</th>
    <th width="48" height="22" align="center">玩家等级</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;

while($rs=mysql_fetch_array($result)){


?>
  <tr bgcolor="#ECECED">

	<td width="60"  height="22" align="center"><?=$rs['account_id']?></td>
	<td width="100" height="22" align="center">
	<?=$rs['NAME']?>
	</td>
	<td width="93" height="22" align="center">
	<?=$rs['pointstotal']?>
	</td>

	<td width="48" height="22" align="center">
	<?=getRank($rs['vip'])?>
	</td>

  </tr>
<?
$I++;
}
?>
  </form>
  <tr>
    <td height="25" colspan="7" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
<?php mysql_close();?>
</body>
</html>