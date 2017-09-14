<html>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style>
.bbb {
	BORDER-LEFT: black 1px outset; 
	BORDER-RIGHT: black 1px outset; 
}
</style>
<script>
var sw=screen.width;
var strColumns_Current = "170px,10px,*";
function showtoc()
  {
  if(parent.frame.cols=="0px,10px,*"){
      parent.frame.cols = strColumns_Current;
      document.all("fon").innerHTML="3";
	  document.all("fon").style.posLeft=-4;
	  oBody.title="点击这里隐藏菜单栏";
      return
  }    
  parent.frame.cols = "0px,10px,*";
  oBody.title="点击这里显示菜单栏";
  document.all("fon").style.posLeft=-6;
  document.all("fon").innerHTML="4";
  }
function hidetoc()
{
  parent.frame.cols = "0px,10px,*";
  oBody.title="Click here to show left";
  document.all("fon").style.posLeft=-6;
  document.all("fon").innerHTML="4";
}
</script>
<body id="oBody" title="点击这里隐藏菜单栏" class="bbb" onselectstart="return false" bgcolor="#01ABFF" leftmargin="0" topmargin="0" onClick="showtoc()">
<table width="100%" height="100%">
<tr valign="middle">
	<td><font face="Webdings" id="fon" style="color:white;font-size:9pt;position:relative;left:-4px;cursor:hand;">3</font></td>
</tr>
</table>
</body></html>
