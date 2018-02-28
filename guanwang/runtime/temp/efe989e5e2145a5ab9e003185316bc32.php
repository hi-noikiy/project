<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:81:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\news\news_us.html";i:1515987277;s:78:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\header_us.html";i:1515987276;s:78:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\footer_us.html";i:1516002709;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elf world VS</title>
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
</head>
<body>
<header id="top">
	<div class="language clearfix">
        <a id="english" class="" href="/pokemon?us"><img src="__static__/index/img/English.png"></a>
        <a id="chinese" class="active" href="/pokemon?cn"><img src="__static__/index/img/chinese.png"></a>
    </div>
    <img class="top_bg" src="__static__/pokemon/img/english/top_bg.png">
    <div class="content clearfix">
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="<?php echo url('Index/index'); ?>">HOME</a>
        </div>
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="<?php echo url('News/news'); ?>">NEWS</a>
        </div>
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="<?php echo url('Activity/activity'); ?>">ACTIVITY</a>
        </div>
        <img id="logo" src="__static__/pokemon/img/english/logo.png">
        <div class="nav">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="<?php echo $fuli_url['value']; ?>">GIFT</a>
        </div>
        <div class="nav" id="community">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="javascript:void(0)">COMMUNITY</a>
        </div>
        <div class="nav" id="contact">
            <div class="nav_line"></div>
            <a class="nav_btn" style="padding-top: 10px;" href="javascript:void(0)">CONTACT</a>
        </div>
    </div>
    <div class="nav_hover community">
        <img class="hover_bg" src="__static__/pokemon/img/english/top_nav_bg.png">
        <div class="content clearfix">
            <div class="hover_block">
                <div class="box">
                    <img class="code" src="/public/static/pokemon/img/english/code.png">
                    <p>scan here to FB page</p>
                </div>
            </div>
            <div class="community_block" style="width: 600px;">
            	<p style="margin-top: 40px;">FB Page: <a href="https://business.facebook.com/LegendsofMonsters/" target="_blank">https://business.facebook.com/LegendsofMonsters/</a></p>
            <!--<?php if(is_array($qq_group) || $qq_group instanceof \think\Collection || $qq_group instanceof \think\Paginator): $i = 0; $__LIST__ = $qq_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <p><?php echo $vo['desc']; ?>：<?php echo $vo['value']; ?></p>
            <?php endforeach; endif; else: echo "" ;endif; ?>-->
            </div>
        </div>
    </div>
    <div class="nav_hover contact">
        <img class="hover_bg" src="__static__/pokemon/img/english/top_nav_bg.png">
        <div class="content clearfix">
            <!--<div class="contact_block">
                <img src="__static__/pokemon/img/english/QQ.png">
                <div class="text_block">
                <?php if(is_array($qq_service) || $qq_service instanceof \think\Collection || $qq_service instanceof \think\Paginator): $i = 0; $__LIST__ = $qq_service;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <p><?php echo $vo['desc']; ?>：<?php echo $vo['value']; ?></p>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>-->
            <div class="contact_block long_fix" style="width: 100%;">
                <img src="__static__/pokemon/img/english/tel_nav.png">
                <div class="text_block">
                    <h1 style="margin-top: 30px;"><?php echo $service_online['desc']; ?>：<?php echo $service_online['value']; ?></h1>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="news_block1">
    <img class="show" src="__static__/pokemon/img/english/news_top.jpg">
    <div class="news_nav">
        <div class="content clearfix news">
            <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <a id="new_<?php echo $vo['id']; ?>" href="javascript:void(0)" class="new_nav_block <?php if(($typeId==$vo['id']) or (($typeId == -1) and ($key == 0))): ?>active<?php endif; ?>">
                <span><?php echo $vo['name']; ?></span>
                <?php if(($typeId==$vo['id']) or (($typeId == -1) and ($key == 0))): ?>
                <img class="selected_fish" src="__static__/pokemon/img/english/selected.png">
                <div class="selected_line"></div>
                <?php endif; ?>
            </a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
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
                    <p><?php echo $news['title']; ?></p><img class="selected_img" src="__static__/pokemon/img/english/pokeball.png">
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
        <a name="prev_btn" href="javascript:void(0)" class="<?php if($hasPrevPage == 1): ?>prev_btn<?php endif; ?>">Previous</a>
        <a name="next_btn" href="javascript:void(0)" class="<?php if($hasNextPage == 1): ?>next_btn<?php endif; ?>">Next</a>
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>
<footer>
    <img src="__static__/pokemon/img/russia/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
    <p>FB Page: <a href="https://business.facebook.com/LegendsofMonsters/" target="_blank">https://business.facebook.com/LegendsofMonsters/</a></p>
</footer>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/top_nav.js"></script>
</body>
<script>
    var page=1;
    var num=5;
    var typeId=<?php echo $newsTypeList[0]['id']; ?>;
    var newsUrl="<?php echo url('News/news'); ?>";
    var detailUrl="<?php echo url('News/detail'); ?>";
    newsUrl=newsUrl.substr(0,newsUrl.length-9);
    var basePath='__static__';
    $(".new_nav_block").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        var idx=$(this).attr("id");
        typeId=idx.substr(4,1);
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
                for (var i = 0; i<datas.newsList.length; i++) {
                    var data=datas.newsList[i];
                    var timeHTML='<div class="time_box">'+data['create_time'].substr(5,5)+'</div>';
                    var dataHTML='<div class="news_list"><div class="content list_line clearfix">'+timeHTML+'<div class="list_content"><a href="javascript:openDetail('+data['id']+')" class="tit"><p>'+data['title']+'</p><img class="selected_img" src="'+basePath+'/pokemon/img/english/pokeball.png"></a><div class="text">'+data['description']+'</div></div></div></div>';
                    dataListHTML+=dataHTML;
                }
                if(datas.hasPrevPage==1){
                    $("#page_new_"+typeId).children().first().addClass("prev_btn");
                }else{
                    $("#page_new_"+typeId).children().first().removeClass();
                }
                if(datas.hasNextPage==1){
                    $("#page_new_"+typeId).children().last().addClass("next_btn");
                }else{
                    $("#page_new_"+typeId).children().last().removeClass();
                }
                $("#list_new_"+typeId).html(dataListHTML);
                if(opt=='prev'){
                    page--;
                }else if(opt=='next'){
                    page++;
                }
            }
        });
    }

    function openDetail(id){
        location.href=detailUrl.substr(0,detailUrl.length-5)+"/id/"+id;
    }
</script>
</html>