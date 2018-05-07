<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:125:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\index\index.html";i:1521602215;s:120:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\header.html";i:1521096116;s:120:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\footer.html";i:1519619557;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="keywords" content="口袋妖怪,口袋妖怪VS,精灵世界,精灵宝可梦,宠物小精灵,手游,游戏,免费手游,养成,收集" /> 
  <meta name="description" content="口袋妖怪VS新世代《精灵世界》完美重置复刻童年经典养成路线，是一款精灵收集养成、GBA策略对战、RPG角色冒险的手机游戏。" /> 
  <title>精灵世界官网_口袋妖怪VS新世代_海牛网络</title> 
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/dist/assets/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/dist/assets/owl.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/carousel_pc.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/switch.css" />
</head>
<body>
<header id="top">
	<div class="langage">
			<p>CN</p>
			<img src="__static__/pokemon/img/blue_triangle.png"/>
		</div>
		<ul class="langage_ul">
			<a href="/?cn"><li>CN</li></a>
			<a href="/?ru"><li>RU</li></a>
			<a href="/?vn"><li>VN</li></a>
			<a href="/?ar"><li>AR</li></a>
			<a href="/?us"><li style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">EN</li></a>
		</ul>
	<!--<div class="language clearfix" style="z-index: 900;">
        <a id="english" class="" href="/?us"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="/?cn"><img src="__static__/index/img/chinese.png"></a>
        <a id="vietnam" class="" href="/?vn"><img src="__static__/index/img/Vietnam.png"></a>
        <a id="russia" class="active" href="/?ru"><img src="__static__/index/img/Russia.png"></a>
    </div>-->
    <img class="top_bg" src="__static__/pokemon/img/top_bg.png">
    <div class="content contentB clearfix">
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" href="<?php echo url('Index/index'); ?>">官网首页<p>HOME</p></a>
        </div>
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" href="<?php echo url('News/news'); ?>">新闻资讯<p>NEWS</p></a>
        </div>
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" href="<?php echo url('Activity/activity'); ?>">活动中心<p>ACTIVITY</p></a>
        </div>
        <img id="logo" src="__static__/pokemon/img/logo.png">
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" href="<?php echo $fuli_url['value']; ?>">福利礼包<p>GIFT</p></a>
        </div>
        <div class="nav" id="community">
            <div class="nav_line"></div>
            <a class="nav_btn" href="javascript:void(0)">官方社区<p>COMMUNITY</p></a>
        </div>
        <div class="nav" id="contact">
            <div class="nav_line"></div>
            <a class="nav_btn" href="javascript:void(0)">联系客服<p>CONTACT</p></a>
        </div>
    </div>
    <div class="nav_hover community">
        <img class="hover_bg" src="__static__/pokemon/img/top_nav_bg.png">
        <div class="content clearfix">
            <div class="hover_block">
                <a href="<?php echo $baidu_bbs['value']; ?>" class="box">
                    <img class="baidu" src="__static__/pokemon/img/baidu.png">
                    <p><?php echo $baidu_bbs['desc']; ?></p>
                </a>
            </div>
            <div class="hover_block">
                <div class="box">
                    <img class="code" src="<?php echo $qrcode_wechart['value']; ?>">
                    <p><?php echo $qrcode_wechart['desc']; ?></p>
                </div>
            </div>
            <div class="hover_block">
                <div class="box">
                    <img class="code" src="<?php echo $qrcode_wb['value']; ?>">
                    <p><?php echo $qrcode_wb['desc']; ?></p>
                </div>
            </div>
            <div class="community_block">
            <?php if(is_array($qq_group) || $qq_group instanceof \think\Collection || $qq_group instanceof \think\Paginator): $i = 0; $__LIST__ = $qq_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <p><?php echo $vo['desc']; ?>：<?php echo $vo['value']; ?></p>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="nav_hover contact">
        <img class="hover_bg" src="__static__/pokemon/img/top_nav_bg.png">
        <div class="content clearfix">
            <div class="contact_block">
                <img src="__static__/pokemon/img/QQ.png">
                <div class="text_block">
                <?php if(is_array($qq_service) || $qq_service instanceof \think\Collection || $qq_service instanceof \think\Paginator): $i = 0; $__LIST__ = $qq_service;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <p><?php echo $vo['desc']; ?>：<?php echo $vo['value']; ?></p>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="contact_block long_fix">
                <img src="__static__/pokemon/img/tel_nav.png">
                <div class="text_block">
                    <h1><?php echo $service_phone['desc']; ?>：<?php echo $service_phone['value']; ?></h1>
                    <p><?php echo $service_online['desc']; ?>：<?php echo $service_online['value']; ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="index_1">
    <div class="dl_block">
        <div class="tips_text">
            宠物收集养成、GBA策略对战、RPG角色冒险
        </div>
        <div class="dl">
            <span>扫描关注微信公众号</span>
            <img class="dl_code" src="<?php echo $qrcode_wechart['value']; ?>">
            <div class="dl_btn">
                <a class="ios" href="<?php echo $pokemon['apple_url']; ?>" target="_blank">
                    <img style="left:18px;top:11px;" src="__static__/pokemon/img/ios.png">IOS下载
                </a>
                <a class="adr" href="<?php echo $pokemon['android_url']; ?>" target="_blank">
                    <img style="left:12px;top:10px;" src="__static__/pokemon/img/adr.png">安卓下载
                </a>
            </div>
            <a class="dl_gift" href="javascript:;">
                <img src="__static__/pokemon/img/gift.png">
                <div class="gift">领取礼包</div>
            </a>

        </div>
    </div>
    <img class="index_1_bg" src="__static__/pokemon/img/bg.jpg"/>
    <div class="index_1_content ">
        <div class="position_fix_btm">
            <div class="content clearfix">
                <div class="auto_play_box">
                    <div id="owl-demo" class="owl-carousel owl-theme">
                    <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 1): ?>
                        <div class="item"><a href="<?php if(($vo['url'] != null) and ($vo['url'] != '')): ?><?php echo $vo['url']; else: ?>javascript:void(0);<?php endif; ?>" alt="<?php echo $vo['name']; ?>" target="_blank"><img src="<?php echo $vo['image_url']; ?>" alt="<?php echo $vo['name']; ?>" style="width: 520px;height: 300px;"></a></div>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <div class="list_box">
                    <ul class="list_tit clearfix">
                    	<li class="active"><a href="javascript:void(0)">最新</a><img class="triangle" src="__static__/pokemon/img/blue_triangle.png"></li>
                        <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li><a href="javascript:void(0)"><?php echo $vo['name']; ?></a><img class="triangle" src="__static__/pokemon/img/blue_triangle.png"></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <a class="more" href="<?php echo url('News/news'); ?>">more+</a>
                    </ul>
                    <div class="list_block" id="list_0">
                    	<?php $var = '1'; if(is_array($topNews) || $topNews instanceof \think\Collection || $topNews instanceof \think\Paginator): $i = 0; $__LIST__ = $topNews;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tops): $mod = ($i % 2 );++$i;if($var <= 1): ?>
                        <div class="list_first">
                            <a href="javascript:openDetail(<?php echo $tops['id']; ?>)" style="color: #fff;"><span style="display:block;width:100%;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $tops['title']; ?></span></a>
                            <img class="bird" src="__static__/pokemon/img/nan_bird.png">
                        </div>
                        <?php $var = $var+1; endif; endforeach; endif; else: echo "" ;endif; $var = '1'; if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($var <= 3): ?>
                         <a href="javascript:openDetail(<?php echo $news['id']; ?>)" style="position: relative;" class="list">
                            <p style="display:block;width:80%;margin:0px;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;z-index: 20;"><?php echo $news['title']; ?></p>
                            <span style="position: absolute;right: 10px;top: 0px;z-index: 30;"><?php echo $news['ctime']; ?></span>
                        </a>
                        <?php $var = $var+1; endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="list_block" id="list_<?php echo $key+1; ?>"style="display: none;">
                    	<?php $var = '1'; if(is_array($topNews) || $topNews instanceof \think\Collection || $topNews instanceof \think\Paginator): $i = 0; $__LIST__ = $topNews;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tops): $mod = ($i % 2 );++$i;if($var <= 1 and $tops['type_id'] == $vo['id']): ?>
                        <div class="list_first">
                            <a href="javascript:openDetail(<?php echo $tops['id']; ?>)" style="color: #fff;"><span style="display:block;width:100%;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $tops['title']; ?></span></a>
                            <img class="bird" src="__static__/pokemon/img/nan_bird.png">
                        </div>
                        <?php $var = $var+1; endif; endforeach; endif; else: echo "" ;endif; $var = '1'; if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($news['type_id'] == $vo['id']): if($var <= 3): ?>
                         <a href="javascript:openDetail(<?php echo $news['id']; ?>)" style="position: relative;" class="list">
                            <p style="display:block;width:80%;margin:0px;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;z-index: 20;"><?php echo $news['title']; ?></p>
                            <span style="position: absolute;right: 10px;top: 0px;z-index: 30;"><?php echo $news['ctime']; ?></span>
                        </a>
                        <?php $var = $var+1; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="content clearfix" style="margin-top:70px">
                <?php if(is_array($linkList) || $linkList instanceof \think\Collection || $linkList instanceof \think\Paginator): $i = 0; $__LIST__ = $linkList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['platform'] == 1): ?>
                <a href="<?php echo (isset($vo['url']) && ($vo['url'] !== '')?$vo['url']:'javascript:void(0)'); ?>" class="switch_block">
                    <img style="left:20px;top:0;" src="<?php echo $vo['image_url']; ?>">
                    <div class="switch"><div class="switch_content"></div></div>
                    <div class="switch_text"><?php echo $vo['name']; ?></div>
                </a>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="index_2">
    <img class="index_2bg" src="__static__/pokemon/img/index_2.png">
    <div class="content">
        <div class="index_2_tit">
            游戏特色
        </div>
        <div class="caroursel_box">
            <div class = "caroursel poster-main" data-setting = '{
	        "width":1100,
	        "height":500,
	        "posterWidth":780,
	        "posterHeight":480,
	        "scale":0.8,
	        "dealy":"5000",
	        "algin":"middle"
	    }'>
                <ul class = "poster-list">
                <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 2): ?>
                    <li class = "poster-item">
                    <a href="<?php if(($vo['url'] != null) and ($vo['url'] != '')): ?><?php echo $vo['url']; else: ?>javascript:void(0);<?php endif; ?>" alt="<?php echo $vo['name']; ?>">
                    <img src="<?php echo $vo['image_url']; ?>" width = "100%" height="100%">
                    </a>
                    </li>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <div class = "poster-btn poster-prev-btn"></div>
                <div class = "poster-btn poster-next-btn"></div>
            </div>
        </div>
    </div>
