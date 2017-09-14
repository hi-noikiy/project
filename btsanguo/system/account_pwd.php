<?
//ini_set("display_errors",true);
include("inc/config.php");
include("inc/email.class.php");
include("inc/function.php");
header("Content-type: text/vnd.wap.wml;charset=GB2312");
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<head>
</head>
<card id="main" title="修改超级密码">
<p>
<?php
foreach($_REQUEST as $key => $val) $_REQUEST[$key]=data_check($val);//防止sql攻击
$ranNum=rand(10000,99999);
$Action=$_REQUEST["Action"];
$code = genRandomString(4);
$time = time();
if ($Action=="pwd"){
	SetConn(81);//连接帐号库
	$UserName=strtolower($_REQUEST["UserName"]);
	$email=$_REQUEST["email"];
        if(empty($email))
        {
            echo "请输入邮箱！";
            echo('</p></card></wml>');
            exit;
        }
        elseif(!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$",$email))
        {
            echo "邮箱格式有误，请检查！";
            echo('</p></card></wml>');
            exit;
        }
	$sql="select * from account_bind where NAME='$UserName' limit 1";
        write_log('log','sup_app_log',$sql."email=".$email.",ip=".getIP_front()." ".date('Y-m-d H:i:s')."\r\n");
	$conn=mysql_query($sql);
	$rs=mysql_fetch_array($conn);
	if($rs) {
            if($rs['email']==$email)
            {
                $upsql="update account_bind set addTime='".$time."',codeSuper='".$code."',tagSuper='0'  where NAME='$UserName' limit 1";
                if(mysql_query($upsql)==false)
                {
                    echo "抱歉！操作失败，请重试！";
                    echo('</p></card></wml>');
                    exit;
                }
                $mailsubject = '天问修改超密确认信';
                $mailbody = "亲爱的$UserName,<br />

您忘记了账号--[$UserName]的超密，如果您确定需要更换该超级密码，请通过以下地址修改超级密码。<br />

手机用户：<a href='http://wap.u591.com/resuppass.php?name=$UserName&amp;code=".md5($code.$mdString)."'> http://wap.u591.com/resuppass.php?name=$UserName&amp;code=".md5($code.$mdString)."</a><br />
pc用户：<a href='http://www.u591.com/resuppass.php?name=$UserName&amp;code=".md5($code.$mdString)."'> http://www.u591.com/resuppass.php?name=$UserName&amp;code=".md5($code.$mdString)."</a><br />

填写天问账号：<font color='red'>$UserName</font> 验证码 <font color='red'>$code(注意大小写)</font> 后，再进行修改超级密码。<br /><br/>
如果URL链接无法直接点击，请复制链接代码来激活网页。<br />
以上操作在24小时内有效! <br /><br />

如果不做任何操作，系统将保留原超级密码。<br /><br />

温馨提示：<br />
* 本邮件为系统自动发送，不受理客户在线直接回复。<br />您可以使用客户服务电话0591-87678008联系我们。再次感谢您使用海牛提供的服务！<br /><br />

福州海牛网络技术有限公司版权所有<br />
*******************************************************************************<br />
*******************************************************************************
";
                send($email,$UserName,$mailsubject,$mailbody);
                echo "请到您的邮箱: $email 查收邮件";
            }
            else
            {
                echo "邮箱和账号不匹配,请先确定该账号已绑定邮箱";
            }
	}else{
		echo "请先将该账号与邮箱绑定,绑定之后才可修改超级密码！";
	}
}else{
?>
帐号：<input name="UserName"/><br/>
邮箱：<input name="email" type="text" maxlength="40"/><br/>
<anchor>确认<go href="account_pwd.php?Action=pwd&amp;ranNum=<?=$ranNum?>" method="post">
<postfield name="UserName" value="$(UserName)"/>
<postfield name="email" value="$(email)"/>
</go></anchor>
<br/>
请输入您的账号和该账号绑定的邮箱，提交成功之后我们将发送一份邮件（带验证码）<br/>到您绑定的邮箱,之后按邮箱的提示方可完成修改您的超级密码。<br/>
若未绑定过邮箱，请先点击<a href="http://bbs.u591.com/wap/index.php?action=my">这里</a>绑定邮箱
<?}?>
<br/><br/><a href="account_pwd.php">返回上级</a><br/>
<a href="index.php">返回首页</a><br/>
<?include("bott.php");?>
</p>
</card>
</wml>