<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("inc/game_config.php");

$TabName="news_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];
$FlagValue=$_REQUEST["FlagValue"];
$num=count($_POST["AutoID"]);

if ($Action=="Del"){
    include("inc/web_config.php");
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST["AutoID"][$i];
		//删除文件
		$result=mysql_query("select ImgPath from ".$TabName." Where ID=$ID[$i]");
		$ImgPath=mysql_result($result,0);
		if ($ImgPath!=""){
			//如果文件存在
			if (file_exists($NewsPath.$ImgPath)){
			unlink($NewsPath.$ImgPath);
			}
		}
		//删除记录
		$sql="Delete from ".$TabName." Where ID=$ID[$i]";
		mysql_query($sql);
        file_get_contents(WEB_URL.'404.php?action=del_new&new_id='.$ID[$i]);
		//echo $sql."<br>";
		header("Location:".getPath());
	}
}
//设置热门
if ($Action=="SetHot"){
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST["AutoID"][$i];
		$sql="Update ".$TabName." Set IsHot='$FlagValue' Where ID=$ID[$i]";
		mysql_query($sql);
		header("Location:".getPath());
	}
}
//设置推荐
if ($Action=="SetComm"){
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST["AutoID"][$i];
		$sql="Update ".$TabName." Set IsComm='$FlagValue' Where ID=$ID[$i]";
		mysql_query($sql);
		header("Location:".getPath());
	}
}
//设置图片
if ($Action=="SetPic"){
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST["AutoID"][$i];
		$sql="Update ".$TabName." Set IsPic='$FlagValue' Where ID=$ID[$i]";
		mysql_query($sql);
		header("Location:".getPath());
	}
}
//设置审核
if ($Action=="SetHide"){
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST["AutoID"][$i];
		$sql="Update ".$TabName." Set IsHide='$FlagValue' Where ID=$ID[$i]";
		mysql_query($sql);
		header("Location:".getPath());
	}
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/ActionFrom.js"></script>
<SCRIPT LANGUAGE="JavaScript">
function del_to() {
	if (count_checked_items()>0){
		if(confirm("您真的要删除这些记录吗？")){
		document.form1.action="<?=getPath()?>?Action=Del";
		document.form1.submit();
		}
	}else{
		alert('请选择要删除的记录！');
		return false;
	}
}
function move_to() {
	document.form1.action="Product_Move.asp";
	document.form1.submit();
}
//标志为
function flag_to() {
	if (count_checked_items()>0){
		document.form1.action="<?=getPath()?>?Action="+document.form1.FlagTo.value;
		document.form1.submit();
	}else{
		alert('请您先选择要操作的记录！');
		document.form1.FlagTo.selectedIndex=-1;
		return false;
	}
}
//标志取消为
function Cancel_to() {
	if (count_checked_items()>0){
		document.form1.action="<?=getPath()?>?Action="+document.form1.CancelTo.value;
		document.form1.submit();
	}else{
		alert('请您先选择要操作的记录！');
		document.form1.CancelTo.selectedIndex=-1;
		return false;
	}
}
</SCRIPT>
</head>
<body class="main">
<?
$NewsTitle=CheckStr($_REQUEST["NewsTitle"]);
$NewsType=$_REQUEST["NewsType"];
$NewsKey=CheckStr($_REQUEST["NewsKey"]);
$IsHot=$_REQUEST["IsHot"];
$IsComm=$_REQUEST["IsComm"];
$IsPic=$_REQUEST["IsPic"];
$IsHide=$_REQUEST["IsHide"];
$StarTime=$_REQUEST["StarTime"];
$EndsTime=$_REQUEST["EndsTime"];
$game_id=$_REQUEST["game_id"];
if ($StarTime != "" && $EndsTime != ""){
	$sql=$sql." And A.Add_Time > '$StarTime' And A.Add_Time < DATE_ADD('$EndsTime',INTERVAL 1 DAY)";
	$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&";
}
if ($NewsTitle != ""){
	$sql=$sql." And NewsTitle Like '%$NewsTitle%'";
	$PURL=$PURL."NewsTitle=$NewsTitle&";
}
if ($NewsKey != ""){
	$sql=$sql." And NewsKey Like '%$NewsKey%'";
	$PURL=$PURL."NewsKey=$NewsKey&";
}
if ($NewsType != ""){
	$sql=$sql." And NewsType=$NewsType";
	$PURL=$PURL."NewsType=$NewsType&";
}
if ($IsHot != ""){
	$sql=$sql." And IsHot='$IsHot'";
	$PURL=$PURL."IsHot=$IsHot&";
}
if ($IsComm != ""){
	$sql=$sql." And IsComm='$IsComm'";
	$PURL=$PURL."IsComm=$IsComm&";
}
if ($IsPic != ""){
	$sql=$sql." And IsPic='$IsPic'";
	$PURL=$PURL."IsPic=$IsPic&";
}
if ($IsHide != ""){
	$sql=$sql." And A.IsHide='$IsHide'";
	$PURL=$PURL."IsHide=$IsHide&";
}
if ($game_id != ""){
	$sql=$sql." And B.game_id='$game_id'";
	$PURL=$PURL."game_id=$game_id&";
}
$SqlStr="select A.*,B.ClassName from ".$TabName." A Left Join news_class B On A.NewsType=B.ClassID Where 1=1".$sql." Order By Add_Time Desc";
//echo $SqlStr;
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->PageSql();//格式化sql语句
$result=mysql_query($SqlStr);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">管理</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">标题：</td>
      <td width="85%" class="forumRow"><input name="NewsTitle" type="text" size="30" value="<?=$NewsTitle?>"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">关键字：</td>
      <td width="85%" class="forumRow"><input name="NewsKey" type="text" size="30" value="<?=$NewsKey?>"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">标识</td>
      <td width="85%" class="forumRow"><input type="checkbox" name="IsHot" value="1" <?=ChecObject($IsHot,"1")?>>
        热门 
        <input type="checkbox" name="IsComm" value="1" <?=ChecObject($IsComm,"1")?>>
        推荐 
        <input type="checkbox" name="IsPic" value="1" <?=ChecObject($IsPic,"1")?>>
        包含图片
        <input type="checkbox" name="IsHide" value="1" <?=ChecObject($IsHide,"1")?>>
        待审核</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableBorder">
  <tr> 
    <td height="20" class="forumRow">
	<a href="<?=getPath()?>">显示所有信息</a><br/>
	
	<?php
	 if($game_arr){
	      foreach ($game_arr as $key=>$val){
				echo "<a href='".getPath()."?game_id=$key'>".$val['name']."信息</a><br/>"; 
				$SqlMain="select ClassID,ClassName from news_class where game_id='$key' and ParentID!='0' order by OrderID";
				$conn=mysql_query($SqlMain);
				$Str='';
				while($Rs_Main=mysql_fetch_array($conn))
				{	
					$Str.="<a href=".getPath()."?NewsType=".$Rs_Main[0].">";
					if ($NewsType==$Rs_Main['ClassID']){
						$Str.="<font color=red>".$Rs_Main[1]."</font>";
					}else{
						$Str.=$Rs_Main[1];
					}
					$Str.="</a> | ";
				}
				echo substr($Str,0,-2),'<br/>';
	        }
      }
	?>
	</td>
  </tr>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr height="22"> 
    <th width="28" height="22" align="center"><input type="checkbox" name="check_all" value="1" onClick="select_ok();"></th>
    <th width="308"  height="22" align="center">标题</th>
    <th width="140" height="22">关键字</th>
    <th width="93" height="22">分类</th>
    <th width="48" height="22">点击</th>
    <th width="96" height="22">时间</th>
    <th width="40">操作</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr bgcolor="#ECECED" id="t<?=$rs[0]?>">
    <td height="22" align="center" nowrap><input name="AutoID[]" type="checkbox" value="<?=$rs[0]?>" onClick="if(this.checked){t<?=$rs[0]?>.style.backgroundColor='#DBEAF5';}else{t<?=$rs[0]?>.style.backgroundColor='#ECECED';}"></td>
    <td nowrap><?
	if ($rs['TitleColor'] != ""){
	$nTitle="<font color=".$rs['TitleColor'].">".$rs['NewsTitle']."</font>";
	}else{
	$nTitle=$rs['NewsTitle'];
	}
	?>
	<a href="News_Show.php?ID=<?=$rs[ID]?>"><?=$nTitle?></a>
	<?
