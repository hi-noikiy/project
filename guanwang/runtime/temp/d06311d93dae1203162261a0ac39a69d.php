<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:119:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\news\news.html";i:1521678063;s:116:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\header.html";i:1521096084;s:116:"C:\Users\Administrator\AppData\Roaming\HBuilder\userprofiles\offline\game\public/../application/war\view\footer.html";i:1520417140;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>精灵世界VS</title>
    <link rel="stylesheet" type="text/css" href="__static__/war/css/pmstyle.css" />
</head>
<body>
<header id="top">
	<!--<div class="langage">
			<p>CN</p>
			<img src="__static__/war/img/blue_triangle.png"/>
		</div>
		<ul class="langage_ul">
			<a href="/?cn"><li>CN</li></a>
			<a href="/?ru"><li>RU</li></a>
			<a href="/?vn"><li>VN</li></a>
			<a href="/?us"><li style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">EN</li></a>
		</ul>-->
	<!--<div class="language clearfix" style="z-index: 900;">
        <a id="english" class="" href="/?us"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="/?cn"><img src="__static__/index/img/chinese.png"></a>
        <a id="vietnam" class="" href="/?vn"><img src="__static__/index/img/Vietnam.png"></a>
        <a id="russia" class="active" href="/?ru"><img src="__static__/index/img/Russia.png"></a>
    </div>-->
    <img class="top_bg" src="__static__/war/img/top_bg.png">
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
        <img id="logo" src="__static__/war/img/logo.png">
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
        <img class="hover_bg" src="__static__/war/img/top_nav_bg.png">
        <div class="content clearfix">
            <div class="hover_block">
                <a href="<?php echo $baidu_bbs['value']; ?>" class="box">
                    <img class="baidu" src="__static__/war/img/baidu.png">
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
        <img class="hover_bg" src="__static__/war/img/top_nav_bg.png">
        <div class="content clearfix">
            <div class="contact_block">
                <img src="__static__/war/img/QQ.png">
                <div class="text_block">
                <?php if(is_array($qq_service) || $qq_service instanceof \think\Collection || $qq_service instanceof \think\Paginator): $i = 0; $__LIST__ = $qq_service;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <p><?php echo $vo['desc']; ?>：<?php echo $vo['value']; ?></p>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="contact_block long_fix">
                <img src="__static__/war/img/tel_nav.png">
                <div class="text_block">
                    <h1><?php echo $service_phone['desc']; ?>：<?php echo $service_phone['value']; ?></h1>
                    <p><?php echo $service_online['desc']; ?>：<?php echo $service_online['value']; ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="news_block1">
    <img class="show" src="__static__/war/img/news_top.jpg">
    <div class="news_nav">
        <div class="content clearfix news">
        	<a id="new_0" href="javascript:void(0)" class="new_nav_block">
                <span>最新</span>
                <img class="selected_fish" src="__static__/war/img/selected.png">
                <div class="selected_line"></div>
            </a>
            <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <a id="new_<?php echo $vo['id']; ?>" href="javascript:void(0)" class="new_nav_block <?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): ?>active<?php else: endif; ?>">
                <span><?php echo $vo['name']; ?></span>
                <img class="selected_fish" src="__static__/war/img/selected.png">
                <div class="selected_line"></div>
            </a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div id="list_new_0" style="<?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): ?>display: block;<?php else: ?>display: none;<?php endif; ?>" class="list_page">
    <?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($news['type_id'] == $vo['id']): ?>
        <div class="news_list">
        <div class="content list_line clearfix">
            <div class="time_box">
                <?php echo mb_substr($news['create_time'],5,5); ?>
            </div>
            <div class="list_content">
                <a href="javascript:openDetail(<?php echo $news['id']; ?>)" class="tit">
                    <p><?php echo $news['title']; ?></p><img class="selected_img" src="__static__/war/img/pokeball.png">
                </a>
                <a href="javascript:openDetail(<?php echo $news['id']; ?>)" class="tit">
	                <div class="text">
	                    <?php echo $news['description']; ?>
	                </div>
                </a>
            </div>
        </div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>
    <div id="page_new_0" class="page_btn" style="<?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): ?>display: block;<?php else: ?>display: none;<?php endif; ?>">
        <a name="prev_btn" href="javascript:void(0)" class="<?php if($hasPrevPage == 1): ?>prev_btn<?php endif; ?>">上一页</a>
        <span>第<span class="pages">1</span>页</span>
        <a name="next_btn" href="javascript:void(0)" class="<?php if($hasNextPage == 1): ?>next_btn<?php endif; ?>">下一页</a>
    </div>
