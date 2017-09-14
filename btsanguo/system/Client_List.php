<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("inc/game_config.php");

$TabName="client_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$ID=CheckStr($_REQUEST["ID"]);
if ($Action=="Del"){
	$num=count($_POST['AutoID']);
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST['AutoID'][$i];
		//删除文件
		$result=mysql_query("select ImgPath from ".$TabName." Where ID=$ID[$i]");
		$ImgPath=mysql_result($result,0);
		//如果文件存在
		if(file_exists($NewsPath.$ImgPath)){
		unlink($NewsPath.$ImgPath);
		}
		//删除记录
		$sql="Delete from ".$TabName." Where ID=$ID[$i]";
		mysql_query($sql);
		//echo $sql."<br>";
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
	if(confirm("您真的要删除这些记录吗？")){
	document.form1.action="<?=getPath()?>?Action=Del";
	document.form1.submit();
	}
}
</SCRIPT>
</head>
<body class="main">
<?
$cType=$_REQUEST["cType"];
$IsHide=$_REQUEST["IsHide"];
$IsHot=$_REQUEST["IsHot"];
$cName=CheckStr($_REQUEST["cName"]);
$content=CheckStr($_REQUEST["content"]);
$StarTime=$_REQUEST["StarTime"];
$EndsTime=$_REQUEST["EndsTime"];
$game_id=intval($_REQUEST["game_id"]);

if ($cName != ""){
	$sql=$sql." And cName Like '$cName%'";
	$PURL=$PURL."cName=$cName&";
}
if ($cType != ""){
	$sql=$sql." And cType=$cType";
	$PURL=$PURL."cType=$cType&";
}
if ($IsHide != ""){
	$sql=$sql." And A.IsHide='$IsHide'";
	$PURL=$PURL."IsHide=$IsHide&";
}
if ($IsHot != ""){
	$sql=$sql." And A.IsHot='$IsHot'";
	$PURL=$PURL."IsHot=$IsHot&";
}
if ($content != ""){
	$sql=$sql." And content Like '%$content%'";
	$PURL=$PURL."content=$content&";
}
if ($game_id != ""){
	$sql=$sql." And A.game_id ='$game_id'";
	$PURL=$PURL."game_id=$game_id&";
}

$SqlStr="select A.*,B.ClassName from ".$TabName." A Left Join down_class B On A.cType=B.ClassID Where 1=1".$sql." Order By Add_Time Desc";
//echo $SqlStr;
//分页参数
$pagesize=10;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->PageSql();//格式化sql语句
$result=mysql_query($SqlStr);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">下载管理</th>
    </tr>
    <tr>
      <td width="15%" align="right" class="forumRow">游戏分类：</td>
      <td width="85%" class="forumRow">
      <select name='game_id'>
      <?php
      if($game_arr){
	      foreach ($game_arr as $key=>$val){
	      	echo "<option value='$key' ".(($key==$game_id)?"selected":"")." >".$val['name']."</option>";
	      }
      }
      ?>
      </select>    </td>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">系列：</td>
      <td width="85%" class="forumRow"><input name="cName" type="text" value="<?=$cName?>">      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">支持型号：</td>
      <td width="85%" class="forumRow"><input name="content" type="text" value="<?=$content?>">      </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">标记：</td>
      <td width="85%" class="forumRow"><input type="checkbox" name="IsHide" value="1" <?=ChecObject($IsHide,"1")?>>
        待审核 &nbsp;&nbsp;<input type="checkbox" name="IsHot" value="1" <?=ChecObject($IsHot,"1")?>>热机</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)">      </td>
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
	<a href="<?=getPath()?>">显示信息</a>
	<?
$SqlMain="select ClassID,ClassName from news_class where ParentID=2 order by OrderID";
$conn=mysql_query($SqlMain);
while($Rs_Main=mysql_fetch_array($conn))
{	
	$Str=" | <a href=".getPath()."?cType=".$Rs_Main[0].">";
	if ($cType==$Rs_Main[ClassID]){
	$Str=$Str."<font color=red>".$Rs_Main[1]."</font>";
	}else{
	$Str=$Str.$Rs_Main[1];
	}
	echo $Str."</a>";
}
echo " | <a href='Client_Hot.php'>热机排序 </a>";
	?>
	</td>
  </tr>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr height="22"> 
    <th width="33" height="22" align="center"><input type="checkbox" name="check_all" value="1" onClick="select_ok();"></th>
    <th width="158"  height="22" align="center">游戏分类</th>
    <th width="158"  height="22" align="center">系列</th>
    <th width="404" height="22">支持型号</th>
    <th width="115" height="22">品牌</th>
    <th width="115" height="22">热机</th>
    <th width="88" height="22">点击</th>
    <th width="72" height="22">增加时间</th>
    <th width="81">操作</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr bgcolor="#ECECED" id="t<?=$rs[0]?>">
    <td height="22" align="center" nowrap><input name="AutoID[]" type="checkbox" value="<?=$rs[0]?>" onClick="if(this.checked){t<?=$rs[0]?>.style.backgroundColor='#DBEAF5';}else{t<?=$rs[0]?>.style.backgroundColor='#ECECED';}"></td>
    <td align="center"><?=$game_arr[$rs['game_id']]['name']?></td>
    <td><a href="Client_Edit.php?ID=<?=$rs[0]?>&game_id=<?=$rs['game_id']?>"><?=$rs[cName]?></a>
	<?
	//if ($rs[IsJad]=="1" ) echo "<font color=blue>[jad]</font>";
	if ($rs[IsHide]=="1" ) echo "<font color=green>[待审核]</font>";
	?>
    <br>
    <font color="#999999"><?=$rs[cPath]?></font></td>
    <td><?=$rs[content]?></a></td>
    <td nowrap><?=$rs[ClassName]?></td>
    <td nowrap><?=$rs[IsHot]=='0'?'否':'是'?></td>
    <td nowrap><?=$rs[HitCount]?></td>
    <td nowrap><?=$rs[Add_Time]?></td>
	<td align="center"><a href="Client_Edit.php?ID=<?=$rs[0]?>&game_id=<?=$rs['game_id']?>">修改</a></td>
  </tr>
<?
$I++;
}
?>
    <tr> 
    <td height="25" colspan="9" class="forumRow"><input class="bott01" name="button_select" type="button" onClick="javascript:select_change();" value=" 全 选 " accesskey="a">
        <input class="bott01" name="del" type="button" onClick="javascript:del_to();" value=" 删 除 "></td>
  </tr>
  </form>
  <tr> 
    <td height="25" colspan="9" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
</body>
</html>