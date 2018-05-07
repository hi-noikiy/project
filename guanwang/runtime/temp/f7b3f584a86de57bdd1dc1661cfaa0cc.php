<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:122:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\index\mobile.html";i:1521773117;s:128:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\header_mobile_lang.html";i:1521096007;s:123:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\header_mobile.html";i:1521775568;s:123:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\footer_mobile.html";i:1520402144;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <title>魔兽小宠物官网_海牛网络</title>
    <link rel="stylesheet" type="text/css" href="__static__/war/css/jquery.fullpage.css" />
    <link rel="stylesheet" type="text/css" href="__static__/war/dist/assets/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="__static__/war/dist/assets/owl.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="__static__/war/css/carousel_pc.css" />
    <link rel="stylesheet" type="text/css" href="__static__/war/css/carousel_moblie.css" />
    <link rel="stylesheet" type="text/css" href="__static__/war/css/pmstyle.css" />
</head>
<body>
<div id="fullpage" lang="<?php echo $lang; ?>">
    <div class="section" id="index_1">
    	<!--<div class="header_lang">
    <div class="langage">
		<p>CN</p>
		<img src="__static__/war/img/blue_triangle.png"/>
	</div>
	<ul class="langage_ul">
		<a href="http://msxcw.u776.com?cn"><li>CN</li></a>
		<a href="http://msxcw.u776.com?ru"><li>RU</li></a>
		<a href="http://msxcw.u776.com?vn"><li>VN</li></a>
		<a href="http://msxcw.u776.com?us"><li style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;border-bottom: 1px solid #ACACAC;">EN</li></a>
	</ul>
    <div class="navBox">
        <img id="logo" src="__static__/index/img/LOGO_color.png"/>
    </div>
</div>-->
        <div class="top_BgImg">
	<div class="top_down">
	    <div class="moblie_content">
	        <img class="ball" src="__static__/war/img/ball.png">
	        <div class="moblie_top_text">
	            <h1>《魔兽小宠物》</h1>
	            <p>3D重制宠物小精灵</p>
	        </div>
	        <a class="down_load" href="javascript:downLoadApp();">
	            <img src="__static__/war/img/download_btn.png">
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
        <a id="load" href="javascript:downLoadApp();">
            <img src="__static__/war/img/down_load.png">
        </a>
        <img class="down_tip" src="__static__/war/img/down_arrow.png">
    </div>
    <div class="section" id="index_2">
        <!--<img src="img/moblie_index_content.png">-->
        <div class="index_2_content">
            <div class="moblie_content">
                <div class="auto_play_box_moblie">
                    <div id="owl-demo" class="owl-carousel owl-theme">
                    <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 1): ?>
                        <div class="item"><a href='<?php if(($vo['url'] != null) and ($vo['url'] != '')): ?>javascript:openImg("<?php echo $vo['url']; ?>");<?php else: ?>javascript:void(0);<?php endif; ?>' alt="<?php echo $vo['name']; ?>"><img src="<?php echo $vo['image_url']; ?>" alt="<?php echo $vo['name']; ?>"></a></div>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <div class="moblie list_box">
                    <ul class="list_tit clearfix">
                        <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li <?php if($key == 0): ?>class="active"<?php endif; ?>><a href="javascript:void(0)"><?php echo $vo['name']; ?></a><img class="triangle" src="__static__/war/img/blue_triangle.png"></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <a class="more" href="javascript:openNews()">+</a>
                    </ul>
                    <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="list_block" id="list_<?php echo $key; ?>" <?php if($key > 0): ?>style="display: none;"<?php endif; ?>>
                    	<?php $var = '1'; if(is_array($topNews) || $topNews instanceof \think\Collection || $topNews instanceof \think\Paginator): $i = 0; $__LIST__ = $topNews;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tops): $mod = ($i % 2 );++$i;if($var <= 1 and $tops['type_id'] == $vo['id']): ?>
                        <div class="list_first">
                            <a href="javascript:openDetail(<?php echo $tops['id']; ?>)" style="color: #fff;"><span style="display:block;width:100%;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $tops['title']; ?></span></a>
                            <img class="bird" src="__static__/war/img/nan_bird.png">
                        </div>
                        <?php $var = $var+1; endif; endforeach; endif; else: echo "" ;endif; ?>
                        <!--<div class="list_first">
                            <a href="javascript:openNews(<?php echo $vo['id']; ?>)" style="color: #fff;display:block;width:100%;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $vo['description']; ?></a>
                            <img class="bird" src="__static__/war/img/nan_bird.png">
                        </div>-->
                        <?php $var = '1'; if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($news['type_id'] == $vo['id']): if($var <= 3): ?>
                        <a href="javascript:openDetail(<?php echo $news['id']; ?>)" style="position: relative;" class="list">
                            <p style="display:block;width:80%;margin:0px;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;z-index: 20;"><?php echo $news['title']; ?></p>
                            <span style="position: absolute;right: 5px;top: 0px;z-index: 30;"><?php echo $news['ctime']; ?></span>
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
                <div class="moblie caroursel_box" style="height: 185px;">
                    <div class = "caroursel poster-main moblie" data-setting = '{
	                "width":690,
	                "height":185,
	                "posterWidth":360,
	                "posterHeight":185,
	                "scale":0.8,
	                "dealy":"5000",
	                "algin":"middle"
	            }'>
                        <ul class = "poster-list">
                        <?php if(is_array($bannerList) || $bannerList instanceof \think\Collection || $bannerList instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['type'] == 2): ?>
                            <li class = "poster-item">
                            <a href="<?php if(($vo['url'] != null) and ($vo['url'] != '')): ?>javascript:openImg("<?php echo $vo['url']; ?>");<?php else: ?>javascript:void(0);<?php endif; ?>" alt="<?php echo $vo['name']; ?>">
                            <img src="<?php echo $vo['image_url']; ?>" width = "100%" height="100%">
                            </a>
                            </li>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <div class = "poster-btn poster-prev-btn" style="left: 50px;"></div>
                        <div class = "poster-btn poster-next-btn" style="right: 50px;"></div>
                    </div>
                </div>
                <div class="moblie btn_link">
                    <a id="weixinAt" href="javascript:void(0)">
                        <img src="__static__/war/img/btn_m_wx.png">
                        <p>关注微信公众号</p>
                        <h1>魔兽小宠物</h1>
                    </a>
                    <a href="javascript:joinQQ();">
                        <img src="__static__/war/img/btn_m_qq.png">
                        <p>官方QQ</p>
                        <h1>一键加入</h1>
                    </a>
                    <a href="javascript:followWB();">
                        <img src="__static__/war/img/btn_m_wb.png">
                        <p>关注微博</p>
                        <h1>互动爆料</h1>
                    </a>
                </div>
                <div class="bottom_tel">
                    <img class="tel_moblie" src="__static__/war/img/tel_1.png?t=123">
                    <div class="serive">客服电话</div>
                    <div class="number"><?php echo $service_phone['value']; ?></div>
                    <img class="starSky" src="__static__/war/img/bottom_bg.png">
                </div>
                <footer class="moblie">
    <img src="__static__/war/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
            </div>
        </div>
    </div>
