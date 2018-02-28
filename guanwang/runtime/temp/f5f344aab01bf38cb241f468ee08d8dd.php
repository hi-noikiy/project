<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:81:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\mobile\index.html";i:1515641079;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\header_mobile.html";i:1515641079;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\footer_mobile.html";i:1515641079;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <title>精灵世界VS</title>
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/jquery.fullpage.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/dist/assets/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/dist/assets/owl.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/carousel_pc.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/carousel_moblie.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
</head>
<body>
<div id="fullpage">
    <div class="section" id="index_1">
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
        <script type="text/javascript">
            function downLoadApp(){
                if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)){
                    location.href = "<?php echo $pokemon['apple_url']; ?>";
                }else if(navigator.userAgent.match(/android/i)){
                    location.href = "<?php echo $pokemon['android_url']; ?>";//android 下载地址
                }
            }
        </script>
        <a id="load" href="javascript:downLoadApp();">
            <img src="__static__/pokemon/img/down_load.png">
        </a>
        <img class="down_tip" src="__static__/pokemon/img/down_arrow.png">
    </div>
    <div class="section" id="index_2">
        <!--<img src="img/moblie_index_content.png">-->
        <div class="index_2_content">
            <div class="moblie_content">
                <div class="auto_play_box_moblie">
                    <div id="owl-demo" class="owl-carousel owl-theme">
                    <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 1): ?>
                        <div class="item"><a href='<?php if(($vo['url'] != null) and ($vo['url'] != '')): ?><?php echo $vo['url']; else: ?>javascript:void(0);<?php endif; ?>' alt="<?php echo $vo['name']; ?>"><img src="<?php echo $vo['image_url']; ?>" alt="<?php echo $vo['name']; ?>"></a></div>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <div class="moblie list_box">
                    <ul class="list_tit clearfix">
                        <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li <?php if($key == 0): ?>class="active"<?php endif; ?>><a href="javascript:void(0)"><?php echo $vo['name']; ?></a><img class="triangle" src="__static__/pokemon/img/blue_triangle.png"></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <a class="more" href="javascript:openNews()">more+</a>
                    </ul>
                    <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="list_block" id="list_<?php echo $key; ?>" <?php if($key > 0): ?>style="display: none;"<?php endif; ?>>
                        <div class="list_first">
                            <a href="javascript:openNews(<?php echo $vo['id']; ?>)" style="color: #fff;"><?php echo $vo['description']; ?></a>
                            <img class="bird" src="__static__/pokemon/img/nan_bird.png">
                        </div>
                        <?php $var = '1'; if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($news['type_id'] == $vo['id']): if($var <= 3): ?>
                        <a href="javascript:openDetail(<?php echo $news['id']; ?>)" class="list">
                            <?php echo $news['title']; ?>
                            <span><?php echo $news['create_time']; ?></span>
                        </a>
                        <?php $var = $var+1; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="moblie btn_box">
                    <?php if(is_array($linkList) || $linkList instanceof \think\Collection || $linkList instanceof \think\Paginator): $i = 0; $__LIST__ = $linkList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['platform'] == 2): ?>
                    <a href="<?php echo (isset($vo['url']) && ($vo['url'] !== '')?$vo['url']:'javascript:void(0)'); ?>" class="switch_block">
                        <div><img src="<?php echo $vo['image_url']; ?>"></div>
                        <p><?php echo $vo['name']; ?></p>
                    </a>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="moblie btn_big_box">
                    <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 3): ?>
                    <a href="<?php echo (isset($vo['url']) && ($vo['url'] !== '')?$vo['url']:'javascript:void(0)'); ?>">
                        <img src="<?php echo $vo['image_url']; ?>">
                    </a>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="moblie index_tit">
                    游戏特色
                </div>
                <div class="moblie caroursel_box">
                    <div class = "caroursel poster-main moblie" data-setting = '{
	                "width":700,
	                "height":400,
	                "posterWidth":240,
	                "posterHeight":400,
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
                <div class="moblie btn_link">
                    <a id="weixinAt" href="javascript:void(0)">
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
                <footer class="moblie">
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
            </div>
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
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.easings.min.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/scrolloverflow.min.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.fullpage.js"></script>
<script type="text/javascript" src="__static__/pokemon/dist/owl.carousel.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.carousel.js"></script>
<script>
    var newsUrl="<?php echo url('News/news'); ?>";
    var detailUrl="<?php echo url('News/detail'); ?>";
    Caroursel.init($('.caroursel'));
    $(function(){
        $('#fullpage').fullpage({
            scrollOverflow: true,
        });
    });
    var owl = $('.owl-carousel');
    $(document).ready(function(){
        owl.owlCarousel({
            items:1,
            loop:true,
            autoplay:true,
            autoplayTimeout:2000,
            autoplayHoverPause:false,
            nav:false,
        });
    });
    owl.mouseenter(function(){
        owl.trigger('stop.owl.autoplay')
    }).mouseleave(function(){
        owl.trigger('play.owl.autoplay',[1000])
    });
    $(".list_tit li").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        var i=$(this).index();
        $("#list_"+i).show().siblings(".list_block").hide()
    })

    function downLoadApp(){
        if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)){
            location.href = "<?php echo $pokemon['apple_url']; ?>";
        }else if(navigator.userAgent.match(/android/i)){
            location.href = "<?php echo $pokemon['android_url']; ?>";//android 下载地址
        }
    }

    function openNews(id){
        if(id){
            location.href=newsUrl.substr(0,newsUrl.length-5)+"/channel/mobile/typeId/"+id;
        }else{
            location.href=newsUrl.substr(0,newsUrl.length-5)+"/channel/mobile";
        }
    }

    function openDetail(id){
        location.href=detailUrl.substr(0,detailUrl.length-5)+"/channel/mobile/id/"+id;
    }
</script>
<script>
    function followWX(){
        /*var url="<?php echo $link_wechart['value']; ?>";
        if(!isWeixin()){
            alert("请在微信客户端打开此页面后点击该按钮进行关注");
            return false;
        }
        if(url&&url!=''){
            location.href="<?php echo $link_wechart['value']; ?>";
        }*/   
    }

    function joinQQ(){
        var url="<?php echo $link_qq['value']; ?>";
        if(!isQQ()){
            alert("请在QQ客户端打开此页面后点击该按钮加入");
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

    $("#weixinAt").click(function(){
        $("#cover").show();
    });
    $(".close-cover").click(function(){
        $("#cover").hide();
    })
</script>
</body>
</html>