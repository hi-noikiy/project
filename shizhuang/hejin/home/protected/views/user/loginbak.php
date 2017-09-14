<!doctype html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <script charset="utf-8" src="<?=ASSETS_URL; ?>lib/jquery.js"></script>

</head>
<body >
<div id="top">
    <div id="logo">
        <h1>
            <a href="/" target="_blank"><img src="<?=ASSETS_URL?>images/logo.png" alt="口袋妖怪VS"></a>
            <img src="<?=ASSETS_URL?>images/line.png" id="line">
            <a href="/" id="game_tj"></a>
            <ul id="js_tj" class="clearfix" style=""></ul>
            <a href="pay" target="_blank" style="float:right;margin-top: 5px;">充值中心</a>
            <a class="link-lg" href="login" style="float:right;margin-right: 45px;margin-top: 5px;">登录/注册</a>
        </h1>
    </div>
</div>
<div id="menu" class="clearfix">
    <div id="game_header">
        <div id="game_logo">
            <img src="<?=ASSETS_URL?>images/web_61.png">
        </div>
        <div id="game_menu">
            <a class="on" id="menu_index" href="/">
                <label class="title">官网首页</label><br>
                <label class="sub_title">HOME</label>
            </a>
            <a class="atlas" id="menu_atlas" href="/">
                <label class="title">精灵图鉴</label><br>
                <label class="sub_title">POKEMON</label>
            </a>
            <a class="news" id="menu_news" href="/">
                <label class="title">新闻中心</label><br>
                <label class="sub_title">NEWS</label>
            </a>
            <a class="service" id="menu_service" href="/">
                <label class="title">玩家服务</label><br>
                <label class="sub_title">SERVICE</label>
            </a>

            <a id="menu_tieba" href="javascript:;">
                <label class="title">&nbsp;</label><br>
                <label class="sub_title">&nbsp;</label>
            </a>
        </div>
    </div>
</div>
<form method="post" action="index" id="form" name="form">
    <div id="register1"style="margin-top: 30px; margin-right: 520px;background-color:  #FFFFFF;">
        <ul class="lt" style="width: 720px;">
            <li class="first" style="margin-left: 265px;">
                欢迎登录海牛游戏
            </li>
            <div class="login-box">

            <div class="box-con tran">

            <div class="login-con f-l">

                <div class="form-group">

                    <input type="text" name="username" id="username" placeholder="邮箱/手机号码"/>

                    <span class="error-notic">邮箱/手机号码不正确</span>

                </div>

                <div class="form-group">

                    <input type="password"  name="password" id="password" placeholder="密码">

                    <span class="error-notic">密码不正确</span>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr" >

                        <a href="javascript:;" class="tran">登录</a>

                        <img class="loading" src="" style="display:block">

                    </button>

                </div>
                <div id="confirm"></div>

                <div class="from-line"></div>

                <div class="form-group">

                    <a href="javascript:;" class="move-signup a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class=""> <i class="iconfont tran"></i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class=""><i class="iconfont tran"></i></a>

                </div>

            </div>
</form>
            <!-- 登录 -->


<form name="RegForm" method="post" action="register" onSubmit="return InputCheck(this)">
            <div class="signup f-l">

                <div class="form-group">

                    <div class="signup-form">

                        <input type="text"  id ="username" name="username" placeholder="邮箱" class="email-mobile" onblur="verify.verifyEmail(this)">

                        <a href="javascript:;" class="signup-select">手机注册</a>

                    </div>
                    <span class="error-notic">邮箱格式不正确</span>
                    <div class="form-group">

                        <input id="password" name="password" type="password" placeholder="密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)">

                        <span class="error-notic">密码长度不够</span>

                    </div>
                </div>

                <div class="signup-email">

                    <div class="form-group">

                        <button type="submit" class="tran pr">

                            <a href="javascript:;" class="tran">注册</a>

                            <img class="loading" src="">

                        </button>

                    </div>

                    <p class="view-clause">点击注册，即同意我们的 <a href="#">用户隐私条款</a></p>

                </div><!-- 邮箱注册 -->

                <div class="signup-tel" style="display:none">

                    <div class="signup-form" id="message-inf" >

                        <input type="text" placeholder="发送验证码" style="width:180px;" onblur="verify.VerifyCount(this)">

                        <input name="btn" id="btn" class="get-code" onclick=" settimes.settime(this);"  style="margin-top: -3px;" value="发送验证码" />


                        <span class="error-notic">验证码输入错误</span>

                    </div>


                    <div class="form-group">

                        <button type="submit"  name="submit" class="tran get-message pr">

                            <a href="javascript:;" class="tran">注册</a>

                            <img class="loading" src="">

                        </button>

                    </div>

                </div><!-- 手机号码注册 -->

                <div class="from-line"></div>

                <div class="form-group">

                    <a href="javascript:;" class=""><i class="iconfont tran"></i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class=""><i class="iconfont tran"></i></a>

                </div>

            </div>
