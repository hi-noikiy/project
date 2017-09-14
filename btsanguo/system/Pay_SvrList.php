<?
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
?>
<html>
<head>
<title>充值记录</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/DateTime/datedialogNew.js"></script>
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<?
$SvrID=CheckStr($_REQUEST["SvrID"]);
$PayID=CheckStr($_REQUEST["PayID"]);
$PayUser=CheckStr($_REQUEST["PayUser"]);
$OrderID=CheckStr($_REQUEST["OrderID"]);
$game_id=CheckStr($_REQUEST["game_id"]);
SetConn($SvrID);//连接服务器

$ParaURL=$ParaURL."SvrID=$SvrID";
$ParaURL=$ParaURL."&PayUser=$PayUser";
//默认时间
if ($StarTime=="" && $EndsTime==""){
	//$StarTime=date("Y-m-d");
	$StarTime=date("Y")."-".date("m")."-01";
	$EndsTime=date("Y-m-d",strtotime("+1day"));
	$SqlSTime=date('ymd')."0000";
	$SqlETime=date('ymd',strtotime("+1day"))."0000";
	//$ny = date("Y");
	//$yf = date("m");
//}else{
}
//$sql=" And time_stamp>$SqlSTime And time_stamp<$SqlETime";
if ($OrderID != ""){
	$sql=$sql." And ref_id='$OrderID'";
	$ParaURL=$ParaURL."&OrderID=$OrderID";
}
if ($PayID != ""){
	$sql=$sql." And account_id=$PayID";
	$ParaURL=$ParaURL."&PayID=$PayID";
}
if ($game_id != ""){
	$ParaURL=$ParaURL."&game_id=$game_id";
}
//分页参数
$sql="select * from u_card Where 1=1".$sql." Order By time_stamp Desc";

//分页参数
$pagesize=20;
$page=new page($sql,$pagesize,"?".$ParaURL."&");//分页类
$sql=$page->pageSql();//格式化sql语句
$result=mysql_query($sql);
//echo $sql;
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="">
    <tr> 
      <th height="22" colspan="2" align="center">充值记录</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">帐号ID：</td>
      <td width="85%" class="forumRow"><input name="PayID" type="text" value="<?=$PayID?>"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">定单号：</td>
      <td width="85%" class="forumRow"><input name="OrderID" type="text" value="<?=$OrderID?>" size="30"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">游戏：</td>
      <td width="85%" class="forumRow">
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
      <td align="right" class="forumRow">服务区： </td>
      <td width="85%" class="forumRow">
      <select name="SvrID" id="ServerID">
            <?php
               foreach($game_arr[$game_id]['server_list'] as $game_key=>$game_value){
                  echo "<option value=\"".$game_key."\" ".(($game_key==$SvrID)?' selected="selected"':'').">".$game_value."</option>";
               }
            ?>
    </select></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">      </td>
    </tr>
  </form>
</table>
<div style="font-size: 3px">&nbsp;</div>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th width="47" height="22" align="center">编号 </th>
    <th width="92"  height="22" align="center">帐号</th>
    <th width="92" height="22">服务区</th>
    <th width="75" height="22">金额(元)</th>
    <th width="89" height="22">充值时间</th>
    <th width="93">领取</th>
    <th width="100">领取时间</th>
    <th width="269" height="22">定单号</th>
  </tr>
  <?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$I+($Ipage-1)*$pagesize?></td>
    <td align="center" nowrap class="forumRow"><?=$PayUser?></td>
    <td align="center" nowrap class="forumRow"><?=$SvrList[$SvrID]?></td>
    <td nowrap class="forumRow"><?=PayList($rs["type"])?></td>
    <td nowrap class="forumRow"><?=getIntTime($rs["time_stamp"])?></td>
    <td align="center" nowrap class="forumRow"><?=$rs["used"]==1?"已领取":"未领取"?></td>
    <td align="center" nowrap class="forumRow"><?if ($rs["used_time_stamp"] !=0000) echo getIntTime($rs["used_time_stamp"]);?></td>
    <td nowrap class="forumRow"><?=$rs["ref_id"]?></td>
  </tr>
<?
$I++;
}
?>
  <tr align="center"> 
    <td height="25" colspan="8" class="forumRow"> 
      <?=$page->show();?>    </td>
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