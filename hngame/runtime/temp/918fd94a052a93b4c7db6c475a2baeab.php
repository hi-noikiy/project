<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"/data/home/hngame/public/../application/index/view/news/news.html";i:1510477808;s:62:"/data/home/hngame/public/../application/index/view/header.html";i:1510535219;s:62:"/data/home/hngame/public/../application/index/view/footer.html";i:1510764126;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="__static__/index/css/style.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/about_us.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/game.css" />
    <title>Title</title>
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
<div class="photo_show">
    <div class="pic">
        <img src="__static__/index/img/news.png" alt="game">
    </div>
</div>
<div class="content cn" style="display: none;">
    <div class="con_body">
        <?php if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="news_border">
            <div class="news_block clearfix">
                <div class="news_pic">
                    <img src="<?php echo $vo['image_url']; ?>">
                </div>
                <div class="news_con">
                    <div class="text_tit clearfix">
                        <?php echo $vo['title']; ?>
                        <span class="time"><?php echo $vo['create_time']; ?></span>
                    </div>
                    <div class="news_text">
                        <?php echo $vo['description']; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="border_bottom"></div>
        <div class="prev">
            <span>第<?php echo $nowPage; ?>页</span>
            <?php if($hasPrevPage == 1): ?>
            <a href="javascript:turnPage(<?php echo $prevPage; ?>);"> 上一页 </a>
            <?php endif; if($hasNextPage == 1): ?>
            <a href="javascript:turnPage(<?php echo $nextPage; ?>);"> 下一页 </a>
            <?php endif; ?>
             全部: <span> <?php echo $pageNum; ?> </span> 页,记录: <span> <?php echo $count; ?> </span>,
             跳转到:
            <select name="pageSelect">
                <?php $__FOR_START_1024224606__=1;$__FOR_END_1024224606__=$pageNum;for($i=$__FOR_START_1024224606__;$i <= $__FOR_END_1024224606__;$i+=1){ ?>
                <option <?php if($i==$nowPage): ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="content en" style="display: none;">
    <div class="con_body">
        <?php if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="news_border">
            <div class="news_block clearfix">
                <div class="news_pic">
                    <img src="<?php echo $vo['image_url']; ?>">
                </div>
                <div class="news_con">
                    <div class="text_tit clearfix">
                        <?php echo $vo['title_en']; ?>
                        <span class="time"><?php echo $vo['create_time']; ?></span>
                    </div>
                    <div class="news_text">
                        <?php echo $vo['description_en']; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="border_bottom"></div>
        <div class="prev">
            <span>Page <?php echo $nowPage; ?></span>
            <?php if($hasPrevPage == 1): ?>
            <a href="javascript:turnPage(<?php echo $prevPage; ?>);"> Previous </a>
            <?php endif; if($hasNextPage == 1): ?>
            <a href="javascript:turnPage(<?php echo $nextPage; ?>);"> Next </a>
            <?php endif; ?>
             Total: <span> <?php echo $pageNum; ?> </span> page,Record: <span> <?php echo $count; ?> </span>,
             GOTO:
            <select name="pageSelect">
                <?php $__FOR_START_1643962212__=1;$__FOR_END_1643962212__=$pageNum;for($i=$__FOR_START_1643962212__;$i <= $__FOR_END_1643962212__;$i+=1){ ?>
                <option <?php if($i==$nowPage): ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
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
<script type="text/javascript" src="__static__/index/js/language.js"></script>
<script type="text/javascript">
var num=5;
function turnPage(page){
    location.href="news?page="+page+"&num="+num;
}

$("select[name='pageSelect']").change(function(){
    var page=$(this).val();
    if(page!="<?php echo $nowPage; ?>"){
        turnPage(page);
    }
});
</script>
</body>
</html>