</div>
<div id="cover">
    <div id="imageQrDiv"><img src="<?php echo $qrcode_wechart['value']; ?>" /></div>
    <img class="close-cover" src="__static__/war/img/close_1.png">
    <div class="cover-text">
        <p>长按二维码保存图片</p>
        <p>关注魔兽小宠物官方公众号</p>
    </div>
</div>
<script type="text/javascript" src="__static__/war/js/jquery.js"></script>
<script type="text/javascript" src="__static__/war/js/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="__static__/war/js/jquery.easings.min.js"></script>
<script type="text/javascript" src="__static__/war/js/scrolloverflow.min.js"></script>
<script type="text/javascript" src="__static__/war/js/jquery.fullpage.js"></script>
<script type="text/javascript" src="__static__/war/dist/owl.carousel.js"></script>
<script type="text/javascript" src="__static__/war/js/jquery.carousel.js"></script>
<script type="text/javascript" src="__static__/war/js/jquery.lang.js"></script>
<script>
	<!--获取屏幕宽度-->
	<!--轮播图变化函数-->
	function carouselChanges(){
	var Mw = $('body').width() - 15;
	var Mh = Mw/1.625;
	var C = $(".caroursel.poster-main.moblie").attr("data-setting");
	$(".moblie.caroursel_box").height(Mh);
	C = JSON.parse(C);
	C.posterWidth = Mw;
	C.height = Mh;
	C.posterHeight = Mh;
	C = JSON.stringify(C);
	$(".caroursel.poster-main.moblie").attr("data-setting",C);
	}
	carouselChanges();
	
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
    
    function openImg(url){
    	var url = url.split('id');
    	url = url[0]+"channel/mobile/id"+url[1];
    	location.href=url;
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
    });
</script>
</body>
</html>