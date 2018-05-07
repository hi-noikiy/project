<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:131:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\activity\activity.html";i:1519619557;s:120:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\header.html";i:1521096116;s:120:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\footer.html";i:1519619557;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>精灵世界VS</title>
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
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
<div class="news_block1">
    <img class="show" src="__static__/pokemon/img/activity_center.jpg">
    <div class="news_nav">
        <div class="content clearfix activity">
            <a href="javascript:void(0)" class="new_nav_block active">
                <span>当前活动</span>
                <img class="selected_fish" src="__static__/pokemon/img/selected.png">
                <div class="selected_line"></div>
            </a>
            <a href="javascript:void(0)" class="new_nav_block">
                <span>往期活动</span>
                <img class="selected_fish" src="__static__/pokemon/img/selected.png">
                <div class="selected_line"></div>
            </a>
        </div>
    </div>
</div>
<div id="block_0" class="activity_content">
    <div class="content clearfix">
    <?php if(is_array($activityList) || $activityList instanceof \think\Collection || $activityList instanceof \think\Paginator): $i = 0; $__LIST__ = $activityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 1): ?>
        <div class="activity_block">
            <div class="hover_content">
                <div class="hover_txt">
                	<div class="hover_txt_box">
	                    <div>活动奖励：</div>
	                    <p>
	                        <?php echo htmlspecialchars_decode($vo['content']); ?>
	                    </p>
	                    <a href="javascript:<?php if($vo['url'] == ''): ?>void(0);<?php else: ?>window.open('<?php echo $vo['url']; ?>')<?php endif; ?>">》查看详细《</a>
	                </div>
                </div>
            </div>
            <a href="javascript:void(0)" class="detail_btn">查看详情</a>
            <div class="block_img">
                <img src="<?php echo $vo['image_url']; ?>">
            </div>
            <div class="block_text">
                <h1><?php echo $vo['title']; ?></h1>
                <p>活动时间:<span><?php echo $vo['begin_time']; ?>——<?php echo $vo['end_time']; ?></span></p>
                <div><?php echo $vo['description']; ?></div>
            </div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>

<div id="block_1" class="activity_content end">
    <div class="content clearfix">
    <?php if(is_array($activityList) || $activityList instanceof \think\Collection || $activityList instanceof \think\Paginator): $i = 0; $__LIST__ = $activityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 2): ?>
        <div class="activity_block">
            <div class="hover_content">
                <div class="hover_txt">
                	<div class="hover_txt_box">
	                    <div>活动奖励：</div>
	                    <p>
	                        <?php echo htmlspecialchars_decode($vo['content']); ?>
	                    </p>
	                    <a href="javascript:void(0)">已结束</a>
	                </div>
                </div>
            </div>
            <a href="javascript:void(0)" class="end_img"><img src="__static__/pokemon/img/end.png"></a>
            <div class="block_img">
                <img src="<?php echo $vo['image_url']; ?>">
            </div>
            <div class="block_text">
                <h1><?php echo $vo['title']; ?></h1>
                <p>活动时间:<span><?php echo $vo['begin_time']; ?>——<?php echo $vo['end_time']; ?></span></p>
                <div><?php echo $vo['description']; ?></div>
            </div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<div class="activity_bottom">
    <img style="margin-left:30px;" src="__static__/pokemon/img/pose2.png"><img class="activity_code" src="<?php echo $qrcode_game_wechart['value']; ?>"><img src="__static__/pokemon/img/pose1.png">
    <p>扫码关注口袋妖怪VS游戏公众号</p>
    <p>了解最新福利活动资讯</p>
</div>
<footer>
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/top_nav.js"></script>
<script>
    $(".new_nav_block").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        idx=$(this).index();
        $("#block_"+idx).show().siblings(".activity_content").hide();
    });
    $(".activity_block").mouseenter(function(){
        $(this).toggleClass("mouse_enter")
    }).mouseleave(function(){
        $(this).toggleClass("mouse_enter")
    })
</script>
</body>
</html>