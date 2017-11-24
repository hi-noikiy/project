<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:67:"/data/home/hngame/public/../application/index/view/index/index.html";i:1510847886;s:62:"/data/home/hngame/public/../application/index/view/header.html";i:1510535219;s:62:"/data/home/hngame/public/../application/index/view/footer.html";i:1510764126;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Important Owl stylesheet -->
    <link rel="stylesheet" type="text/css" href="__static__/index/dist/assets/owl.carousel.css" />
    <!-- Default Theme -->
    <link rel="stylesheet" type="text/css" href="__static__/index/dist/assets/owl.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/style.css" />
    <title>海牛游戏</title>
</head>
<body>
<header>
    <div class="language clearfix">
        <a id="english" class="" href="javascript:void(0)"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="javascript:void(0)"><img src="__static__/index/img/chinese.png"></a>
    </div>
    <div class="navBox">
        <img id="logo" src="__static__/index/img/LOGO_color.png"/>
        <ul class="topNav cn" style="display: none;">
            <li><a href="<?php echo url('Index/index'); ?>">首页</a></li>
            <li><a href="<?php echo url('Game/game'); ?>">游戏</a></li>
            <li><a href="<?php echo url('News/news'); ?>">新闻</a></li>
           
        </ul>
        <ul class="topNav en" style="display: none;">
            <li><a href="<?php echo url('Index/index'); ?>">HOME</a></li>
            <li><a href="<?php echo url('Game/game'); ?>">GAMES</a></li>
            <li><a href="<?php echo url('News/news'); ?>">NEWS</a></li>
        </ul>
     
    </div>
</header>
<div class="screenbox">
    <div id="screen">
        <div class="owl-carousel owl-theme owl-loaded owl-drag">
            <?php if(is_array($gameList) || $gameList instanceof \think\Collection || $gameList instanceof \think\Paginator): $i = 0; $__LIST__ = $gameList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['recommend'] == 2): if($vo['show_way'] == 1): ?>
            <div>
                <img class="banner" src="<?php echo $vo['image_url']; ?>"/>
            <?php else: ?>
            <div style="height: auto;">
                <video name="video" src="<?php echo $vo['video_url']; ?>" autoplay loop></video>
            <?php endif; ?>    
                <dl class="btnBox">
                    <dt><a href="<?php echo $vo['apple_url']; ?>"><img src="__static__/index/img/ios.png"></a></dt>
                    <dt><a href="<?php echo $vo['android_url']; ?>"><img src="__static__/index/img/android.png"></a></dt>
                </dl>
                <p class="textBox cn" style="display: none;">
                    <span class="title"><?php echo $vo['name']; ?></span>
                    <br>
                    <span><?php echo $vo['description']; ?></span>
                </p>
                <p class="textBox en" style="display: none;">
                    <span class="title"><?php echo $vo['name_en']; ?></span>
                    <br>
                    <span><?php echo $vo['description_en']; ?></span>
                </p>
            </div>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="space"></div>
<?php if(is_array($gameList) || $gameList instanceof \think\Collection || $gameList instanceof \think\Paginator): $i = 0; $__LIST__ = $gameList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['recommend'] == 1): ?>
<div class="imgBox">
    <img class="showImg" src="<?php echo $vo['image_url']; ?>">
    <dl class="imgBtnBox">
        <dt><a href="<?php echo $vo['apple_url']; ?>"><img src="__static__/index/img/ios.png"></a></dt>
        <dt><a href="<?php echo $vo['android_url']; ?>"><img src="__static__/index/img/android.png"></a></dt>
    </dl>
    <p class="imgTextBox cn" style="display: none;">
        <span class="title"><?php echo $vo['name']; ?></span>
        <br>
        <span><?php echo $vo['description']; ?></span>
    </p>
    <p class="imgTextBox en" style="display: none;">
        <span class="title"><?php echo $vo['name_en']; ?></span>
        <br>
        <span><?php echo $vo['description_en']; ?></span>
    </p>
</div>
<?php endif; endforeach; endif; else: echo "" ;endif; ?>
<footer>
    <div class="funBtnBox cn" style="display: none;">
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=1'); ?>"><img src="__static__/index/img/about_us.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=1'); ?>">关于我们</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=2'); ?>"><img src="__static__/index/img/service_center.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=2'); ?>">客服中心</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=3'); ?>"><img src="__static__/index/img/contact_us.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=3'); ?>">联系我们</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('join/join'); ?>"><img src="__static__/index/img/join_us.png"/></a>
            <a href="<?php echo url('join/join'); ?>">加入我们</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=4'); ?>"><img src="__static__/index/img/business.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=4'); ?>">加盟我们</a>
        </div>
    </div>
    <div class="funBtnBox en" style="display: none;">
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=1'); ?>"><img src="__static__/index/img/about_us.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=1'); ?>">ABOUT US</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=2'); ?>"><img src="__static__/index/img/service_center.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=2'); ?>">SERVICE CENTER</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=3'); ?>"><img src="__static__/index/img/contact_us.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=3'); ?>">CONTACT US</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('join/join'); ?>"><img src="__static__/index/img/join_us.png"/></a>
            <a href="<?php echo url('join/join'); ?>">JOIN US</a>
        </div>
        <div class="funBtn">
            <a class="img" href="<?php echo url('websiteInfo/index?type=4'); ?>"><img src="__static__/index/img/business.png"/></a>
            <a href="<?php echo url('websiteInfo/index?type=4'); ?>">BUSINESS</a>
        </div>
    </div>
    <img id="footLogo" src="__static__/index/img/logo_white.png">
    <h1 class="cn" style="display: none;"><?php echo $copyright['v']; ?></h1>
    <p class="icp cn" style="display: none;"><?php echo $approve['v']; ?></p>
    <h1 class="en" style="display: none;"><?php echo $copyright_en['v']; ?></h1>
    <p class="icp en" style="display: none;"><?php echo $approve_en['v']; ?></p>
</footer>
<script type="text/javascript" src="__static__/index/js/jquery.js"></script>
<script type="text/javascript" src="__static__/index/dist/owl.carousel.js"></script>
<script type="text/javascript" src="__static__/index/js/language.js"></script>
<script>
    var owl = $('.owl-carousel');
    $(document).ready(function(){
        owl.owlCarousel({
            items:1,
            loop:true,
            autoplay:false,
            autoplayTimeout:2000,
            autoplayHoverPause:false
        });
        $('video').trigger('play');
    });
</script>
</body>
</html>