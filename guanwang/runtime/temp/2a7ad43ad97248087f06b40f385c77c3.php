<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:138:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\activity\activity_mobile.html";i:1519619557;s:127:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\header_mobile.html";i:1521775153;s:127:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/pokemon\view\footer_mobile.html";i:1519619557;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>精灵世界VS</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
</head>
<body>
<div class="top_BgImg">
	<div class="top_down">
    <div class="moblie_content">
        <img class="ball" src="__static__/pokemon/img/ball.png">
        <div class="moblie_top_text">
            <h1>《口袋妖怪VS》</h1>
            <p>3D重制宠物小精灵</p>
        </div>
        <a class="down_load" href="javascript:downLoadApp();">
            <img src="__static__/pokemon/img/download_btn.png">
        </a>
    </div>
	</div>
</div>

<script type="text/javascript">
    function downLoadApp(){
        if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)){
            location.href = "<?php echo $pokemon['apple_url']; ?>";
        }else if(navigator.userAgent.match(/android/i)){
            location.href = "<?php echo $pokemon['android_url']; ?>";//android 下载地址
        }
    }
</script>
<div class="bread">
    <div class="moblie_content moblie_nav">
        <a href="javascript:openIndex()">官网首页</a><a href="javascript:void(0)">活动中心</a>
    </div>
</div>
<div class="selected_card clearfix">
    <a href="javascript:void(0)" class="card active">当前活动</a>
    <a href="javascript:void(0)" class="card">往期活动</a>
</div>
<div class="card_box">
    <div class="moblie_content">
        <?php if(is_array($activityList) || $activityList instanceof \think\Collection || $activityList instanceof \think\Paginator): $i = 0; $__LIST__ = $activityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 1): ?>
            <div class="card_list">
                <div class="top_block clearfix">
                    <img src="<?php echo $vo['image_url']; ?>">
                    <div class="right">
                        <h1><?php echo $vo['title']; ?></h1>
                        <p>活动时间：<span><?php echo $vo['begin_time']; ?></span>—<span><?php echo $vo['end_time']; ?></span></p>
                    </div>
                </div>
                <div class="tips">
                    <?php echo $vo['description']; ?>
                </div>
                <a href="javascript:<?php if($vo['url'] == ''): ?>void(0);<?php else: ?>window.open('<?php echo $vo['url']; ?>')<?php endif; ?>" class="hide">
                    <div class="tit">
                        活动奖励
                    </div>
                    <p>
                        <?php echo htmlspecialchars_decode($vo['content']); ?>
                    </p>
                </a>
            </div>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        <div class="moblie btn_link color_b">
            <a name="weixinAt" href="javascript:void(0)">
                <img src="__static__/pokemon/img/btn_m_wx.png">
                <p>关注微信公众号</p>
                <h1>口袋妖怪VS游戏</h1>
            </a>
            <a href="javascript:joinQQ();">
                <img src="__static__/pokemon/img/btn_m_qq.png">
                <p>官方QQ</p>
                <h1>一键加入</h1>
            </a>
            <a href="javascript:followWB();">
                <img src="__static__/pokemon/img/btn_m_wb.png">
                <p>关注微博</p>
                <h1>互动爆料</h1>
            </a>
        </div>
        <div class="bottom_tel">
            <img class="tel_moblie" src="__static__/pokemon/img/tel_1.png?t=123">
            <div class="serive">客服电话</div>
            <div class="number">0591-87688008</div>
            <img class="starSky" src="__static__/pokemon/img/bottom_bg.png">
        </div>
    </div>
