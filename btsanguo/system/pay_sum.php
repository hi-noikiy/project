<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<?
$CPID=CheckStr($_REQUEST["CPID"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);
$hours=CheckStr($_REQUEST["hours"]);
$houre=CheckStr($_REQUEST["houre"]);
$dwFenBaoID=$_REQUEST["dwFenBaoID"];
$clienttype = $_REQUEST["clienttype"];
$game_id = $_REQUEST["game_id"];
$SvrID = $_REQUEST["SvrID"];
//默认时间
if ($StarTime=="" && $EndsTime==""){
	$StarTime=date('Y-m-d',strtotime("-7day"));//统计前7天
	$EndsTime=date("Y-m-d");
}
if($hours=='')
$hours='00';
if($houre=='')
$houre='23';
$StarTimet = $StarTime." ".$hours.":00:00";
$EndsTimet = $EndsTime." ".$houre.":59:59";
#在数据库时间条件加1天
$sql=" And Add_Time >= '$StarTimet' And Add_Time <= '$EndsTimet'";
$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&hours=$hours&houre=$houre&";
//统计方式
$cType=$_REQUEST["cType"];
$PURL=$PURL."cType=$cType&";

if ($CPID != ""){
	$sql=$sql." And CPID=$CPID";
	$PURL=$PURL."CPID=$CPID&";
}else
	//1是天猫魔盒充值	
	$sql=$sql." and CPID!=1";
if ($game_id != 0){
	$sql=$sql." And game_id=$game_id";
	$PURL=$PURL."game_id=$game_id&";
}
if ($SvrID != 0){
	$sql=$sql." And ServerID=$SvrID";
	$PURL=$PURL."SvrID=$SvrID&";
}
if ($dwFenBaoID!=''){
	$sql=$sql." And dwFenBaoID='$dwFenBaoID'";
	$PURL=$PURL."dwFenBaoID=$dwFenBaoID&";
}
if ($clienttype){
	$sql=$sql." And clienttype='$clienttype'";
	$PURL=$PURL."clienttype=$clienttype&";
}

//统计充值成功数据
if ($cType==2){
	//按充值渠道
	$SqlStr="select CPID,sum(PayMoney) as payTota,count(distinct PayID ) count_PayID from pay_log";
	$SqlStr=$SqlStr." Where rpCode in ('1','10')".$sql." group by CPID";
}else{
	//按时间统计
	$SqlStr="select sum(PayMoney) as payTota,CAST(add_Time AS date) as totaDay,count(distinct PayID ) count_PayID from pay_log";
	$SqlStr=$SqlStr." Where rpCode in ('1','10')".$sql." group by CAST(add_Time AS date)";
	$SqlStr=$SqlStr." order by CAST(add_Time AS date) desc";
}
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->pageSql();//格式化sql语句


$result=mysql_query($SqlStr);
//echo $SqlStr;
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">查询</th>
    </tr>

        <tr>
      <td align="right" class="forumRow">游戏：</td>
      <td class="forumRow">
      <select name="game_id" onchange="change_game(this.value)">
           <option value='' >选择游戏</option>
            <?php
//               for($i=1;$i<=count($game_arr);$i++){
//                  echo "<option value=\"".$i." \" ".(($i==$game_id)?' selected="selected"':'')." >".$game_arr[$i]['name']."</option>";
//               }

                if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".($game_id==$key?'selected':'')." >$val[name]</option>";
                                }
                            }

            ?>
            </select>
       </td>
      <td class="forumRow">&nbsp;</td>
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
	  <td rowspan="2" class="forumRow">&nbsp;</td>
	</tr>

        <tr>
      <td width="15%" align="right" class="forumRow">充值渠道：</td>
      <td width="85%" class="forumRow"><select name="CPID">
<option value="">—请选择—</option><?
foreach ($CPList as $aKey=>$aValue){
$str=$str."<option value=".$aKey." ".SeleObject($aKey,$CPID).">".$aValue."</option>\n";
}
echo $str;
?>
</select></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">统计时间：</td>
      <td width="85%" class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
          <?getHours($hours,'s')?>
～
  <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"><?getHours($houre,'e')?></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">统计方式：</td>
      <td width="85%" class="forumRow"><input name="cType" type="radio" value="1" checked <?=ChecObject($cType,1)?>>
      按时间
	  <input name="cType" type="radio" value="2" <?=ChecObject($cType,2)?>>
      按渠道
        </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">机型：</td>
      <td width="85%" class="forumRow"><input name="clienttype" type="text" value="<?Php echo $clienttype;?>" >

        </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">经销商分包ID：</td>
      <td width="85%" class="forumRow"><input name="dwFenBaoID" type="text" value="<?php echo $dwFenBaoID;?>" >

        </td>
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
    <th width="51" height="22" align="center">编号 </th>
    <th width="153"  height="22">日期</th>
    <th width="153" height="22">金额(元)</th>
    <th width="153"><span class="forumRow">充值渠道</span></th>
    <th width="153">充值人数</th>
  </tr>
  <?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$I+($Ipage-1)*$pagesize?></td>
    <td nowrap class="forumRow"><?if ($cType==2){
		echo $StarTime." 至 ".$EndsTime;
	}else{
		echo $rs["totaDay"];
	}
	?></td>
    <td nowrap class="forumRow"><?=$rs["payTota"]?></td>
    <td nowrap class="forumRow"><?if ($cType==2){
		echo $CPList[$rs["CPID"]];
	}else{
		echo "全部";
		}
		?></td>
     <td nowrap class="forumRow"><?=$rs["count_PayID"]?></td>
  </tr>
  <?
$I++;}
?>
  <tr> 
    <td height="25" colspan="4" align="center" class="forumRow"><?
	  echo $page->show();
	  mysql_close();
	  ?></td>
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