<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$linkType=$_REQUEST["linkType"];
$Action=$_REQUEST["Action"];


if ($Action=="Order"){
	$news_str=$_REQUEST["released_news_str"];
	$news_list=explode(",",$news_str);
	$N=count($news_list);
	for ($I=0; $I<=$N-2; $I++) {
		$sql="Update link_data Set orderID=$I Where id=$news_list[$I] and linkType=$linkType";
		//echo $sql."<br>";
		mysql_query($sql);
	}	
	header("Location: ?linkType=$linkType");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>智能排序</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?
if ($linkType!=""){
	$sqlstr=" And linkType=$linkType Order By OrderID";
}
$sql="select id,linkName,linkUrl from link_data where isHide=0".$sqlstr;
$result=mysql_query($sql);
$RowNum=mysql_num_rows($result);
if ($RowNum==0){
echo "暂无内容，不能进行排序！";
}else{
?>
<script language="javascript">
	function upsel(){
		var findex=document.form2.news_link_select.selectedIndex;
		if(findex < 1) return;
		var ftext=document.form2.news_link_select.options[findex].text;
		var fvalue=document.form2.news_link_select.options[findex].value;
		var qvalue=document.form2.news_link_select.options[findex-1].value;
		var qtext=document.form2.news_link_select.options[findex-1].text;
		document.form2.news_link_select.options[findex-1].text=ftext; 
		document.form2.news_link_select.options[findex-1].value=fvalue;
		document.form2.news_link_select.options[findex].text=qtext;
		document.form2.news_link_select.options[findex].value=qvalue;
		document.form2.news_link_select.selectedIndex=findex-1;
	}
	function downsel()	{
		var nIndex=document.form2.news_link_select.length;
		var findex=document.form2.news_link_select.selectedIndex;
		if((findex >= nIndex-1) || findex < 0) return;
		var fvalue=document.form2.news_link_select.options[findex].value;
		var ftext=document.form2.news_link_select.options[findex].text;
		var hvalue=document.form2.news_link_select.options[findex+1].value;
		var htext=document.form2.news_link_select.options[findex+1].text;
		document.form2.news_link_select.options[findex+1].text=ftext; 
		document.form2.news_link_select.options[findex+1].value=fvalue;
		document.form2.news_link_select.options[findex].text=htext;
		document.form2.news_link_select.options[findex].value=hvalue;
		document.form2.news_link_select.selectedIndex=findex+1;
	}
	function released_news_str1(){
		document.form2.button_qd.disabled=true;
		var link_str="";
		var current_link_id;
		
		for(var i=0; i<(document.form2.news_link_select.options.length); i++){
				
				current_link_id=document.form2.news_link_select.options[i].value+",";
				link_str+=current_link_id;				
			}
		document.form2.released_news_str.value=link_str;
		document.form2.submit();
		}
		</script>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
<form name="form2" action="?Action=Order" method="post">
    <tr> 
      <th height="25" colspan=6>　顺序排列</th>
		</tr>
		<tr> 
      <td align="center" class="forumRow"> 
	<select size=5 name="news_link_select" multiple style="WIDTH: 220px;HEIGHT: 350px;" language=javascript >
	<? while($rs=mysql_fetch_array($result)){?>
	<option value="<?=$rs["id"]?>"><?=$rs["linkName"]?></option>
	<? }?>
	</select>
	<input type="hidden" name="released_news_str">
	<input type="hidden" name="linkType" value="<?=$linkType?>">
      </td>
      <td class="forumRow"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
          <tr > 
            <td height="40"> <input type="button" name="button_3" value="↑上  移" class="box" style="WIDTH: 70px;HEIGHT: 22px;BACKGROUND-COLOR: #ffffff" onClick="upsel();"> 
            </td>
          </tr>
          <tr > 
            <td height="38"> <input type="button" name="button_32" value="↓下  移" class="box" style="WIDTH: 70px;HEIGHT: 22px;BACKGROUND-COLOR: #ffffff" onClick="downsel();"> 
            </td>
          </tr>
          <tr > 
            <td height="40"> <input type="button" name="button_34" value="恢  复" class="box" style="WIDTH: 70px;HEIGHT: 22px;BACKGROUND-COLOR: #ffffff" onClick="javascript:location.href='?linkType=<?=$linkType?>';"> 
            </td>
          </tr>
          <tr > 
            <td height="40"> <input type="button" name="button_qd" value="确  定" class="box" style="WIDTH: 70px;HEIGHT: 22px;BACKGROUND-COLOR: #ffffff" onClick="javascript:released_news_str1();"> 
            </td>
          </tr>
          <tr >
            <td height="40"><input type="button" name="back" value="返  回" class="box" style="WIDTH: 70px;HEIGHT: 22px;BACKGROUND-COLOR: #ffffff" onClick="window.location='link_List.php?linkType=<?=$linkType?>'"></td>
          </tr>
        </table>
      </td>
		</tr>
	  </form>
	 </table>                           
<?
}
mysql_close();
?>
</body>
</html>