<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
include("inc/excel.class.php");

$rpCode=CheckStr($_REQUEST["rpCode"]);
$SvrID=CheckStr($_REQUEST["SvrID"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);
$game_id=intval($_REQUEST["game_id"]);
//默认时间
if ($StarTime=="" && $EndsTime==""){
	$StarTime=date("Y-m-d");
	$EndsTime=date("Y-m-d");
}
$StarTimet = $StarTime." 00:00:00";
$EndsTimet = $EndsTime." 23:59:59";

$sql=" And (Add_Time between '$StarTimet' And  '$EndsTimet') ";
$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&";

//成功状态
if ($rpCode != ""){
	if ($rpCode == "1") $sql=$sql." And rpCode in ('1','10')";
	if ($rpCode == "2") $sql=$sql." And rpCode not in ('1','10')";
	if ($rpCode == "3") $sql=$sql." And rpCode is null";
	$PURL=$PURL."rpCode=$rpCode&";
}

if ($game_id != ""){
	$sql=$sql." And game_id=$game_id";
	$PURL=$PURL."game_id=$game_id&";
}

if ($SvrID != ""){
	$sql=$sql." And ServerID=$SvrID";
	$PURL=$PURL."SvrID=$SvrID&";
}

$SqlStr="select sum(PayMoney) total,clienttype from pay_log Where 1=1 ".$sql." group By clienttype";
if($_POST['excel']){
	$excel=new Excel_XML('gbk');
	$excel->addRow(array('序号','机型','总金额'));
	$query=mysql_query($SqlStr);
	while ($rs=mysql_fetch_assoc($query)){
		if($rs['clienttype']==''){
			$tmp+=$rs['total'];
		}
		else{
			$list[]=$rs;
		}
	}
	if($list){
		array_push($list,array('clienttype'=>'空','total'=>$tmp));
	}
	$all='';
	if($list){
		foreach ($list as $key=>$val){
			$excel->addRow(array($key+1,$val['clienttype'],$val['total']));
			$all+=$val['total'];
		}
	}
	$excel->addRow(array());
	$excel->addRow(array('总金额','',$all));
	if($game_id&&$SvrID){
		$excel->addRow(array('游戏',$game_arr[$game_id]['name'],'服务区',$game_arr[$game_id]['server_list'][$SvrID]));
	}
	if(!$rpCode){
		$rpCode='0';
	}
	$excel->addRow(array('充值状态',strtr($rpCode,array('0'=>'全部','1'=>'成功','2'=>'失败','3'=>'无回执'))));
	if($StarTime&&$EndsTime){
		$excel->addRow(array('查询时间',$StarTime,$EndsTime));
	}
	$excel->generateXML('充值机型统计'.date('Y-m-d'));
	exit;
}

//分页参数
$pagesize=30;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->pageSql();//格式化sql语句

$result=mysql_query($SqlStr);
while ($rs=mysql_fetch_assoc($result)){
	if($rs['clienttype']==''){
		$tmp+=$rs['total'];
	}
	else{
		$list[]=$rs;
	}
}
if($list){
	array_push($list,array('clienttype'=>'空','total'=>$tmp));
}
?>
<html>
<head>
<title>充值机型统计</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="3" align="center">充值机型统计</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">游戏：</td>
      <td class="forumRow">
      <select name="game_id" onchange="change_game(this.value)">
           <option value='' >选择游戏</option>
            <?php
               for($i=1;$i<=count($game_arr);$i++){
                  echo "<option value=\"".$i." \" ".(($i==$game_id)?' selected="selected"':'')." >".$game_arr[$i]['name']."</option>";
               }
            ?>
            </select>
       </td>
     
    </tr>
	<tr> 
      <td align="right" class="forumRow">服务区：</td>
      <td class="forumRow">
      <select name="SvrID" id="ServerID">
            <option value="" selected="selected">请选择分区</option>
            <?php
               foreach($game_arr[$game_id]['server_list'] as $game_key=>$game_value){
                  echo "<option value=\"".$game_key."\" ".(($SvrID==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
               }
            ?>
    </select>
	 </td>
	
	</tr>
	<tr>
	  <td align="right" class="forumRow">充值状态：</td>
      <td class="forumRow"><select name="rpCode">
        <option value="">—全部—</option>
        <option value="1" <?=SeleObject(1,$rpCode)?>>成功</option>
        <option value="2" <?=SeleObject(2,$rpCode)?>>失败</option>
        <option value="3" <?=SeleObject(3,$rpCode)?>>无回执</option>
      </select>
	</tr>
    <tr> 
      <td align="right" class="forumRow">充值时间：</td>
      <td class="forumRow">
      <input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
   		 ～ 
      <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"></td>

   </tr>
    <tr> 
      <td align="right" class="forumRow"></td>
      <td class="forumRow">
      <input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">
      <input type="submit" name="excel" class="bott01" value="导出excel">
      </td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr> 
    <th width="10%" align="center">序号 </th>
    <th width="20%">机型</th>
    <th width="20%">总金额</th>
  </tr>
<?
$all='';
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;

if($list){
	foreach ($list as $key=>$rs){
		$all+=$rs["total"];
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$key+1?></td>
    <td nowrap class="forumRow"><?=$rs["clienttype"]?></td>
    <td nowrap class="forumRow"><?=$rs["total"]?></td>
  </tr>
  <?
}}
?>
  <tr> 
    <td height="25" colspan="13" align="center" class="forumRow">当页总金额:<?=$all?></td>
  </tr>
  <tr> 
    <td height="25" colspan="13" align="center" class="forumRow"><?php echo $page->show();?></td>
  </tr>
</table>

</body>
<script type="text/javascript">
   function change_game(game_id){
       $.post("ajax/game.php", { action: "change_game", game_id: game_id },
  function(data){
         $("#ServerID").html(data);
    });
   }
</script>
</html>
<?
mysql_close();
?>