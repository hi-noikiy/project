<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\news\detail.html";i:1515641079;s:75:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\header.html";i:1515748487;s:75:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\footer.html";i:1515641079;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>精灵世界VS</title>
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
    <style type="text/css">
    .bd_weixin_popup{position: fixed;left: 50%;padding:15px;margin-left: -150px;top: 50%;margin-top: -150px;z-index: 1000;width:280px !important;height:330px !important;background:#fff;border:solid 1px #d8d8d8;z-index:11001;font-size:16px;display: none;}.bd_weixin_popup .bd_weixin_popup_head{font-size:16px;font-weight:bold;text-align:left;line-height:20px;height:20px;position:relative;color:#000}.bd_weixin_popup .bd_weixin_popup_head .bd_weixin_popup_close{width:16px;height:16px;position:absolute;right:0;top:0;color:#999;text-decoration:none;font-size:16px}.bd_weixin_popup .bd_weixin_popup_head .bd_weixin_popup_close:hover{text-decoration:none}.bd_weixin_popup .bd_weixin_popup_main{padding:30px 40px;min-height:150px;_height:150px}.bd_weixin_popup .bd_weixin_popup_foot{font-size:14px;text-align:left;line-height:22px;color:#666} 
    </style>
</head>
<body>
<header id="top">
	<div class="language clearfix">
        <a id="english" class="" href="/pokemon?us"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="/pokemon?cn"><img src="__static__/index/img/chinese.png"></a>
    </div>
    <img class="top_bg" src="__static__/pokemon/img/top_bg.png">
    <div class="content clearfix">
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
<div class="detail_top">
    <img src="__static__/pokemon/img/detail_top.jpg">
</div>
<div class="detail_tit">
    <div class="content">
        <a class="arrow_back" href="javascript:history.go(-1)"></a>
        <div class="tit"><?php echo $data['title']; ?></div>
        <p>
            <span>作者：<?php echo $data['author']; ?></span>
            <span><?php echo $data['create_time']; ?></span>
            <span>分享到：
                <a href="javascript:openQrcodeDiv();"><img src="__static__/pokemon/img/icon_weixin.png"></a>
                <a href="javascript:openShare('kj');"><img src="__static__/pokemon/img/icon_ss.png"></a>
                <a href="javascript:openShare('wb');"><img src="__static__/pokemon/img/icon_weibo.png"></a>
                <a href="javascript:openShare('qq');"><img src="__static__/pokemon/img/icon_qq.png"></a>
            </span>
        </p>
    </div>
</div>
<div class="content detail_txt">
    <?php echo htmlspecialchars_decode($data['content']); ?>
    <a href="javascript:void(0)" class="heart">
        <div class="beat"></div> <span><?php echo $data['thumb_up']; ?></span>
    </a>
    <a class="back_top" href="#top"><img src="__static__/pokemon/img/top_btn.png"></a>
</div>
<div id="weixinQrcodeDiv" class="bd_weixin_popup">
    <div class="bd_weixin_popup_head">
        <span>分享到微信朋友圈</span><a href="javascript:closeQrcodeDiv();" class="bd_weixin_popup_close">×</a>
    </div>
    <div id="bdshare_weixin_qrcode_dialog_qr" class="bd_weixin_popup_main">
        <div id="weixin_qrcode"></div>
    </div>
    <div class="bd_weixin_popup_foot">
        打开微信，点击底部的“发现”，<br>使用“扫一扫”即可将网页分享至朋友圈。
    </div>
</div>
<footer>
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/top_nav.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/jquery.qrcode.min.js"></script>
<script>
    var shareUrl="http://fhweb.u776.com:88/public/index.php/pokemon/news/detail/id/"+<?php echo $data['id']; ?>;
    var newsUrl="<?php echo url('News'); ?>";
    jQuery('#weixin_qrcode').qrcode({width:200,height: 200,text: shareUrl});

    $(".heart").click(function(){
        $(this).find(".beat").toggleClass("active");
        thumbUp($(this).find(".beat").hasClass("active"));
    })

    $(window).scroll(function(){
        if($(this).scrollTop()>400){
            $(".back_top").css({"position":"fixed","bottom":"255px","top":"auto"})
        }else{
            $(".back_top").css({"position":"absolute","bottom":"auto","top":"400px"})
        }
    })
    function openQrcodeDiv(){
        $("#weixinQrcodeDiv").show();
    }

    function closeQrcodeDiv(){
        $("#weixinQrcodeDiv").hide();
    }

    function thumbUp(isUp){
        var thumbUp=0;
        if(isUp){
            thumbUp=1;
        }
        $.ajax({
            type : "post",
            url : newsUrl.substr(0,newsUrl.length-10)+"/thumbUp/id/<?php echo $data['id']; ?>/thumbUp/"+thumbUp,
            dataType:'json',
            success : function(datas) {
                var upNum=$(".heart").find("span").text();
                if(isUp){
                    $(".heart").find("span").text(parseInt(upNum)+1);
                }else{
                    $(".heart").find("span").text(parseInt(upNum)-1);
                }
            }
        })
    }

    function openShare(type){
        if(type=='kj'){
            shareToQzone();
        }else if(type=='wb'){
            shareToSinaWB();
        }else if(type=='qq'){
            shareToQQ();
        }
    }

    //分享到新浪微博     
    function shareToSinaWB(){  
        var _shareUrl ='http://v.t.sina.com.cn/share/share.php?&appkey=895033136';    //真实的appkey，必选参数   
        _shareUrl +='&url='+ encodeURIComponent(shareUrl);     //参数url设置分享的内容链接|默认当前页location，可选参数  
        _shareUrl +='&title=' + encodeURIComponent('<?php echo $data['title']; ?>');    //参数title设置分享的标题|默认当前页标题，可选参数  
       //_shareUrl +='&pic='+ encodeURIComponent('http://fhweb.u776.com:88/public/static/index/img/LOGO_color.png');  //参数pic设置图片链接|默认为空，可选参数 
        _shareUrl +='&content=' + 'utf-8';   //参数content设置页面编码gb2312|utf-8，可选参数    
       window.open(_shareUrl);  
    }  
      
      
    //分享到QQ空间  
    function shareToQzone(){  
        var _shareUrl ='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';  
        _shareUrl += 'url=' +encodeURIComponent(shareUrl);   //参数url设置分享的内容链接|默认当前页location  
        _shareUrl +='&showcount=' + 1;      //参数showcount是否显示分享总数,显示：'1'，不显示：'0'，默认不显示  
        //_shareUrl +='&desc=' + encodeURIComponent('分享的描述111');   //参数desc设置分享的描述，可选参数  
        _shareUrl +='&summary=' + encodeURIComponent('<?php echo $data['description']; ?>');   //参数summary设置分享摘要，可选参数  
        _shareUrl +='&title=' + encodeURIComponent('<?php echo $data['title']; ?>');    //参数title设置分享标题，可选参数  
        _shareUrl +='&site=' + encodeURIComponent('海牛游戏');   //参数site设置分享来源，可选参数  
        //_shareUrl += '&pics='+ encodeURIComponent('http://fhweb.u776.com:88/public/static/index/img/LOGO_color.png');   //参数pics设置分享图片的路径，多张图片以＂|＂隔开，可选参数 
       window.open(_shareUrl);
    }  
      
      
    //分享到QQ  
    function shareToQQ(){  
        var _shareUrl ='http://connect.qq.com/widget/shareqq/index.html?';  
        _shareUrl +='url=' + encodeURIComponent(shareUrl);  //分享的链接
        _shareUrl +='&title=' + encodeURIComponent('<?php echo $data['title']; ?>');    //参数title设置分享标题，可选参数    
        _shareUrl +='&summary=' + encodeURIComponent('<?php echo $data['description']; ?>');   //参数summary设置分享摘要，可选参数 
        _shareUrl +='&site=' + encodeURIComponent('海牛游戏');   //参数site设置分享来源，可选参数  
        //_shareUrl +='&pics=' + encodeURIComponent('http://fhweb.u776.com:88/public/static/index/img/LOGO_color.png');    //分享的图片  
       window.open(_shareUrl);
    }  
</script>
</body>
</html>