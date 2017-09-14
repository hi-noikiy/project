<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $this->title ?></title>
    <meta name="keywords" content="<?= $this->keyword ?>">
    <meta name="description" content="<?= $this->desc ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.55">
    <link href="<?= ASSETS_URL; ?>css/common.css" rel="stylesheet" type="text/css">
    <link href="<?= ASSETS_URL; ?>css/white.css" rel="stylesheet" type="text/css">
    <script charset="utf-8" src="<?= ASSETS_URL; ?>js/jquery.js"></script>
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
            <img src="<?=ASSETS_URL?>images/web_61.png">
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

<div class="wrapper clearfix clear">
    <?=$content; ?>
</div>

<div id="copyright" style="min-width:1900px;">
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
</body>
</html>