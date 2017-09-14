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
<title>充值查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<?
$PayCode=CheckStr($_REQUEST["PayCode"]);
$CPID=CheckStr($_REQUEST["CPID"]);
$rpCode=CheckStr($_REQUEST["rpCode"]);
$CardNO=CheckStr($_REQUEST["CardNO"]);
$PayName=CheckStr($_REQUEST["PayName"]);
$SvrID=CheckStr($_REQUEST["SvrID"]);
$OrderID=CheckStr($_REQUEST["OrderID"]);
$IsUC=CheckStr($_REQUEST["IsUC"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);
$hours=CheckStr($_REQUEST["hours"]);
$houre=CheckStr($_REQUEST["houre"]);
 $game_id=intval($_REQUEST["game_id"]);
 $dwFenBaoID=$_REQUEST["dwFenBaoID"];
 $clienttype=$_REQUEST["clienttype"];
 $s_money=$_REQUEST["s_money"];
 $e_money=$_REQUEST["e_money"];
//默认时间
if ($StarTime=="" && $EndsTime==""){
	//$StarTime=date("Y")."-".date("m")."-01";
	$StarTime=date("Y-m-d");
	$EndsTime=date("Y-m-d");
}
if($hours=='')
$hours='00';
if($houre=='')
$houre='23';
$StarTimet = $StarTime." ".$hours.":00:00";
$EndsTimet = $EndsTime." ".$houre.":59:59";

$sql=" And Add_Time >= '$StarTimet' And Add_Time <= '$EndsTimet'";
$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&hours=$hours&houre=$houre&game_id=$game_id&";

if ($CPID != ""){
	$sql=$sql." And CPID=$CPID";
	$PURL=$PURL."CPID=$CPID&";
}else
	//1是天猫魔盒充值	
	$sql=$sql." and CPID!=1";
if ($PayCode != ""){
	$sql=$sql." And PayCode='$PayCode'";
	$PURL=$PURL."PayCode=$PayCode&";
}
//成功状态
if ($rpCode != ""){
	if ($rpCode == "1") $sql=$sql." And rpCode in ('1','10')";
	if ($rpCode == "2") $sql=$sql." And rpCode not in ('1','10')";
	if ($rpCode == "3") $sql=$sql." And rpCode is null";
	$PURL=$PURL."rpCode=$rpCode&";
}
if ($CardNO != ""){
	$sql=$sql." And CardNO Like '$CardNO%'";
	$PURL=$PURL."CardNO=$CardNO&";
}
if ($PayName != ""){
	$sql=$sql." And PayName Like '$PayName%'";
	$PURL=$PURL."PayName=$PayName&";
}
if($game_id){
	$sql=$sql." And game_id=$game_id";
	$PURL=$PURL."game_id=$game_id&";
}
if ($SvrID != ""){
	$sql=$sql." And ServerID=$SvrID";
	$PURL=$PURL."SvrID=$SvrID&";
}
if ($OrderID != ""){
	$sql=$sql." And OrderID='$OrderID'";
	$PURL=$PURL."OrderID=$OrderID&";
}
if ($IsUC != ""){
	$sql=$sql." And IsUC=1";
	$PURL=$PURL."IsUC=$IsUC&";
}
if ($dwFenBaoID !==''&&$dwFenBaoID !==null){
	 $sql=$sql." And dwFenBaoID='$dwFenBaoID'";
	$PURL=$PURL."dwFenBaoID=$dwFenBaoID&";
}
if ($clienttype != ""){
	$sql=$sql." And clienttype='$clienttype'";
	$PURL=$PURL."clienttype=$clienttype&";
}
if ($s_money != ""){
	$sql=$sql." And PayMoney>='".intval($s_money)."'";
	$PURL=$PURL."s_money=$s_money&";
}
if ($e_money != ""){
    $sql=$sql." And PayMoney<='".intval($e_money)."'";
	$PURL=$PURL."e_money=$e_money&";
}

//设置客服查询权限，查询以下参数才显示列表
if (getFlag(306,$uFlag)){
	if ( ($PayName!='') || ($CardNO!='') || ($OrderID!='') ){
		$action="find";
	}
}else if(getFlag(809,$uFlag)){
	if ( ($PayName!='') || ($CardNO!='') || ($OrderID!='') ){
		$action="find_zg";
	}
}else{
	$action="findAll";
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="3" align="center">神州行、银行卡、骏网充值查询</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">充值渠道：</td>
      <td width="32%" class="forumRow"><?
$aTop="<select name=\"CPID\">\n";
$aTop=$aTop."<option value=''>—请选择—</option>\n";
foreach ($CPList as $aKey=>$aValue){
$aStr=$aStr."<option value='".$aKey."' ".SeleObject($aKey,$CPID).">".$aValue."</option>\n";
}
echo $aTop.$aStr."</select>\n";
$aStr='';//清空

$aTop="<select name=\"PayCode\">\n";
$aTop=$aTop."<option value=''>—全部—</option>\n";
foreach ($pCodeList as $aKey=>$aValue){
$aStr=$aStr."<option value='".$aKey."' ".SeleObject($aKey,$PayCode).">".$aValue."</option>\n";
}
echo $aTop.$aStr."</select>";
$aStr='';//清空
?>
	</td>
      <td width="53%" class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">帐号：</td>
      <td class="forumRow"><input name="PayName" type="text" value="<?=$PayName?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">定单号：</td>
      <td class="forumRow"><input name="OrderID" type="text" value="<?=$OrderID?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">卡号：</td>
      <td class="forumRow"><input name="CardNO" type="text" value="<?=$CardNO?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
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
	  <td align="right" class="forumRow">充值状态：</td>
      <td class="forumRow"><select name="rpCode">
        <option value="">—全部—</option>
        <option value="1" <?=SeleObject(1,$rpCode)?>>成功</option>
        <option value="2" <?=SeleObject(2,$rpCode)?>>失败</option>
        <option value="3" <?=SeleObject(3,$rpCode)?>>无回执</option>
      </select>
	  补单<input type="checkbox" name="IsUC" value="1" <?=ChecObject($IsUC,1)?>></td>
       <td rowspan="2" class="forumRow">&nbsp;</td>
	</tr>
        <tr>
      <td align="right" class="forumRow">机型：</td>
      <td width="" class="forumRow"><input name="clienttype" type="text" value="<?Php echo $clienttype;?>" >

        </td>
        <td rowspan="2" class="forumRow">&nbsp;</td>
    </tr>
           <tr>
      <td align="right" class="forumRow">金额(范围)：</td>
      <td width="" class="forumRow"><input name="s_money" type="text" value="<?Php echo $s_money;?>" >--<input name="e_money" type="text" value="<?Php echo $e_money;?>" >

        </td>
        <td rowspan="2" class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">经销商分包ID：</td>
      <td width="" class="forumRow"><input name="dwFenBaoID" type="text" value="<?php echo $dwFenBaoID;?>" >

        </td>
       
    </tr>
    <tr> 
      <td align="right" class="forumRow">充值时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
          <?getHours($hours,'s')?>
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"><?getHours($houre,'e')?></td>
      <td class="forumRow"><a href="News_Show.php?ID=218" target="_blank">充值说明文档</a>
	   <a href="News_Show.php?ID=212" target="_blank">状态编码查询</a></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow"></td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
      <td class="forumRow">定单号显示红色状态：写入游戏数据库失败</td>
    </tr>
  </form>
</table>
<?
if ( ($action=="find") || ($action=="findAll") || ($action=="find_zg")){
if($action=="find"){
    $SqlStr="select * from pay_log Where 1=1".$sql." and Add_Time >= '".date('Y-m-d H:i:s',time()-60*60*24*3)."' Order By Add_Time Desc";
}else{
    $SqlStr="select * from pay_log Where 1=1".$sql." Order By Add_Time Desc";
}
//echo $SqlStr;
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->pageSql();//格式化sql语句

$result=mysql_query($SqlStr);
?>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th width="60" height="22" align="center">编号 </th>
    <th width="80"  height="22" align="center">充值渠道</th>
    <th width="104" align="center">充值方式</th>
    <th width="84" height="22">帐号</th>
    <th width="82" height="22">金额</th>
    <th width="110" height="22">卡号</th>
    <th width="94">密码</th>
    <th width="95">银行编码</th>
    <th width="81" height="22">游戏</th>
    <th width="81" height="22">服务区</th>
    <th width="82">定单号</th>
    <th width="49">充值状态</th>
    <th width="137">提交时间</th>
    <th width="45" height="22">提交状态</th>
  </tr>
  <?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$I+($Ipage-1)*$pagesize?></td>
    <td nowrap class="forumRow"><?=$CPList[$rs["CPID"]]?></td>
    <td nowrap class="forumRow"><?=$pCodeList[$rs["PayCode"]]?></td>
    <td nowrap class="forumRow"><a href="<?=getPath()?>?PayName=<?=$rs['PayName']?>"><?=$rs["PayName"]?></a></td>
    <td nowrap class="forumRow"><?=$rs["PayMoney"]?></td>
    <td nowrap class="forumRow"><a href="<?=getPath()?>?CardNO=<?=$rs['CardNO']?>"><?=$rs["CardNO"]?></a></td>
    <td nowrap class="forumRow"><?=$rs["CardPwd"]?></td>
    <td class="forumRow"><?=$rs["BankID"]?></td>
    <td class="forumRow"><?=$game_arr[$rs["game_id"]]['name']?></td>
    <td nowrap class="forumRow"><a  href="Pay_SvrList.php?game_id=<?=$rs['game_id']?>&SvrID=<?=$rs['ServerID']?>&PayID=<?=$rs['PayID']?>&PayUser=<?=$rs['PayName']?>&OrderID=<?=$rs['OrderID']?>"><?=$game_arr[$rs["game_id"]]['server_list'][$rs["ServerID"]]?></a><?if($rs["ServerID"]==$sidyd)echo '移动梦网'?></td>
    <td class="forumRow"><?if ($rs["IsUC"]==1){
		echo "<font color=#ff0000>".$rs["OrderID"]."</font>";
	}else{
		echo $rs["OrderID"];
	}?></td>
    <td class="forumRow"><?if ($rs["rpCode"]==1 or $rs["rpCode"]==10){
		echo "成功";
	}else{
		echo $rs["rpCode"];
	}?></td>
    <td nowrap class="forumRow"><?=$rs["Add_Time"]?></td>
    <td class="forumRow"><?=$rs["SubStat"]?></td>
  </tr>
  <?
$I++;}
?>
  <tr> 
    <td height="25" colspan="13" align="center" class="forumRow"> 
      <?
if (getFlag(303,$uFlag)){
	if ($rpCode ==1 ){
		$SqlStr="select Sum(PayMoney) from pay_log where 1=1".$sql;
		//echo $SqlStr;
		$result=mysql_query($SqlStr);
		if ($result>0){
		$PayNum=mysql_result($result,0);
		}else{
		$PayNum=0;
		}
		echo "共<font color=red><b>".$PayNum."</b></font>元，";
	}
}
	  echo $page->show();	  
	  ?></td>
  </tr>
</table>
<?
}
?>
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