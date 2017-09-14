<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title></title>
    <meta charset="utf-8">
    <script charset="utf-8" src="<?=ASSETS_URL; ?>lib/jquery.js"></script>
    <link rel="stylesheet" href="<?=ASSETS_URL; ?>style.css">
    <link rel="stylesheet" href="<?=ASSETS_URL; ?>lib/iconfont.css">
    <style>
        @charset "utf-8";
        /********************************css reset*****************************************************************/
        body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td ,span, object, iframe{ padding: 0; margin: 0; }
        table{border-collapse: collapse; border-spacing: 0; }
        fieldset, img { border: 0; }
        ol, ul { list-style: none; }
        h1, h2, h3, h4, h5, h6 { font-weight: normal; font-size: 100%; }
        address,cite,code,em,th,i{font-weight:normal; font-style:normal;}
        q:before, q:after { content:""; }
        :link, :visited { text-decoration: none; }
        a { cursor: pointer; text-decoration: none; }
        a:hover { text-decoration: none; }
        .clear { clear:both }
        .clearfix{ zoom:1;}
        .clearfix:after{ content:""; height:0; visibility:hidden; display:block; clear:both; font-size:0;}
        .left{ float:left; }
        .right{ float:right;}
        .t_header{ color:#fff; }
        .t_header:before{background: #d8263c;}
        .title_dot{color: #c6c6c6 !important;}
        .nothing_tip{padding-top: 50px; text-align:center; font-size: larger; color: #000; line-height: 1.5}

        /********************************css reset end*****************************************************************/
        body{ font-family:"Microsoft Yahei"; font-size:14px; letter-spacing:1pt; word-break:break-all;}
        #content,#footer{ width: 100%;}
        .wrapper{width:1200px;position: relative;margin-left: auto;margin-right: auto}
        #menu{ height: 70px; }
        h2.t_header{ font-size: 14px; height: 35px; line-height: 35px; position: relative; background-color: #232323; padding-left: 10px;}
        h2.t_header:before{ content: ""; display: inline-block; *display:block; width:3px; height: 14px; vertical-align: middle; margin-right: 8px; }

        /********************************顶部logo*****************************************************************/
        #top{ background:#F3F3F3; padding-top:8px;min-width: 300px}
        #top a{ color:#737373; font-size:16px;}
        #top a:hover{color:#be222e !important}
        #top #logo{ width:1200px;position:relative;margin:0 auto;}
        #top #logo h1{position:relative}
        #top #logo a{ display: inline-block; padding: 4px 2px 5px;}
        #top #logo h1 span{cursor:pointer;font-weight:400;display: inline-block; overflow: hidden; width: 120px;}
        #top #logo h1 #line{position:absolute;top:8px;margin-left:12px}
        #top #logo h1 ul{z-index:10;display:none;border:1px solid #e1e0e0;width:570px;position:absolute;left:170px;background:#fff;padding:10px 0 10px 15px}
        #top #logo h1 #js_tj a{display:block}
        #top #logo h1 #js_tj img{width:40px;height:40px}
        #top #logo h1 ul li{height:40px;line-height:40px;overflow:hidden;padding-bottom:7px;margin-bottom:7px;border-bottom:1px dotted #d9d9d9;margin-right:15px;width:175px}
        #top #logo h1 ul li img{margin-right:10px}
        #top #logo h1 #game_tj{display:inline-block; position:absolute;top:5px;left:170px;background:url('../images/game_tj.png') no-repeat center 5px;width:141px;height:28px;z-index:11}
        #top #logo h1 #game_tj:hover,#top #logo h1 a.visited{width:141px;height:28px;border-left:1px solid #e1e0e0;border-right:1px solid #e1e0e0;border-top:1px solid #e1e0e0;background-color:#fff !important}
        #top .option{position:absolute; top:8px; right:0;bottom:0px;}
        #top .option a{padding:13px 15px;*padding:15px;display:inline-block}
        #top .option a#login:hover,#top .option a#login.visited{padding:6px 14px 10px 14px;*padding:6px 14px 10px 14px;border-left:1px solid #e1e0e0;border-right:1px solid #e1e0e0;border-top:1px solid #e1e0e0;background:#fff}
        #top #login_con{display:none;position:absolute;width:290px;height:300px;background:#fff;right:-20px; top:34px; border:1px solid #e1e0e0; z-index:10}
        #top #login_con input{color:#A9A9A9;outline:none}
        #top em{display:none;width:59px;height:1px;*height:2px;position:absolute;background:#fff;bottom:-1px;*bottom:1px;right:163px;z-index:11}
        #top #login_con form input.width{padding:8px;font-size:14px;width:211px;border-radius:4px;border:1px solid #ccc}
        #top #login_con ul{padding:20px 0;width:250px;margin:0 auto;}
        #top #login_con ul li{margin-bottom:10px}
        #top #login_con ul li#forget{padding:10px 0;overflow:hidden;border-bottom:1px dotted #C8C8C8;margin-bottom:15px}
        #top #login_con ul li#forget a{font-size:14px}
        #top #login_con ul li#forget a:hover{color:red;text-decoration:underline}
        #top #login_con ul li#forget a.register{color:#199d6a;text-decoration:underline}
        #top #login_con a{padding:0}
        #top a#login_btn{display:block;background:url('../../../www2/img/btn.png') no-repeat left bottom;width:230px;height:45px}
        #top a#login_btn:hover{background-position:-230px bottom}
        #top li#dsflogin a{display:inline-block; height:23px;line-height:23px;font-size:14px}
        #top li#dsflogin a.qq{background:url('../../../www2/img/qq.png') no-repeat left center;margin-right:6px; padding-left: 26px;}
        #top li#dsflogin a.sina{background:url('../../../www2/img/sina.png') no-repeat left center;padding-left:30px}

        /********************************顶部菜单*****************************************************************/
        #menu{height: 71px; margin-left: auto; margin-right: auto; background: url('img/white/menu_bg.png') no-repeat center;}
        #game_header{width: 1200px; position: relative; margin-left: auto; margin-right: auto;}
        #game_logo{ float: left; }
        #game_logo img{float: right; }
        #menu #game_menu{float:right; margin-top: 11px;}
        #menu a{ float:left; width: 150px; text-align: center;}
        #menu .title{ font-family: "微软雅黑", sans-serif, serif; font-size: 20px; height: 30px; line-height: 30px; color: #fff;}
        #menu .title:hover{ color: #d8263c; cursor: pointer;}
        #menu .sub_title{ font-family: "微软雅黑", sans-serif, serif; font-size: 12px; height: 16px; line-height: 16px;  color: #9d9d9d;}

        /********************************主页信息*****************************************************************/
        #centre{ margin-top: 400px;}
    </style>
</head>
<link rel="stylesheet" href="css.css">
<body>
<div id="top">
    <div id="logo">
        <h1>
            <a href="/" target="_blank"><img src="http://localhost:63342/u591/hejin/assets/images/logo.png" alt="口袋妖怪VS"></a>
            <img src="http://localhost:63342/u591/hejin/assets/images/line.png" id="line">
            <a href="/" id="game_tj"></a>
            <ul id="js_tj" class="clearfix" style=""></ul>
            <a href="pay" target="_blank" style="float:right;margin-top: 5px;">充值中心</a>
            <a href="register" target="_blank" style="float:right;margin-top: 5px;margin-right: 30px">注册</a>
            <a class="link-lg" href="login" target="_top" style="float:right;margin-right: 45px;margin-top: 5px;">登录</a>

        </h1>
    </div>
</div>
<div id="menu" class="clearfix">
    <div id="game_header">
        <div id="game_logo">
            <img src="http://localhost:63342/u591/hejin/assets/images/web_61.png">
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
<section>
    <div id="main" style="margin-right: 550px;background-color:  #FFFFFF; height: 562px;">
        <ul class="lf">
            <li>
                <b></b>
                手机注册
                <div id="register1" class="rt">
                    <p id="p1">
                        提示：如果你已有海牛账号，请直接 <a>登录</a>
                    </p>
                    <form style="method:post">
                        <ul>
                            <li>
                                手机号码 <input type="text" name="txtemail" value="请输入手机号码">
                            </li>
                            <li>
                                设置密码 <input type="password" name="txtPwd">
                            </li>
                            <li style="margin-left: 80px;">
                                <p>低&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp中&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp高</p>
                            </li>
                            <li>
                                确认密码 <input type="password" name="txt2Pwd">
                            </li>
                            <li style="margin-left: 70px;">
                                验证
										<span id="yanzheng">
											<a>&gt&gt</a><span>请按住滑块，拖动到最右边</span>
										</span>
                            </li>
                            <input type="text" id="input1" style="margin-left: -29px;" />
                            <input id="btnSendCode" type="button" value="发送验证码" onclick="sendMessage()" /></p>
                            <li id="zhuce" style="margin-left: 88px;">
                                <input type="submit" name="btn" value="注册">
                            </li>
                        </ul>
                    </form>
                </div>
            </li>
            <li id="absolute">
                <b></b>
                邮箱注册
                <div id="register2" class="rt">
                    <p id="p1">
                        提示：如果你已有海牛账号，请直接 <a>登录</a>
                    </p>
                    <form style="method:post">
                        <ul>
                            <li>
                                邮&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp箱 <input type="text" name="txtemail" value="请输入邮箱">
                            </li>
                            <li>
                                设置密码 <input type="password" name="txtPwd">
                            </li>
                            <li style="margin-left: 80px;">
                                <p>低&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp中&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp高</p>
                            </li>
                            <li >
                                确认密码 <input type="password" name="txt2Pwd">
                            </li>
                            <li style="margin-left: 70px;">
                                验证
										<span id="yanzheng">
											<a>&gt&gt</a><span>请按住滑块，拖动到最右边</span>
										</span>
                            </li>
                            <li id="zhuce" style="margin-left: 88px;">
                                <input type="submit" name="btn" value="注册">
                            </li>
                        </ul>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</section>

<div id="copyright" style="margin-top: 400px;">
    <img src="http://localhost:63342/u591/hejin/assets/images/copyright_logo.png" style="position:relative; left:336px"/>
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
        background: #EDEDED url('http://localhost:63342/u591/hejin/assets//images/1444459364949.jpg') no-repeat center 0px
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
</body>
<style>
    body{
        font:12px "microsoft yahei",Arial,Helvetica,sans-serif;
        color:#666;
        margin:0;
        padding:0;

    }
    ul,li,p,h1,h2,h3,h4,h5,h6,dl,dd{
        margin:0;
        padding:0;
        list-style:none;
    }
    .lf{float:left;}
    .rt{float:right;}
    .clear{clear:both;}

    a{
        color:#666;
        text-decoration:none;
    }
    #main{
        width:700px;
        height:400px;
        border:1px solid #A7A7A7;
        margin:0 auto;
        box-sizing:border-box;
    }

    #main ul.lf{
        height:560px;
        width:160px;
        background-color:#DFDFDF;
    }

    #main ul li{
        color:#080808;
        font-size:14px;
        height:64px;
        width:160px;
        line-height:64px;
    }

    #main ul li b{
        padding:30px 20px 30px 30px;
    }
    #main ul li:hover{
        background-color:#FFFFFF;
    }

    #main{
        position:relative;
    }

    #register2 p#p1{
        height:30px;
        width:400px;
        line-height:30px;
        text-align:center;
        background-color:#DFDFDF;
        margin:5px 0 0 30px;
    }
    #register2 p#p1 a{
        color:#3AB0F2;
    }

    #register2 ul{
        height:363px;
        width:540px;
        text-align:center;
    }

    ul li div#register2{
        width:540px;
        height:400px;
        display:none;
        position:absolute;
        top:0;
        right:0;
    }

    #main ul li:hover #register2{
        display:block;
    }

    ul li div#register2 li{
        width:300px;
        height:40px;
        line-height:40px;
        padding:10px 0 10px 30px;
        margin-left:4px;
    }

    #yanzheng{
        display:inline-block;
        height:32px;
        width:250px;
        line-height:32px;
        text-align:center;
        background-color:#BEBEBE;
    }

    #yanzheng a{
        display:inline-block;
        border:1px solid #979797;
        height:30px;
        line-height:30px;
        text-align:center;
        width:40px;
        background-color:#FFFFFF;
    }

    form ul li p{
        width:210px;
        height:20px;
        background-color:#BEBEBE;
        line-height:20px;
        text-align:center;
        border-radius:30px;
        color:#EDEDED;
        margin-left:32px;
    }

    ul li#zhuce input{
        width:250px;
        height:36px;
        background-color:#00A8FF;
        border:0;
        color:#fff;
        font-size:22px;
    }
    #register1 p#p1{
        height:30px;
        width:400px;
        line-height:30px;
        text-align:center;
        background-color:#DFDFDF;
        margin:5px 0 0 30px;
    }
    #register1 p#p1 a{
        color:#3AB0F2;
    }

    #register1 ul{
        height:363px;
        width:540px;
        text-align:center;
    }

    ul li div#register1{
        width:540px;
        height:400px;
        display:none;
        position:absolute;
        top:0;
        right:0;
    }

    #main ul li:hover #register1{
        display:block;
    }

    ul li div#register1 li{
        width:300px;
        height:40px;
        line-height:40px;
        padding:10px 0 10px 30px;
        margin-left:4px;
    }

</style>

</html>