<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$TabName="news_data";
$ID=CheckStr($_REQUEST["ID"]);

$sql="select * from ".$TabName." where ID=$ID";
$result=mysql_query($sql);
$rs=mysql_fetch_array($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title><?=$rs[NewsTitle]?></title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <tr> 
    <th colspan="2" align="center"><?=$rs['NewsTitle']?></th>
  </tr>
  <tr> 
    <td width="15%" align="right" class="forumRow">标题： </td>
    <td width="85%" class="forumRow"><?=$rs[NewsTitle]?></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">分类：</td>
    <td class="forumRow"> 
<?
$result=mysql_query("select ClassName from news_class Where ClassID=".$rs['NewsType']);
$NewsName=mysql_result($result,0);
echo $NewsName;
?>
    </td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">来源：</td>
    <td class="forumRow"><?=$rs['NewsFrom']?></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">关键字：</td>
    <td class="forumRow"><?=$rs['NewsKey']?></td>
  </tr>
  <tr> 
    <td colspan="2" class="forumRow gao150"><?=$rs['Content']?></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">编者：</td>
    <td class="forumRow"><?=$rs['AddOper']?></td></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">点击率：</td>
    <td class="forumRow"><?=$rs['HitCount']?></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">已上传：</td>
    <td class="forumRow"> 
      <?
      $img_arr_old = unserialize($rs['img_arr']);
        for($i=0;$i<count($img_arr_old);$i++){
            if($img_arr_old[$i]){
               echo "<img src=".$NewsPath.$img_arr_old[$i].">";
            }
        }
	  if ($rs['ImgPath']!=""){
	  echo "<img src=".$NewsPath.$rs['ImgPath'].">";
	  }
	  ?>
    </td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">发布时间：</td>
    <td class="forumRow"><?=$rs['Add_Time']?></td>
  </tr>
  <tr> 
    <td align="right" class="forumRow">&nbsp;</td>
    <td class="forumRow"><?//加入权限
	if (getFlag('101',$uFlag)){?><a href="News_Edit.php?ID=<?=$ID?>">[修 改]</a>　<a href="javascript:history.back();">[返 回]</a><?}?></td>
  </tr>
</table>
</body>
</html>