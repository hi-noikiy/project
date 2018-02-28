<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\news\detail_mobile_us.html";i:1516002709;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\header_mobile.html";i:1515641079;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\footer_mobile.html";i:1515641079;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elf world VS</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
</head>
<body>
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
<div class="bread">
    <div class="moblie_content moblie_nav">
        <a href="javascript:openIndex()">Website home page</a><a href="javascript:openNews()">News and information</a><a href="javascript:void(0)">Body</a>
    </div>
</div>
<div style="width: 100%;overflow: hidden;">
<div class="detail_tit_m">
    <div class="moblie_content">
        <div class="tit">
            <?php echo $data['title']; ?>
        </div>
        <div class="mess clearfix">
            <div class="l">author：<span><?php echo $data['author']; ?></span></div>
            <div class="r"><?php echo $data['create_time']; ?></div>
        </div>
    </div>
</div>
<div class="detail_content_m" style="width: 100%;overflow: hidden;">
    <?php echo htmlspecialchars_decode($data['content']); ?>
    <div class="share_heart">
        <a href="javascript:void(0)" class="heart" id="heart">
            <div class="beat"></div> <span><?php echo $data['thumb_up']; ?></span>
        </a>
        <a href="javascript:void(0)" class="heart share" id="share">
            <div class="share_icon"></div> <span>Share</span>
        </a>
    </div>
</div>
<div class="moblie_content moblie btn_link">
    <a id="weixinAt" href="javascript:void(0)">
        <img src="__static__/pokemon/img/english/btn_m_wx.png">
        <p style="color: #000;">关注微信公众号</p>
        <h1 style="color: #000;">口袋妖怪VS游戏</h1>
    </a>
    <a href="javascript:joinQQ();">
        <img src="__static__/pokemon/img/english/btn_m_qq.png">
        <p style="color: #000;">官方QQ</p>
        <h1 style="color: #000;">一键加入</h1>
    </a>
    <a href="javascript:followWB();">
        <img src="__static__/pokemon/img/english/btn_m_wb.png">
        <p style="color: #000;">关注微博</p>
        <h1 style="color: #000;">互动爆料</h1>
    </a>
</div>
<div class="bottom_tel">
    <img class="tel_moblie" src="__static__/pokemon/img/english/tel_1.png?t=123">
    <div class="serive">Telephone</div>
    <div class="number">0591-87688008</div>
    <img class="starSky" src="__static__/pokemon/img/english/bottom_bg.png">
</div>
</div>
<div id="cover">
    <div id="imageQrDiv"><img src="<?php echo $qrcode_wechart['value']; ?>" /></div>
    <img class="close-cover" src="__static__/pokemon/img/english/close_1.png">
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
<script type="text/javascript" src="__static__/pokemon/plugin/soshm-master/dist/soshm.js"></script>
</body>
<script>
    var newsUrl="<?php echo url('News'); ?>";
    $("#heart").click(function(){
        $(this).find(".beat").toggleClass("active");
        thumbUp($(this).find(".beat").hasClass("active"));
    })

    function openIndex(){
        location.href="<?php echo url('Mobile/index'); ?>";
    }

    function openNews(){
        location.href=newsUrl.substr(0,newsUrl.length-5)+"/channel/mobile";
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
                    $("#heart").find("span").text(parseInt(upNum)+1);
                }else{
                    $("#heart").find("span").text(parseInt(upNum)-1);
                }
            }
        })
    }

    $("#share").click(function(){
        soshm.popIn({
          title: '<?php echo $data['title']; ?>',
          sites: ['weixin', 'weixintimeline', 'weibo', 'qzone', 'qq']
        });
    });
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
    $("#weixinAt").click(function(){
        $("#cover").show();
    });
    $(".close-cover").click(function(){
        $("#cover").hide();
    })
</script>
</html>