</div>
<div class="card_box endness">
    <div class="moblie_content">
        <?php if(is_array($activityList) || $activityList instanceof \think\Collection || $activityList instanceof \think\Paginator): $k = 0; $__LIST__ = $activityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;if($vo['type'] == 2): ?>
            <div class="card_list">
                <div class="top_block clearfix">
                    <img src="<?php echo $vo['image_url']; ?>">
                    <div class="right">
                        <h1><?php echo $vo['title']; ?></h1>
                        <p>活动时间：<span><?php echo $vo['begin_time']; ?></span>—<span><?php echo $vo['end_time']; ?></span></p>
                    </div>
                    <img class="m_end" src="__static__/pokemon/img/end.png">
                </div>
                <div class="tips">
                    <?php echo $vo['description']; ?>
                </div>
                <a href="javascript:<?php if($vo['url'] == ''): ?>void(0);<?php else: ?>window.open('<?php echo $vo['url']; ?>')<?php endif; ?>" class="hide">
                    <div class="tit">
                        活动奖励
                    </div>
                    <p>
                        <?php echo htmlspecialchars_decode($vo['content']); ?>
                    </p>
                </a>
            </div>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        <div class="moblie btn_link color_b">
            <a name="weixinAt" href="javascript:void(0)">
                <img src="__static__/pokemon/img/btn_m_wx.png">
                <p style="color: #000;">关注微信公众号</p>
                <h1 style="color: #000;">口袋妖怪VS游戏</h1>
            </a>
            <a href="javascript:joinQQ();">
                <img src="__static__/pokemon/img/btn_m_qq.png">
                <p style="color: #000;">官方QQ</p>
                <h1 style="color: #000;">一键加入</h1>
            </a>
            <a href="javascript:followWB();">
                <img src="__static__/pokemon/img/btn_m_wb.png">
                <p style="color: #000;">关注微博</p>
                <h1 style="color: #000;">互动爆料</h1>
            </a>
        </div>
        <div class="bottom_tel">
            <img class="tel_moblie" src="__static__/pokemon/img/tel_1.png?t=123">
            <div class="serive">客服电话</div>
            <div class="number">0591-87688008</div>
            <img class="starSky" src="__static__/pokemon/img/bottom_bg.png">
        </div>
    </div>
</div>
<div id="cover">
    <div id="imageQrDiv"><img src="<?php echo $qrcode_wechart['value']; ?>" /></div>
    <img class="close-cover" src="__static__/pokemon/img/close_1.png">
    <div class="cover-text">
        <p>长按二维码保存图片</p>
        <p>关注口袋妖怪VS游戏官方公众号</p>
    </div>
</div>
<div style="width: 100%;overflow: hidden;">
<footer class="moblie">
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
</div>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script>
    $(".card_list").click(function(){
        $(this).addClass("active");
    });
    $(".card").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        idx=$(this).index();
        $(".card_box").eq(idx).show().siblings(".card_box").hide();
    })

    function openIndex(){
        location.href="<?php echo url('Mobile/index'); ?>";
    }
</script>
<script>
    function followWX(){
        var url="<?php echo $link_wechart['value']; ?>";
        if(!isWeixin()){
            alert("请在微信客户端点击此按钮进行关注");
            return false;
        }
        if(url&&url!=''){
            location.href="<?php echo $link_wechart['value']; ?>";
        }   
    }

    function joinQQ(){
        var url="<?php echo $link_qq['value']; ?>";
        if(!isQQ()){
            alert("请在QQ客户端点击此按钮加入");
            return false;
        }
        if(url&&url!=''){
            location.href="<?php echo $link_qq['value']; ?>";
        } 
    }

    function followWB(){
        var url="<?php echo $link_wb['value']; ?>";
        if(url&&url!=''){
            location.href="<?php echo $link_wb['value']; ?>";
        } 
    }

    isWeixin = function (){
        return deviceDetect('MicroMessenger');
    }

    isQQ = function (){
        return deviceDetect('QQ');
    }

    /**
    * 设备检测函数
    * @param  {String} needle [特定UA标识]
    * @return {Boolean}
    */
    var detectorUA = navigator.userAgent.toLowerCase();
    deviceDetect = function(needle) {
        needle = needle.toLowerCase();
        return detectorUA.indexOf(needle) !== -1;
    }

    $("a[name='weixinAt']").click(function(){
        $("#cover").show();
    });
    $(".close-cover").click(function(){
        $("#cover").hide();
    })
</script>
</body>
</html>