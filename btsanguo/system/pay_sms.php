<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
if (!getFlag('304',$uFlag)){
	header("Location: Adm_Login.php");
}
?>
<html>
<head>
<title>充值查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
</head>
<body class="main">
<?
//更新掉单数据
//$sql="update pay_sms set rpCode='DELIVRD' where LinkID in('11142113063341624774')";
//mysql_query($sql);
$SPNO=CheckStr($_REQUEST["SPNO"]);
$UserNumber=CheckStr($_REQUEST["UserNumber"]);
$PayName=CheckStr($_REQUEST["PayName"]);
$SvrID=CheckStr($_REQUEST["SvrID"]);
$rpCode=CheckStr($_REQUEST["rpCode"]);
$LinkID=CheckStr($_REQUEST["LinkID"]);
$moMsg=CheckStr($_REQUEST["moMsg"]);
$IsUC=CheckStr($_REQUEST["IsUC"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);
//默认时间
if ($StarTime=="" && $EndsTime==""){
	//$StarTime=date("Y")."-".date("m")."-01";
	//$StarTime=date('Y-m-d',strtotime("-7day"));//统计前7天
	$StarTime=date("Y-m-d");
	$EndsTime=date("Y-m-d");
}
#在数据库时间条件加1天
$sql=" And Add_Time >= '$StarTime' And Add_Time < DATE_ADD('$EndsTime',INTERVAL 1 DAY)";
$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&";

//成功状态
if ($rpCode != ""){
	if ($rpCode == "DELIVRD"){
		$sql=$sql." And rpCode='DELIVRD'";
	}else{
		$sql=$sql." And (rpCode<>'DELIVRD' or rpCode is null)";
	}
	$PURL=$PURL."rpCode=$rpCode&";
}
if ($SPNO != ""){
	$sql=$sql." And SPNO Like '$SPNO%'";
	$PURL=$PURL."SPNO=$SPNO&";
}
if ($UserNumber != ""){
	$sql=$sql." And UserNumber Like '$UserNumber%'";
	$PURL=$PURL."UserNumber=$UserNumber&";
}
if ($PayName != ""){
	$sql=$sql." And PayName Like '$PayName%'";
	$PURL=$PURL."PayName=$PayName&";
}
if ($SvrID != ""){
	$sql=$sql." And ServerID=$SvrID";
	$PURL=$PURL."SvrID=$SvrID&";
}
if ($LinkID != ""){
	$sql=$sql." And LinkID='$LinkID'";
	$PURL=$PURL."LinkID=$LinkID&";
}
if ($moMsg != ""){
	$sql=$sql." And moMsg Like '%$moMsg%'";
	$PURL=$PURL."moMsg=$moMsg&";
}
if ($IsUC != ""){
	$sql=$sql." And IsUC=1";
	$PURL=$PURL."IsUC=$IsUC&";
}

$SqlStr="select * from pay_sms Where 1=1".$sql." Order By Add_Time Desc";
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
      <th height="22" colspan="3" align="center">短信充值查询</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">服务号码：</td>
      <td width="32%" class="forumRow"><input name="SPNO" type="text" value="<?=$SPNO?>" size="30"></td>
      <td width="53%" class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">手机号码：</td>
      <td class="forumRow"><input name="UserNumber" type="text" value="<?=$UserNumber?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">帐号：</td>
      <td class="forumRow"><input name="PayName" type="text" value="<?=$PayName?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">上行内容：</td>
      <td class="forumRow"><input name="moMsg" type="text" value="<?=$moMsg?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
	<tr> 
      <td align="right" class="forumRow">服务区：</td>
      <td class="forumRow"><?
    $aTop="<select name=\"SvrID\">\n";
	$aTop=$aTop."<option value=''>所有服务区</option>\n";
	foreach ($SvrList as $aKey=>$aValue){
	$aStr=$aStr."<option value=".$aKey." ".SeleObject($aKey,$SvrID).">".$aValue."</option>\n";
	}
	echo $aTop.$aStr."</select>";
	?>
  充值状态
<select name="rpCode">
<option value="">—全部—</option>
<option value="DELIVRD" <?=SeleObject("DELIVRD",$rpCode)?>>成功</option>
<option value="lost" <?=SeleObject("lost",$rpCode)?>>失败</option>
</select>
  补单<input type="checkbox" name="IsUC" value="1" <?=ChecObject($IsUC,1)?>>
	  </td>
      <td class="forumRow">&nbsp;</td>
	</tr>
    <tr> 
      <td align="right" class="forumRow">充值时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)">      </td>
      <td class="forumRow"><a href="News_Show.php?ID=211" target="_blank">短信充值说明文档</a></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow"></td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
      <td class="forumRow">定单号显示灰色状态：写入游戏数据库失败；状态在72小时内有效</td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th width="60" height="22" align="center">编号 </th>
    <th width="80"  height="22" align="center">手机号码</th>
    <th width="104" align="center">帐号</th>
    <th width="84" height="22">金额(元)</th>
    <th width="194" height="22"><span class="forumRow">服务号码</span></th>
    <th width="94">上行内容</th>
    <th width="178" height="22">服务区</th>
    <th width="167">定单号(LinkID)</th>
    <th width="178">充值时间</th>
    <th width="61" height="22">状态</th>
  </tr>
  <?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$I+($Ipage-1)*$pagesize?></td>
    <td nowrap class="forumRow"><a href="<?=getPath()?>?UserNumber=<?=$rs[UserNumber]?>"><?=$rs["UserNumber"]?></a></td>
    <td nowrap class="forumRow"><a href="<?=getPath()?>?PayName=<?=$rs[PayName]?>"><?=$rs["PayName"]?></a></td>
    <td nowrap class="forumRow"><?=$rs["PayMoney"]?></td>
    <td nowrap class="forumRow"><?=$rs["SPNO"]?></td>
    <td nowrap class="forumRow"><?=$rs["moMsg"]?></td>
    <td class="forumRow"><a href="Pay_SvrList.php?SvrID=<?=$rs[ServerID]?>&PayID=<?=$rs[PayID]?>&PayUser=<?=$rs[PayName]?>&OrderID=<?=$rs[LinkID]?>"><?=$SvrList[$rs["ServerID"]]?></a></td>
    <td class="forumRow"><?if ($rs["IsUC"]==1){
		echo "<font color=#666666>".$rs["LinkID"]."</font>";
	}else{
		echo $rs["LinkID"];
	}?></td>
    <td class="forumRow"><?=$rs["Add_Time"]?></td>
    <td class="forumRow"><?=$rs["rpCode"]=="DELIVRD"?"成功":$rs["rpCode"]?></td>
  </tr>
  <?
$I++;}
?>
  <tr> 
    <td height="25" colspan="10" align="center" class="forumRow"> 
      <?
if (getFlag(304,$uFlag)){
	$SqlStr="select Sum(PayMoney) from pay_sms where rpCode='DELIVRD'".$sql;
	//echo $SqlStr;
	$result=mysql_query($SqlStr);
	if ($result>0){
	$PayNum=mysql_result($result,0);
	}else{
	$PayNum=0;
	}
	echo "共<font color=red><b>".$PayNum."</b></font>元，";
}
	  echo $page->show();

mysql_close();	  
	  ?></td>
  </tr>
</table>
</body>
</html>