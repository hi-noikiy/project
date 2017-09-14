<?php
//error_reporting(E_ALL);
$noLocation=true;
include_once('common.inc.php');
$_SESSION["ADMIN_ID"]="";
//@session_destroy();
/*
 * 如果用户有提交数据
 */
if($_POST['username'] && $_POST['pass']){
	/*
	 * 加载用户类
	 */
	$userClass=new user();
	$reInt=false;
	/*
	 * 检查用户合法性
	 */   
	 $reInt=$userClass->check($_POST['username'],$_POST['pass']);
        
     //   print_r($_SESSION);exit;
    
	if($reInt>0){      //       Header("Location: unread.php"); exit;////
        // echo '<script> window.location.href="unread.php";</script>';exit;
         $url = 'unread.php';
         go($url);
    }
	else{
		switch($reInt){
			case -1	: $error='错误的用户帐号';
			break;
			case -2	: $error='密码错误';
			break;
			case -3	: $error='没有登陆权限';
			break;
		}
		$_SESSION["ADMIN_ID"]="";
		@session_destroy();
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- saved from url=(0034)http://c022161813b.works.tw/admin/ -->
<HTML>
<HEAD>
<TITLE>后台登录</TITLE>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<STYLE type="text/css">TD {
	FONT-SIZE: 13px; COLOR: #8f7b5c; FONT-FAMILY: Arial, Helvetica, sans-serif
}
INPUT {
	BORDER-RIGHT: 1px; BORDER-TOP: 1px; BORDER-LEFT: 1px; WIDTH: 114px; BORDER-BOTTOM: 1px; HEIGHT: 16px
}
.input_bg {
	BACKGROUND-POSITION: center center; BACKGROUND-ATTACHMENT: fixed; BACKGROUND-IMAGE: url(style/images//admin_input.gif); BACKGROUND-REPEAT: no-repeat
}
BODY {
	MARGIN: 0px
}
</STYLE>

<META content="MSHTML 6.00.6000.16762" name=GENERATOR>
</HEAD>
<BODY bgColor=#ffffff>
<form method="post">
<TABLE height="100%" cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR>
		<TD>
		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<TR>
				<TD background="style/images/admin_bg.gif">
				<TABLE cellSpacing=0 cellPadding=0 align=center border=0>
					<TR>
						<TD colSpan=3><IMG height=20 src="style/images/admin_t.gif" width=533 border=0 ></TD>
					</TR>
					<TR>
						<TD><IMG height=262 src="style/images/admin_l.gif" width=20 border=0 ></TD>
						<TD vAlign=top bgColor=#ffffff>
						<TABLE cellSpacing=0 cellPadding=0 border=0>
							<TR>
								<TD><IMG height=182 src="style/images/admin_m.gif" width=493 border=0 ></TD>
							</TR>
							<TR>
								<TD align=right><IMG height=10 src="style/images/spacer.gif" width=1></TD>
							</TR>
							<TR>
								<TD align=right>
									<TABLE cellSpacing=4 cellPadding=0 border=0>
										<?php
                                                                                    if($error)
                                                                                    {
                                                                                ?>
                                                                                    <TR>
											<TD align=right>错误提示：</TD>
											<TD class=input_bg vAlign=center align=middle width=120><font color='red'><?=$error?></font></TD>

                                                                                    </TR>
                                                                                <?php
                                                                                    }
                                                                                ?>
										
										<TR>
											<TD align=right>用户名：</TD>
											<TD class=input_bg vAlign=center align=middle width=120><INPUT
												tabIndex=1 name="username" style="background-color:#CCCCCC"></TD>
											<TD width=63 rowSpan=2><input type="image" style="width: 63px; height: 47px;" src="style/images/admin_enter.gif" border=0 ></TD>
										</TR>
										<TR>
											<TD align=right>密 码：</TD>
											<TD class=input_bg vAlign=center align=middle width=120><INPUT
												tabIndex=2 type=password name="pass" style="background-color:#CCCCCC"></TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
						</TABLE>
						</TD>
						<TD><IMG height=262 src="style/images/admin_r.gif" width=20 border=0></TD>
					</TR>
					<TR>
						<TD colSpan=3><IMG height=20 src="style/images/admin_b.gif" width=533 border=0 ></TD>
					</TR>
				</TABLE>
				</TD>
			</TR>
		</TABLE>
		</TD>
	</TR>
</TABLE>
</form>
</BODY>
</HTML>
