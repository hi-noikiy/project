<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $this->title ?></title>
    <meta name="keywords" content="<?= $this->keyword ?>">
    <meta name="description" content="<?= $this->desc ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.55">

    <link href="<?=ASSETS_URL; ?>css/common.css" rel="stylesheet" type="text/css">
    <link href="<?=ASSETS_URL; ?>css/white.css" rel="stylesheet" type="text/css">
    <script charset="utf-8" src="<?=ASSETS_URL; ?>js/i.js"></script>

</head>
<body>
<div id="top">
    <div id="logo">
        <h1>
            <a href="/" target="_blank"><img src="<?=ASSETS_URL; ?>images/logo.png" alt="口袋妖怪VS"></a>
            <img src="<?=ASSETS_URL; ?>images/line.png" id="line">
<!--            <a href="/" id="game_tj"></a>-->
            <ul id="js_tj" class="clearfix" style=""></ul>

            <a href="pay" style="float:right;margin: 5px 30px 0 0;">充值中心</a>
            <?php if(isset($this->accountInfo['username'])) { ?>
            <a href="<?=$this->createUrl('user/loginout');?>" style="float:right;margin:5px 10px 0 0;">退出</a>
            <a href="javascript:;" style="float:right;margin:5px 10px 0 0;"><?=$this->accountInfo['username']; ?></a>
            <?php } else { ?>
            <a href="login" style="float:right;margin: 5px 30px 0 0;">登录/注册</a>
            <?php } ?>
        </h1>
    </div>
</div>
<div id="menu" class="clearfix">
    <div id="game_header">
        <div id="game_logo">
            <img src="<?= ASSETS_URL; ?>images/web_61.png">
        </div>
        <div id="game_menu">
            <a class="on" id="menu_index" href="/">
                <label class="title">官网首页</label><br>
                <label class="sub_title">HOME</label>
            </a>
            <?php foreach ($this->cate as $v) { ?>
                <a href="/<?= strtolower($v->en_name) ?>">
                    <label class="title"><?= $v->name ?></label><br>
                    <label class="sub_title"><?= $v->en_name ?></label>
                </a>
            <?php } ?>
            <a target="_blank" href="http://tieba.baidu.com/f?kw=口袋妖怪vs官方&ie=utf-8">
                <label class="title">官方贴吧</label><br>
                <label class="sub_title">TIEBA</label>
            </a>
            <a href="javascript:;">
                <label class="title">&nbsp;</label><br>
                <label class="sub_title">&nbsp;</label>
            </a>
        </div>
    </div>
</div>

<div id="centre" class="wrapper clearfix clear">
    <div id="side_bar" class="left">
    <a href="pay" class="btn btn_pay" title="唯一官方充值"></a>
    <a href="#" onClick="return confirm('IOS版本暂未上线，敬请期待');" target="_blank" class="btn btn_iTunes" title="Appstore下载">
        <img src="<?= ASSETS_URL; ?>images/app_download_icon.png"
             style="height: 59px; width: 235px; margin-left: -30px; margin-right: 0px; border-left-width: 0px;">
    </a>

    <a href="http://up.u776.com/poke/Pokemon_VS_523.apk" target="_blank" class="btn Android" title="Appstore下载">
        <img src="<?= ASSETS_URL; ?>images/Android_download_icon.png"
             style="height: 59px; width: 235px; margin-left: -30px; margin-right: 0px; border-left-width: 0px;">
    </a>
    <a href="gift" class="btn btn_gift" title="礼物领取">
        <img src="<?= ASSETS_URL; ?>images/gift_download_icon.png"
             style="height: 59px; width: 235px; margin-left: -30px; margin-right: 0px; border-left-width: 0px;">
    </a>
    <a class="btn btn_wechat" title="微信关注">
        <img src="<?= ASSETS_URL; ?>images/wechat_download_icon.png"
             style="height: 59px; width: 235px; margin-left: -30px; margin-right: 0px; border-left-width: 0px;">
    </a> <a href="javascript:;" class="btn btn_code" title="扫描微信">
        <img src="<?= ASSETS_URL; ?>images/code.png"
             style="margin-top: 22px; margin-left: 1px; height: 223px; border-left-width: 0px;">
    </a>


