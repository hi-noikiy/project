<?
include("inc/CheckUser.php");
include("inc/function.php");
//include("inc/config.php");
include("inc/jurisdiction.php");

session_start();
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<SCRIPT language="JavaScript">
function showsubmenu(sid)
{
	whichEl = eval("submenu" + sid);
	if (whichEl.style.display == "none")
	{
		eval("submenu" + sid + ".style.display=\"\";");
	}else{
		eval("submenu" + sid + ".style.display=\"none\";");
	}
}
</SCRIPT>
</head>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<body bgcolor="#01ABFF" leftmargin="0" topmargin="0" class="tdbg" >
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/left_top_03.gif" width="186" height="40"></td>
  </tr>
  <tr>
    <td background="images/left_top_01.gif"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="1%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td width="99%" height="30">


            <table width=98% border="0" align=center cellpadding=0 cellspacing=0>
              <tr>
                <td height=25 background="images/bg3.gif" class=onemenu style="cursor:hand;" onClick="showsubmenu(8)" >
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;充值管理</td>
              </tr>

              <tr>
                <td id='submenu8' style="display:">
                    <table width=98% border="0" align=center cellpadding=0 cellspacing=0>

       <?php

       foreach($jurisdiction_arr as $key=>$value){
           if($value['type']==1){
               if(MuchObject($key,$_SESSION['u_flag'])=="checked"){
                   echo '<tr> <td height="20">&nbsp;&nbsp;<img src="images/tb.gif" width="20" height="9"><a href="'.$value['url'].'" target="mainFrame">'.$value['name'].'</a></td></tr>';
               }

           }
       }
       ?>

                  </table></td>
              </tr>
            </table>

                <table width=98% border="0" align=center cellpadding=0 cellspacing=0>
              <tr>
                <td height=25 background="images/bg3.gif" class=onemenu style="cursor:hand;" onClick="showsubmenu(6)" >
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;运营操作相关</td>
              </tr>
              <tr>
                <td id='submenu6' style="display:">
				<table width=98% border="0" align=center cellpadding=0 cellspacing=0>

        <?php
       foreach($jurisdiction_arr as $key=>$value){
           if($value['type']==2){
               if(MuchObject($key,$_SESSION['u_flag'])=="checked"){
                   echo '<tr> <td height="20">&nbsp;&nbsp;<img src="images/tb.gif" width="20" height="9"><a href="'.$value['url'].'" target="mainFrame">'.$value['name'].'</a></td></tr>';
               }
           }
       }
       ?>


		</table></td>
              </tr>
            </table>

            <table width=98% border="0" align=center cellpadding=0 cellspacing=0>
              <tr>
                <td height=25 background="images/bg3.gif" class=onemenu style="cursor:hand;" onClick="showsubmenu(15)" >
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;系统管理</td>
              </tr>
              <tr>
                <td id='submenu15' style="display:">
				<table width=98% border="0" align=center cellpadding=0 cellspacing=0>

        <?php
       foreach($jurisdiction_arr as $key=>$value){
           if($value['type']==3){
               if(MuchObject($key,$_SESSION['u_flag'])=="checked"){
                   echo '<tr> <td height="20">&nbsp;&nbsp;<img src="images/tb.gif" width="20" height="9"><a href="'.$value['url'].'" target="mainFrame">'.$value['name'].'</a></td></tr>';
               }
           }
       }
       ?>

                    <tr>
                      <td height="20">&nbsp;&nbsp;<img src="images/tb.gif" width="20" height="9"><a href="Adm_PwdEdit.php" target="mainFrame">修改密码</a></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;&nbsp;<img src="images/tb.gif" width="20" height="9"><a href="Adm_Right.php" target="mainFrame">关于系统</a></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="onemenu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;系统状态</td>
              </tr>
              <tr>
                <td height="20">当前登录用户：<font color="#FF0000"><?=$AdminName?></font></td>
              </tr>
              <tr>
                <td height="20">上次登录日期：<?=date("Y-m-d",strtotime($LoginTime))?></td>
              <tr>
                <td height="20">上次登录时间：<?=date("H:i:s",strtotime($LoginTime))?></td>
              </tr>
              <tr>
                <td height="20" align="center"><a href="../" target="top">网站首页</a> <a href="Adm_logout.php" target="_parent">退出</a><img src="images/scalewing.gif" width="21" height="16"></td>
              </tr>
            </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="images/left_top_02.gif" width="186" height="31"></td>
  </tr>
</table>
</body>
</html>