</div>
<div class="alert-win" style="display: none">
    <img class="close" src="__static__/pokemon/img/close.png">
    <div class="item"><span>恭喜你！</span>获得</div>
    <div class="item">《精灵世界》官网特权礼包</div>
    <div class="item"><input id="privilegeCode" type="text" value="<?php echo $privilege_code['value']; ?>"><a href="javascript:copyCode();">复制</a> </div>
</div>
<footer>
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/dist/owl.carousel.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/top_nav.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.carousel.js"></script>
</body>
<script>
    Caroursel.init($('.caroursel'));
</script>
<script>
    var newsUrl="<?php echo url('News/news'); ?>";
    var detailUrl="<?php echo url('News/detail'); ?>";
    var owl = $('.owl-carousel');
    $(document).ready(function(){
        owl.owlCarousel({
            items:1,
            loop:true,
            autoplay:true,
            autoplayTimeout:2000,
            autoplayHoverPause:false,
            nav:true,
            navText:["<",">"]
        });
    });
    owl.mouseenter(function(){
        owl.trigger('stop.owl.autoplay')
    }).mouseleave(function(){
        owl.trigger('play.owl.autoplay',[1000])
    });
    $(".close").click(function(){
        $(this).parent(".alert-win").hide()
    });
    $(".dl_gift").click(function(){
        $(".alert-win").show()
    })

    function copyCode(){   
        var e=document.getElementById("privilegeCode");//对象是contents 
        e.select(); //选择对象 
        document.execCommand("Copy"); 
        alert("复制成功");
    }   

    $(".list_tit li").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        var i=$(this).index();
        $("#list_"+i).show().siblings(".list_block").hide()
    })

    function openNews(id){
       // window.location=newsUrl.substr(0,newsUrl.length-5)+"/typeId/"+id;
        window.open(newsUrl.substr(0,newsUrl.length-5)+"/typeId/"+id);
    }

    function openDetail(id){
       // window.location=detailUrl.substr(0,detailUrl.length-5)+"/id/"+id;
        window.open(detailUrl.substr(0,detailUrl.length-5)+"/id/"+id);
    }
</script>
</html>