if ($rs['IsPic']=="1" || $rs['ImgPath'] != "") echo "<font color=red>[图]</font>";
if ($rs['IsComm']=="1") echo "<font color=blue>[推荐]</font>";
if ($rs['IsIndex']=="1") echo "<font color=#666666>[首页]</font>";
if ($rs['IsHide']=="1") echo "<font color=green>[待审核]</font>";
if ($rs['IsHot']=="1") echo "<img src='Images/hot.gif' border='0'>";
?></td>
    <td nowrap><?=$rs['NewsKey']?></a></td>
      <td align="center" nowrap><a href="<?=getPath()?>?NewsType=<?=$rs[NewsType]?>"><?=$rs['ClassName']?></a></td>
      <td align="center" nowrap><?=$rs['HitCount']?></td>
    <td nowrap><?=$rs['Add_Time']?></td>
	<td align="center"><a href="News_Edit.php?ID=<?=$rs[0]?>">修改</a></td>
  </tr>
<?
$I++;
}
?>
    <tr> 
    <td height="25" colspan="7" class="forumRow"><input class="bott01" name="button_select" type="button" onClick="javascript:select_change();" value=" 全 选 " accesskey="a"> 
        <input class="bott01" name="del" type="button" onClick="javascript:del_to();" value=" 删 除 ">
        <select name="FlagTo" onChange="flag_to();">
          <option selected>标志为</option>
          <option value="SetHot&FlagValue=1">设置热门</option>
          <option value="SetComm&FlagValue=1">设置推荐</option>
          <option value="SetPic&FlagValue=1">包含图片</option>
          <option value="SetHide&FlagValue=0">设置发布</option>
        </select> <select name="CancelTo" onChange="Cancel_to();">
          <option selected>取消为</option>
          <option value="SetHot&FlagValue=0">取消热门</option>
          <option value="SetComm&FlagValue=0">取消推荐</option>
          <option value="SetPic&FlagValue=0">取消图片</option>
          <option value="SetHide&FlagValue=1">取消发布</option>
        </select></td>
  </tr>
  </form>
  <tr> 
    <td height="25" colspan="7" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
</body>
</html>