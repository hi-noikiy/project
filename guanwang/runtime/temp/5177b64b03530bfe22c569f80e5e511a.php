<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"F:\wamp\www\project\guanwang\public/../application/index\view\index\index.html";i:1520389109;s:73:"F:\wamp\www\project\guanwang\public/../application/index\view\header.html";i:1519984368;s:73:"F:\wamp\www\project\guanwang\public/../application/index\view\footer.html";i:1516180793;}*/ ?>
﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Important Owl stylesheet -->
    <link rel="stylesheet" type="text/css" href="__static__/index/dist/assets/owl.carousel.css" />
    <!-- Default Theme -->
    <link rel="stylesheet" type="text/css" href="__static__/index/dist/assets/owl.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/style.css" />
	<!--南美-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112166484-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-112166484-1');
	</script>
	<!--越南-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112166484-2"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-112166484-2');
	</script>
	
	
	<!--俄文-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112166484-3"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-112166484-3');
	</script>
	
	<!--国内-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112166484-4"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-112166484-4');
	</script>
  <meta name="keywords" content="海牛网络,热门手游,手机游戏,口袋妖怪VS,精灵世界,天问,魔兽小宠物,衣范儿,龙威,手游,充值" /> 
  <meta name="description" content="海牛网络是集手机游戏研发、发行运营为一体的网络技术有限公司" /> 
  <title>海牛网络_seacow官网_海牛网络技术有限公司官方网站</title> 
</head>
<body>
<header>
    <!--<div class="language clearfix">
        <a id="english" class="" href="javascript:void(0)"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="javascript:void(0)"><img src="__static__/index/img/chinese.png"></a>
        <a id="vietnam" class="" href="javascript:void(0)"><img src="__static__/index/img/Vietnam.png"></a>
        <a id="russia" class="" href="javascript:void(0)"><img src="__static__/index/img/Russia.png"></a>
    </div>-->
    <div class="langage">
			<p>CN</p>
			<img src="__static__/pokemon/img/blue_triangle.png"/>
		</div>
		<ul class="langage_ul">
			<a><li>CN</li></a>
			<a><li style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;border-bottom: 1px solid #ACACAC;">EN</li></a>
		</ul>
    <div class="navBox">
        <img id="logo" src="__static__/index/img/LOGO_color.png"/>
        <ul class="topNav cn" style="display: none;">
            <li><a href="<?php echo url('Index/index'); ?>">首页</a></li>
            <li><a href="<?php echo url('Game/game'); ?>">游戏</a></li>
            <li><a href="<?php echo url('News/news'); ?>">新闻</a></li>
            <!--<li><a href="http://www.u591.com/newpay/all.php">充值中心</a></li>-->
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
                	<!--<a class="cn" href="/pokemon?cn" style="position:relative;top:-30px;color: #EDDE34;font-size: 16px;margin-right: 30px;display: none;" target="_blank">进入官网</a>
                	<a class="en" href="/pokemon?us" style="position:relative;top:-30px;color: #EDDE34;font-size: 16px;margin-right: 30px;display: none;" target="_blank">Access to the official network</a>-->
                    <dt class="cn" style="display: none;"><a href="http://pokemon.u776.com?cn" target="_blank"><img src="__static__/index/img/official.png"></a></dt>
                    <dt class="en" style="display: none;"><a href="http://pokemon.u776.com?us" target="_blank"><img src="__static__/index/img/official.png"></a></dt>
                    <dt><a href="<?php echo $vo['apple_url']; ?>" target="_blank"><img src="__static__/index/img/ios.png"></a></dt>
                    <dt><a href="<?php echo $vo['android_url']; ?>" target="_blank"><img src="__static__/index/img/android.png"></a></dt>
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
    <h1 class="cn" style="display: none;"><?php echo $copyright['value']; ?></h1>
    <p class="icp cn" style="display: none;"><?php echo $approve['value']; ?></p>
    <h1 class="en" style="display: none;"><?php echo $copyright_en['value']; ?></h1>
    <p class="icp en" style="display: none;"><?php echo $approve_en['value']; ?></p>
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
            nav:true,
            navText:'',
            autoplay:false,
            autoplayTimeout:2000,
            autoplayHoverPause:false
        });
        $('video').trigger('play');
    });
</script>
</body>
</html>