</div>
<div id="main" class="left">
    <?=$content; ?>
</div>
<div id="right_option" style="left: 1249px; display: block;">
    <a href="<?= __URL__ ?>#" class="first" title="意见反馈"></a>
    <a href="<?= __URL__ ?>#" id="js_top" class="last" title="回到顶部"></a>
</div>
</div>

<div id="copyright">
   <img src="<?= ASSETS_URL ?>images/copyright_logo.png" style="position:relative;left:336px"/>

    <div class="wrapper clearfix">

        <div class="info clearfix">
            <p style="margin-bottom: 15px;" id="games">
                <a href="/article/151" target="_blank">公司简介</a>
                <a href="/article/150" target="_blank">联系我们</a>
<!--                <a href="http://www.u591.com/tw" rel="game_1" target="_blank">天问</a>-->
<!--                <a href="http://www.u591.com/lw" rel="game_2" target="_blank">龙威</a>-->
<!--                <a href="http://www.u591.com/sw" rel="game_3" target="_blank">圣纹</a>-->
            </p>

            <p>
                Copyright@2010-2015 福州海牛网络技术有限公司 All Right Reserved.<br>
                <!-- 闽ICP备号-3 闽网文许字[2014]1118-005号 <br> -->
                <!-- <a href="#" target="_blank" title="站长统计">站长统计</a>  -->
                <a href="#" target="_blank">联系我们</a>
                <span>电话：0591-87678008</span>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= ASSETS_URL ?>js/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?=ASSETS_URL?>js/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript" src="<?= ASSETS_URL ?>js/common.js"></script>
<style type="text/css">
    body {
        background: #EDEDED url('<?=ASSETS_URL?>/images/1444459364949.jpg') no-repeat center 0px
    }
</style>
<script type="text/javascript" src="<?= ASSETS_URL ?>js/jquery.scrollLoading-min.js"></script>
<script type="text/javascript">
    $("img.scrollLoading").scrollLoading();
    function init_carousel() {
        var timer;
        var carousel_container = $("div.carousel_container").hover(function () {
            clearInterval(timer);
        }, function () {
            timer = setInterval("carousel()", 3000);
        });
        var imgs = carousel_container.find("img");
        var page_num = imgs.length;
        carousel_container.find("ul").eq(0).css("width", (page_num * 510) + "px");

        var cur = 0;
        var numbers = $("ol.numbers");
        for (var i = 0; i < page_num; i++) {
            numbers.append("<li class=\"left\" data-value='" + i + "'><span></span></li>");
        }
        numbers.find("li").eq(0).addClass("on").siblings().removeClass("on");
        numbers.find("li").each(function () {
            var that = $(this);
            that.hover(function () {
                cur = parseInt(that.attr("data-value"));
                show_img();
            });
        });

        window.carousel = function () {
            cur++;
            if (cur == page_num) {
                cur = 0;
            }
            show_img();
        }

        function show_img() {
            numbers.find("li").eq(cur).addClass("on").siblings().removeClass("on");
            carousel_container.stop(true, false).animate({ scrollLeft: 510 * cur}, 330);
        }

        timer = setInterval("carousel()", 3000);
    }
    init_carousel();


    $("#index_header ul.tab li").each(function () {
        var that = $(this);

        that.hover(function () {
            that.addClass("on").siblings().removeClass("on");
            $("#" + that.attr("data-id")).show().siblings("ul.container").hide();
        });
    });


    $(".beauty a.left").each(function () {
        var that = $(this);
        that.hover(function () {
            that.find("p.summary").show();
        }, function () {
            that.find("p.summary").hide();
        });
    });

</script>

</body>
</html>