</form>

            <!-- 注册 -->



            <div class="other-way f-l">

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">QQ帐号登录</a>

                        <img class="loading" src="">

                    </button>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">新浪微博帐号登录</a>

                        <img class="loading" src="">

                    </button>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">微信帐号登录</a>

                        <img class="loading" src="">

                    </button>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">网易帐号登录</a>

                        <img class="loading" src="">

                    </button>

                </div>

                <div class="from-line"></div>

                <div class="form-group">

                    <a href="javascript:;" class="move-signup a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class="move-login a-tag tran">已有帐号？登录<i class="iconfont tran">&#xe606;</i></a>

                </div>

            </div>

            <!-- 第三方登录 -->



            <div class="mimachongzhi f-l">

                <div class="form-group">

                    <input type="text" placeholder="请输入您的邮箱地址">

                    <span class="error-notic">邮箱格式不正确</span>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">发送重置密码邮件</a>

                        <img class="loading" src="">

                    </button>

                </div>

                <div class="from-line"></div>

                <div class="form-group">

                    <a href="javascript:;" class="move-signup	a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class="move-login a-tag tran">已有帐号？登录<i class="iconfont tran">&#xe606;</i></a>

                </div>

            </div>

            <!-- 密码重置 -->



            <div class="mobile-success f-l">

                <p>手机号 <span>186****7580</span> 验证成功</p>

                <p>请完善您的账号信息，您也可以<a href="#">绑定现有账号</a></p>

                <div class="form-group">

                    <input type="text" placeholder="邮箱" class="email-mobile" onblur="verify.verifyEmail(this)"/>

                    <span class="error-notic">邮箱格式不正确</span>

                </div>

                <div class="form-group">

                    <input type="text" placeholder="您的名字">

                </div>

                <div class="form-group">

                    <input type="password" placeholder="密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)"/>

                    <span class="error-notic">密码长度不够</span>

                </div>

                <div class="form-group">
                    <button  class="tran pr">
                        <a href="javascript:;" class="tran">注册</a>
                        <img class="loading" src="">
                    </button>
                </div>
                <p class="view-clause">点击注册，即同意我们的 <a href="#">用户隐私条款</a></p>
            </div>

            <!-- 手机注册成功添补信息 -->

            </div>

            </div>

        </ul>

    </div>

<div id="copyright" style="min-width:1900px; margin-top: 400px;">
    <img src="<?=ASSETS_URL?>images/copyright_logo.png" style="position:relative; left:336px"/>
    <div class="wrapper clearfix">

        <div class="info clearfix">
            <p style="margin-bottom: 15px;" id="games">
                <a href="http://www.u591.com/tw" rel="game_1" target="_blank">天问</a>
                <a href="http://www.u591.com/lw" rel="game_2" target="_blank">龙威</a>
                <a href="http://www.u591.com/sw" rel="game_3" target="_blank">圣纹</a>
            </p>
            <p>
                Copyright©2010-2015 福州海牛网络科技有限公司 All Right Reserved.<br>
                <!-- 闽ICP备号-3 闽网文许字[2014]1118-005号 <br> -->
                <!-- <a href="#" target="_blank" title="站长统计">站长统计</a>  -->
                <a href="#" target="_blank">联系我们</a>
                <span>电话：0591-87678008</span>
            </p>
        </div>
    </div>
