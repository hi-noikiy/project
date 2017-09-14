<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
$nTypeTwo=$_COOKIE["nTypeTwo"];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>add</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/jquery-1.3.2.min.js"></script>
<script language = "JavaScript">
function CheckForm(){
	if (document.form1.NewsTitle.value=="")
	{
		alert("标题不能为空！");
		document.form1.NewsTitle.focus();
		return false;
	}
	if (document.form1.NewsType.value=="")
	{
		alert("请选择分类！");
		document.form1.NewsType.focus();
		return false;
	}	
}
function setNewsList(val){
	$.ajax({ 
		url: 'ajax/getNewsList.php', //接收页面
		type: 'post', //POST方式发送数据 
		async: false, //ajax同步 
		data: 'id='+val, 
		success: function(msg) { 
			 $("#NewsType").html(msg);
		} 
	});
}
</script>
<script language = "JavaScript">
    function more_img(){
        $("#tr_more_img2").show();
        $("#tr_more_img3").show();
        $("#tr_more_img4").show();
        $("#tr_more_img5").show();
    }
</script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="form1" method="POST" onSubmit="return CheckForm();" enctype="multipart/form-data" action="News_Save.php?Action=Add">
    <tr> 
      <th colspan="2" align="center">新增</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">标题： </td>
      <td width="85%" class="forumRow"><input name="NewsTitle" type="text" size="50">
        * <?=getColor("")?></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">游戏分类：</td>
      <td class="forumRow"> 
      <select name='game_id' onchange="setNewsList(this.value)">
      <option value='0'>请选择游戏分类</option>
      <?php
      if($game_arr){
	      foreach ($game_arr as $key=>$val){
	      	echo "<option value='$key'>".$val['name']."</option>";
	      }
      }
      ?>
      </select>
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">信息分类：</td>
      <td class="forumRow" id="NewsType">
	      <select name=NewsType>
	      	<option value=''>无</option>
	      </select>
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">来源：</td>
      <td class="forumRow"><input name="NewsFrom" type="text" size="50"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">关键字：</td>
      <td class="forumRow"><input name="NewsKey" type="text" size="50"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">WEB内容：</td>
      <td class="forumRow"><textarea name="Content" style="display:none"></textarea> 
		  <IFRAME ID="eWebEditor1" SRC="WebEdit/ewebeditor.htm?id=Content&style=coolblue" FRAMEBORDER="0" SCROLLING="no" WIDTH="550" HEIGHT="450"></IFRAME>      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">WAP内容：</td>
      <td class="forumRow">
        <textarea name="wapContent" cols="100" rows="15"></textarea></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">标记：</td>
      <td class="forumRow"><input type="checkbox" name="IsHot" id="IsHot" value="1">
        <label for=IsHot>热门</label>
        <input type="checkbox" name="IsComm" id="IsComm" value="1">
        <label for=IsComm>推荐</label>
        <input type="checkbox" name="IsPic" id="IsPic" value="1">
        <label for=IsPic>包含图片</label>
        <input type="checkbox" name="IsHide" id="IsHide" value="1">
        <label for=IsHide>待审核</label></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">点击率：</td>
      <td class="forumRow"><input name="HitCount" type="text" value="0" size="10">      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile1" type="file" size="45">     <button onclick="javascript:more_img();">上传更多图片</button> {img}  </td>
    </tr>
    
    <tr id="tr_more_img2" style="display:none;">
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile2" type="file" size="45">   </td>
    </tr>
    <tr id="tr_more_img3" style="display:none;">
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile3" type="file" size="45">   </td>
    </tr>
    <tr id="tr_more_img4" style="display:none;">
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile4" type="file" size="45">   </td>
    </tr>
     <tr id="tr_more_img5" style="display:none;">
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile5" type="file" size="45">   </td>
    </tr>




    <tr> 
      <td align="right" class="forumRow">发布时间：</td>
      <td class="forumRow"> <input name="Add_Time" type="text" value="<?=date("Y-m-d H:i:s")?>" size="40"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 增 加 "  style="cursor:hand;" accesskey="s"> 
        <input name="Cancel" class="bott01" type="button" value=" 取 消 " onClick="javascript:history.back()" style="cursor:hand;">      </td>
    </tr>
  </form>
</table>
</body>
</html>