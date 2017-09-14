<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");
include("inc/game_config.php");
include("inc/page.php");

    $bw = $_REQUEST['bw'];
    $game_id = $_REQUEST['game_id'];
    $StarTime=CheckStr($_REQUEST["StarTime"]);
    $EndsTime=CheckStr($_REQUEST["EndsTime"]);
    //默认时间
    if ($StarTime=="" && $EndsTime==""){
            //$StarTime=date("Y")."-".date("m")."-01";
            $StarTime=date("Y-m-d");
            $EndsTime=date("Y-m-d");
    }
    #在数据库时间条件加1天
    $sql=" And Add_Time >= '$StarTime' And Add_Time < DATE_ADD('$EndsTime',INTERVAL 1 DAY)";
    $PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&game_id=$game_id&";
    if($game_id){
       $sql .= " and game_id=$game_id ";
    }
    if(!empty($bw)||$bw=='0')
    {
        $sql .= " and dwFenBaoID ='".$bw."'";
        $PURL=$PURL."bw=$bw&";
    }
    else
    {
        $bw = '';
    }
	SetConn(88);
	$SqlStr2="SELECT PayName,dwFenBaoID,sum(PayMoney) as c FROM pay_log where 1".$sql." And rpCode in ('1','10') group by PayName order by sum(PayMoney) desc";
	//echo $SqlStr2;
        $pagesize=15;
	$page=new page($SqlStr2,$pagesize,getPath()."?".$PURL);//分页类
	$SqlStr2=$page->PageSql();//格式化sql语句
	$result=mysql_query($SqlStr2);
        $ary = array();
        
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
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">推广商充值查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">推广商ID：</td>
      <td width="85%" class="forumRow"><input name="bw" type="text" size="30" value="<?=$bw?>" ></td>
    </tr>
        <tr>
      <td width="15%" align="right" class="forumRow">游戏：</td>
      <td width="85%" class="forumRow"><select name="game_id">
<option value="" >—请选择—</option><?
foreach ($game_arr as $key=>$value){

    if($key==$game_id){
        echo "<option value=".$key." selected=\"selected\"  >".$value['name']."</option>\n";
    }else{
        echo "<option value=".$key."   >".$value['name']."</option>\n";
    }

}
?>
</select></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 提交 ">  <a href="mexcel.php?StarTime=<?=$StarTime?>&EndsTime=<?=$EndsTime?>&bw=<?=$bw?>&game_id=<?=$game_id?>">导出excel</a></td>
    </tr>
  </form>
</table>

<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr>
    <th width="60"  height="22" align="center">编号</th>
    <th width="100" height="22" align="center">玩家账号</th>
    <th width="93" height="22" align="center">金额</th>
    <th width="93" height="22" align="center">推广商ID</th>
  </tr>
<?php
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;

while($rs=mysql_fetch_array($result))
{
?>
  <tr bgcolor="#ECECED">

	<td height="22" align="center" width="100"><?=$I+($Ipage-1)*$pagesize?></td>
	<td width="100" height="22" align="center">
	<?=$rs['PayName']?>
	</td>
        <td width="100" height="22" align="center">
	<?=$rs['c']?>
	</td>
	<td width="93" height="22" align="center">
	<?=$rs['dwFenBaoID']?>
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