<?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $k = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
    <div id="list_new_<?php echo $vo['id']; ?>" style="<?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): ?>display: block;<?php else: ?>display: none;<?php endif; ?>" class="list_page">
    <?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;if($news['type_id'] == $vo['id']): ?>
        <div class="news_list">
        <div class="content list_line clearfix">
            <div class="time_box">
                <?php echo mb_substr($news['create_time'],5,5); ?>
            </div>
            <div class="list_content">
                <a href="javascript:openDetail(<?php echo $news['id']; ?>)" class="tit">
                    <p><?php echo $news['title']; ?></p><img class="selected_img" src="__static__/war/img/pokeball.png">
                </a>
                <div class="text">
                    <?php echo $news['description']; ?>
                </div>
            </div>
        </div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>
    <div id="page_new_<?php echo $vo['id']; ?>" class="page_btn" style="<?php if(($typeId==$vo['id']) or (($typeId == -1) and ($k == 1))): ?>display: block;<?php else: ?>display: none;<?php endif; ?>">
        <a name="prev_btn" href="javascript:void(0)" class="<?php if($hasPrevPage == 1): ?>prev_btn<?php endif; ?>">上一页</a>
        <span>第<span class="pages">1</span>页</span>
        <a name="next_btn" href="javascript:void(0)" class="<?php if($hasNextPage == 1): ?>next_btn<?php endif; ?>">下一页</a>
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>
<footer>
    <img src="__static__/war/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
    <div></div>
</footer>
<script type="text/javascript" src="__static__/war/js/jquery.js"></script>
<script type="text/javascript" src="__static__/war/js/top_nav.js"></script>
</body>
<script>
    var page=1;
    var num=5;
    var typeId=0;
    var newsUrl="<?php echo url('News/news'); ?>";
    var detailUrl="<?php echo url('News/detail'); ?>";
    newsUrl=newsUrl.substr(0,newsUrl.length-9);
    var basePath='__static__';
    if(!$('.new_nav_block').hasClass('active')){
    	$('.new_nav_block').eq(0).addClass('active');
    	$('.list_page').eq(0).css('display','block');
    	$('.page_btn').eq(0).css('display','block');
    }
    var idx=$(".new_nav_block.active").attr("id");
        typeId=idx.split("_")[1];
        loadDatas();
        $("#list_"+idx).show().siblings(".list_page").hide();
        $("[id^=page_]").hide();
        $("#page_"+idx).show();
    $(".new_nav_block").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        var idx=$(this).attr("id");
        typeId=idx.split("_")[1];
        page=1;
        loadDatas();
        $("#list_"+idx).show().siblings(".list_page").hide();
        $("[id^=page_]").hide();
        $("#page_"+idx).show();
    })

    $("[name=prev_btn]").click(function(){
        if($(this).attr("class")!=''){
            loadDatas('prev');
        }
    })

    $("[name=next_btn]").click(function(){
        if($(this).attr("class")!=''){
            loadDatas('next');
        }
    })

    function loadDatas(opt){
        var needPage=page;
        if(opt=='prev'){
            needPage--;
        }else if(opt=='next'){
            needPage++;
        }
        $.ajax({
            type : "get",
            url : newsUrl+"newsAjax/typeId/"+typeId+"/page/"+needPage+"/num/"+num,
            dataType:'json',
            success : function(datas) {
                var dataListHTML="";
//              console.log(datas);
                for (var i = 0; i<datas.newsList.length; i++) {
                    var data=datas.newsList[i];
                    var timeHTML='<div class="time_box">'+data['create_time'].substr(5,5)+'</div>';
                    var dataHTML='<div class="news_list"><div class="content list_line clearfix">'+timeHTML+'<div class="list_content"><a href="javascript:openDetail('+data['id']+')" class="tit"><p>'+data['title']+'</p><img class="selected_img" src="'+basePath+'/war/img/pokeball.png"></a><a href="javascript:openDetail(<?php echo $news['id']; ?>)" class="tit"><div class="text">'+data['description']+'</div></a></div></div></div>';
                    dataListHTML+=dataHTML;
                }
                if(datas.hasPrevPage==1){
                    $("#page_new_"+typeId).children().first().addClass("prev_btn");
                }else{
                	$("#page_new_"+typeId).children().first().removeClass();
                }
                if(datas.hasNextPage==1){
                    $("#page_new_"+typeId+" [name=next_btn]").addClass("next_btn");
                }else{
                    $("#page_new_"+typeId+" [name=next_btn]").removeClass();
                }
                $("#list_new_"+typeId).html(dataListHTML);
                if(opt=='prev'){
                    page--;
                }else if(opt=='next'){
                    page++;
                }
                $('.pages').text(page);
            }
        });
    }

    function openDetail(id){
        location.href=detailUrl.substr(0,detailUrl.length-5)+"/id/"+id;
    }
</script>
</html>