</div>
<style type="text/css">
    body {
        background: #EDEDED url('<?=ASSETS_URL?>images/1444459364949.jpg') no-repeat center 0px
    }
</style>
<style  type="text/css">
    body{
        font:12px "microsoft yahei",Arial,Helvetica,sans-serif;
        color:#666;
        margin:0;
        padding:0;
    }
    .rt{
        float:right;
    }

    .lt{
        float:left;
    }

    .clear{
        clear:both;
    }

    ul,li,p,h1,h2,h3,h4,h5,h6,dl,dd{
        margin:0;
        padding:0;
        list-style:none;
    }

    ul{
        list-style:none;
    }
    a{
        text-decoration:none;
    }

    div#register1{
        width:740px;
        height:585px;
        border:1px solid #A8A8A8;
        margin:0 auto;
        position:relative;
    }

    ul li {
        margin:20px 0 20px 60px;
    }
    ul li h2{
        font-weight:bold;
    }

    ul li.form-group input{
        height:30px;
        width:290px;
    }
    ul .first{
        font-weight:bold;
        font-size:22px;
    }

    ul li.form-group button{
        width:294px;
        color:#EDF9FD;
        background-color:#00A8FF;
        border:0px;
        height:30px;
    }

    pre{
        color:#47C0FE;
        float: right;
        margin: 2px 25px 0 0;
    }

    #register1 p#righttxt{
        color:#1AB32F;
        font-weight:bold;
        height:305px;
        border-left:1px dotted #EFEFEF;
        padding-left:50px;
        position:absolute;
        top:67px;
        left:356px;
    }
    body, div, ul, ol, li, p, i, em, h1, h2, h3, h4, a, strong, span, img, form, input, textarea, button{
        padding: 0;
        margin:0;
    }
    body{
        font-family:"Microsoft YaHei", "Simsun", Tahoma, Arial,"Helvetica Neue","Hiragino Sans GB", "sans-self";
        font-size: 14px;
        line-height: 24px;
    }
    @font-face {font-family: 'iconfont';
        src: url('font/iconfont.eot'); /* IE9*/
        src: url('font/iconfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('font/iconfont.woff') format('woff'), /* chrome、firefox */
        url('font/iconfont.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
        url('font/iconfont.svg#iconfont') format('svg'); /* iOS 4.1- */
    }
    input, textarea{
        outline: none;
    }
    li{
        list-style: none;
    }
    a{
        text-decoration: none;
        color: inherit;
    }
    img{
        border: 0;
    }
    .pr{
        position: relative;
    }
    .pa{
        position: absolute;
    }
    .pf{
        position: fixed;
    }
    .f-l{
        float: left;
    }
    .f-r{
        float: right;
    }
    li, div, ul{
        *zoom:1;
    }
    li:after, div:after, ul:after{
        content: '';
        display: table;
        clear: both;
    }
    .tran{
        transition:all 0.3s ease-out;
        -webkit-transition:all 0.3s ease-out;
        -moz-transition:all 0.3s ease-out;
        -o-transition:all 0.3s ease-out;
    }
    .container{
        width: 1000px;
        margin: 0 auto;
    }
    /*--------------样式重置------------*/
    /***********/

    .login-banner{
        width: 100%;
        background: url(../images/banner.jpg) center bottom no-repeat \9;
        background: url(../images/banner.jpg) center bottom/cover no-repeat;
    }
    @media screen and (min-width: 320px) {
        .login-banner{
            height: 100px;
        }
    }
    @media screen and (min-width: 480px) {
        .login-banner{
            height: 120px;
        }
    }
    @media screen and (min-width: 640px) {
        .login-banner{
            height: 160px;
        }
    }
    @media screen and (min-width: 768px) {
        .login-banner{
            height: 180px;
        }
    }
    @media screen and (min-width: 1024px) {
        .login-banner{
            height: 200px;
        }
    }
    @media screen and (min-width: 1280px) {
        .login-banner{
            height: 220px;
        }
    }
    @media screen and (min-width: 1366px) {
        .login-banner{
            height: 240px;
        }
    }
    @media screen and (min-width: 1680px) {
        .login-banner{
            height: 260px;
        }
    }
    .login-banner{
        height: 260px \9;
    }
    .login-box{
        width: 320px;
        margin:0 auto;
        overflow-x: hidden;
    }
    .box-con{
        width: 1600px;
    }
    .login-con{
        width: 300px;
        margin:0 10px;
    }
    .login-box .form-group{
        margin-top: 20px;
    }

    .login-box input, .signup-form{
        width: 300px;
        height:50px;
        line-height: 48px \9;
        padding-left: 10px;
        border: 1px #d9d9d9 solid;
        border-radius: 5px;
        font-size: 14px;
        box-sizing:border-box;
        -webkit-box-sizing:border-box;
        -moz-box-sizing:border-box;
        transition:all 0.3s ease-out;
        -webkit-transition:all 0.3s ease-out;
        -moz-transition:all 0.3s ease-out;
    }
    .login-box input:focus{
        border-color: #03a9f4;
        box-shadow: 0 0 15px #03a9f4;
        -webkit-box-shadow: 0 0 15px #03a9f4;
        -moz-box-shadow: 0 0 15px #03a9f4;
    }
    .login-box button{
        width: 300px;
        height: 50px;
        line-height: 50px;
        font-size: 16px;
        border: 0;
        background-color: #03a9f4;
        color: #fff;
        border-radius: 5px;
    }
    .login-box button a{
        display: block;
    }
    .login-box button a:hover{
        padding-top: 3px;
    }
    .login-box button:hover{
        background-color: #0096da;
    }
    .from-line{
        height: 0;
        overflow: hidden;
        border-top: 1px #c2c2c2 solid;
        margin-top: 20px;
    }
    .a-tag{
        position: relative;
        display: block;
        height: 48px;
        border: 1px #c2c2c2 solid;
        text-align: center;
        line-height: 48px;
        color: #c2c2c2;
        border-radius: 5px;
        background-color: #fff;
    }
    .blue-border{
        border:1px #03a9f4 solid;
        color: #03a9f4;
    }

    .a-tag:hover{
        background-color: #aaa;
        color: #fff;
    }
    .blue-border:hover{
        background-color: #03a9f4;
        color: #fff;
    }
    .a-tag i{
        position: absolute;
        right: 20px;
        top: 0;
        font-size: 24px;
        color: #fff;
    }
    .a-tag:hover i{
        right: 10px;
    }
    .signup, .other-way, .mimachongzhi, .mobile-success{
        width: 300px;
        margin: 0 10px;
    }


    .signup-form {
        margin-top: 20px;
    }
    .signup-form input{
        border: 0;
        height: 48px;
        width: 220px;
    }
    .signup-form input:focus{
        box-shadow:0 0 0 #fff;
    }
    .signup-form.border{
        border-color: #03a9f4;
        box-shadow: 0 0 15px #03a9f4;
        -webkit-box-shadow: 0 0 15px #03a9f4;
        -moz-box-shadow: 0 0 15px #03a9f4;
    }
    .signup-form a{
        color: #03a9f4;
    }
    .view-clause{
        padding-top: 20px;
        line-height: 14px;
        text-align: center;
        color: #808080;
    }
    .view-clause a{
        color: #03a9f4;
    }
    .view-clause a:hover{
        text-decoration: underline;
    }
    a.reacquire{
        color: #c1c1c1;
    }
    .error-notic{
        color: #ff4e00;
        display: none;
    }
    .login-footer{
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        padding: 10px 0;
        border-top: 1px #c1c1c1 solid;
        text-align: center;
        font-size: 12px;
        background-color: #fff;
        line-height: 20px;
    }
    .login-footer h1{
        font-size: 18px;
        font-weight: normal;
    }
    .login-nav{
        width: 100%;
        height: 30px;
        background-color: rgba(0,0,0,0.7);
        color: #fff;
        border-bottom: 1px #c1c1c1 solid;
    }
    .login-nav ul{
        padding-right: 50px;
    }
    .login-nav li{
        float: left;
        line-height: 30px;
        padding: 0 10px;
    }
    .login-nav a:hover{
        text-decoration: underline;
        color: #03a9f4;
    }
    .mobile-success{
        padding-top: 20px;
    }
    .mobile-success p{
        text-align: center;
        color: #666;
    }
    .mobile-success p span{
        font-weight: bold;
        padding: 0 10px;
    }
    .mobile-success p a{
        color: #03a9f4;
        padding-left: 5px;
    }
    .mobile-success p a:hover{
        text-decoration: underline;
    }
    .loading{
        position: absolute;
        top: 10px;
        right: 20px;
        display: none;

    }

    @font-face {font-family: "iconfont";
        src: url('iconfont.eot'); /* IE9*/
        src: url('iconfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('iconfont.woff') format('woff'), /* chrome、firefox */
        url('iconfont.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
        url('iconfont.svg#iconfont') format('svg'); /* iOS 4.1- */
    }

    .iconfont {
        font-family:"iconfont" !important;
        font-size:16px;
        font-style:normal;
        -webkit-font-smoothing: antialiased;
        -webkit-text-stroke-width: 0.2px;
        -moz-osx-font-smoothing: grayscale;
    }
  //

    .signup-tel.signup-form .get-code {
        position: absolute;
        right: 0;
        top: 0;
        width: 106px;
    }
    .signup-form .get-code, .signup-form .get-code2 {
        background: #fff3dd none repeat scroll 0 0;
        border: 1px solid #ffe2ac;
        color: #f59f00;
        cursor: pointer;
        display: inline-block;
        height: 46px;
        line-height: 40px;
        text-align: center;
        vertical-align: middle;
        width: 102px;
    }
    a {
        color: #2f3031;
    }
</style>
<style>
    #copyright{ margin-top: 50px; width: 100%; background: #2d3240;}
    #copyright .wrapper{ padding:40px;}
    #copyright img{ position: absolute; float: left; vertical-align: middle; margin-top: 20px;}
    #copyright .info{ position: relative; float: left; left: 245px; width: 950px; padding-left: 23px; height: auto;  border-left: 2px solid #363c4c;}
    #copyright .info p,#copyright .info a{ line-height: 2; margin-right: 8px; font-size: 14px; color: #888888; }
    #copyright .info a:hover{ color: #d8263c;}
</style>
<script>

var countdown=60;
function settime(obj) {
    if (countdown == 0) {
        obj.removeAttribute("disabled");
        obj.value="重获验证码";
        countdown = 60;
        return;
    } else {
        obj.setAttribute("disabled", true);
        obj.value="重新发送(" + countdown + ")";
        countdown--;
    }
    setTimeout(function() {
            settime(obj) }
        ,1000)
}

function InputCheck(RegForm)
{
    if (RegForm.username.value == "")
    {
        alert("用户名不可为空!");
        RegForm.username.focus();
        return (false);
    }
    if (RegForm.password.value == "")
    {
        alert("必须设定登陆密码!");
        RegForm.password.focus();
        return (false);
    }
}

var _handle='';//储存电话是否填写正确

$(function(){

    $(".signup-form input").on("focus",function(){

        $(this).parent().addClass("border");

    });

    $(".signup-form input").on("blur",function(){

        $(this).parent().removeClass("border");

    })



    $(document).ready(function(){
        $("#form").submit(function(){
            login();
            return false;
        });
    });
    function login(){
        var user = $("#username").val();
        var pass = $("#password").val();
        if (user == ""){
            $("#confirm").text("请输入登录用户名");
            $("#username").focus();
            return false;
        }
        if(pass == ""){
            $("#confirm").text("请输入登录密码");
            $("#password").focus();
            return false;
        }
        $.ajax({
            type: "POST",
            url: "index",
            data: "username=" + user + "&password=" + pass,
            beforeSend: function(){
                $("confirm").text("登录中，请稍候");
            },
            success: function(data){
                if( data =='success'){
                    $("#confirm").text("登录成功，欢迎" + user + "回来！正在进入你的空间");
                   window.location.href="/";
                }else {
                    $("#confirm").text("账号或者密码不正确！");
                }
            }
        });
    }

    //注册方式切换

    $(".signup-select").on("click",function(){

        var _text=$(this).text();

        var $_input=$(this).prev();

        $_input.val('');

        if(_text=="手机注册"){

            $(".signup-tel").fadeIn(200);

            $(".signup-email").fadeOut(180);

            $(this).text("邮箱注册");

            $_input.attr("placeholder","手机号码");

            $_input.attr("onblur","verify.verifyMobile(this)");

            $(this).parents(".form-group").find(".error-notic").text("手机号码格式不正确")



        }

        if(_text=="邮箱注册"){

            $(".signup-tel").fadeOut(180);

            $(".signup-email").fadeIn(200);

            $(this).text("手机注册");

            $_input.attr("placeholder","邮箱");

            $_input.attr("onblur","verify.verifyEmail(this)");

            $(this).parents(".form-group").find(".error-notic").text("邮箱格式不正确")

        }

    });

    //步骤切换

    var _boxCon=$(".box-con");

    $(".move-login").on("click",function(){

        $(_boxCon).css({

            'marginLeft':0

        })

    });

    $(".move-signup").on("click",function(){

        $(_boxCon).css({

            'marginLeft':-320

        })

    });

    $(".move-other").on("click",function(){

        $(_boxCon).css({

            'marginLeft':-640

        })

    });

    $(".move-reset").on("click",function(){

        $(_boxCon).css({

            'marginLeft':-960

        })

    });

    $("body").on("click",".move-addinf",function(){

        $(_boxCon).css({

            'marginLeft':-1280

        })

    });
/*
 var countdown=60;
 function settime(obj) {
 if (countdown == 0) {
 obj.removeAttribute("disabled");
 obj.value="重获验证码";
 countdown = 60;
 return;
 } else {
 obj.setAttribute("disabled", true);
 obj.value="重新发送(" + countdown + ")";
 countdown--;
 }
 setTimeout(function() {
 settime(obj) }

 ,1000)
 }
 */


    //获取短信验证码

    var messageVerify=function (){

        $(".get-message").on("settime",function(){

            if(_handle){
                var countdown=60;
                function settime(obj) {
                    if (countdown == 0) {
                        obj.removeAttribute("disabled");
                        obj.value="重获验证码";
                        countdown = 60;
                        return;
                    } else {
                        obj.setAttribute("disabled", true);
                        obj.value="重新发送(" + countdown + ")";
                        countdown--;
                    }
                    setTimeout(function() {
                            settime(obj) }

                        ,1000)
                }

                $("#message-inf").fadeIn(100)

                $(this).html('<a href="javascript:;">下一步</a><img class="loading" src="">').addClass("move-addinf");

            }

        });

    }();

});



//表单验证

function showNotic(_this){

    $(_this).parents(".form-group").find(".error-notic").fadeIn(100);

    $(_this).focus();

}//错误提示显示

function hideNotic(_this){

    $(_this).parents(".form-group").find(".error-notic").fadeOut(100);

}//错误提示隐藏

var verify={

    verifyEmail:function(_this){

        var validateReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        var _value=$(_this).val();

        if(!validateReg.test(_value)){

            showNotic(_this)

        }else{

            hideNotic(_this)

        }

    },//验证邮箱

    verifyMobile:function(_this){

        var validateReg = /^((\+?86)|(\(\+86\)))?1\d{10}$/;

        var _value=$(_this).val();

        if(!validateReg.test(_value)){

            showNotic(_this);

            _handle=false;

        }else{

            hideNotic(_this);

            _handle=true;

        }

        return _handle

    },//验证手机号码

    PasswordLenght:function(_this){

        var _length=$(_this).val().length;

        if(_length<6){

            showNotic(_this)

        }else{

            hideNotic(_this)

        }

    },//验证设置密码长度

    VerifyCount:function(_this){

        var _count="123456";

        var _value=$(_this).val();

        console.log(_value)

        if(_value!=_count){

            showNotic(_this)

        }else{

            hideNotic(_this)

        }

    }//验证验证码

}

</script>

</body>

</html>