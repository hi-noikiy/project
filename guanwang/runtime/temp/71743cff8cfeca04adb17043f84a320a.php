<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:85:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\news\news_mobile.html";i:1515658037;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\header_mobile.html";i:1515641079;s:82:"F:\wamp\www\project\guanwang\public/../application/pokemon\view\footer_mobile.html";i:1515641079;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>精灵世界VS</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/mui.min.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/applist.css" />
    <link rel="stylesheet" type="text/css" href="__static__/pokemon/css/pmstyle.css" />
    <style>
        .mui-scroll-wrapper{
            top:134px;
        }
    </style>
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
            <a href="javascript:openIndex()">官网首页</a><a href="javascript:void(0)">新闻资讯</a>
        </div>
    </div>
    <div class="main-container">
        <div class="selection" id="sss">
            <?php if(is_array($newsTypeList) || $newsTypeList instanceof \think\Collection || $newsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="selection-title">
                <?php if(($typeId == $vo['id']) or ($typeId == -1 and $key==0)): ?>
                <div class="underline"></div>
                <?php else: ?>
                <div></div>
                <?php endif; ?>
                <a href="javascript:openNews(<?php echo $vo['id']; ?>);" class="option"><?php echo $vo['name']; ?></a>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper padding-fix bar">
            <div class="obj">
                <div id="dataList" class="mui-table-view mui-table-view-chevron">
                </div>
            </div>
        </div>
    </div>
    <div id="up" class="top">
        <img src="__static__/pokemon/img/top.png">
    </div>
    <footer class="moblie">
    <img src="__static__/pokemon/img/logo_btm.png">
    <p><?php echo $copyright['value']; ?></p>
    <p><?php echo $contact_us['value']; ?></p>
</footer>
<script type="text/javascript" src="__static__/pokemon/js/jquery.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/lib/jquery.fullPage.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="__static__/pokemon/js/lib/mui.js"></script>
<script>
    var newsUrl="<?php echo url('News/news'); ?>";
    var detailUrl="<?php echo url('News/detail'); ?>";
    var page=1;
    var num=5;
    var hasMoreData=true;
    var pullUpFlag=false;
    var pullDownFlag=false;
    $(function(){
        loadDatas(page,'up');
    })

    $("#up").click(function(){
        $(".obj").css({
            "transform":"translate3d(0px, 0px, 0px)",
            "transition-duration":"500ms"
        })
    })

    mui.init({
      pullRefresh : {
        container:"#pullrefresh",//下拉刷新容器标识，querySelector能定位的css选择器均可，比如：id、.class等
/*        down : {
          callback :pullfreshDown //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
        },*/
        up : {
          contentrefresh:'拼命加载中...',
          contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
          callback :pullfreshUp //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
        }
      }
    });
    mui('body').on('tap','a',function(){document.location.href=this.href;});
    function pullfreshUp(){
        mui('#pullrefresh').pullRefresh().endPullupToRefresh(!hasMoreData); //参数为true代表没有更多数据了。
        if(pullUpFlag){
            return false;
        }
        pullUpFlag=true;
        if(hasMoreData){
            setTimeout(function() {
                loadDatas(page,'up');
            },1000);
        }
    }

    function pullfreshDown(){
        console.log(pullDownFlag);
        if(pullDownFlag){
            return false;
        }
        pullDownFlag=true;
        setTimeout(function() {
            page=1;
            hasMoreData=true;
            loadDatas(page,'down');
            mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
            mui('#pullrefresh').pullRefresh().refresh(true);
        },1000);
    }

    function loadDatas(pageI,opt){
        var async=true;
        if(opt=='down'){
            async=false;
        }
        $.ajax({
            type : "get",
            url : newsUrl.substr(0,newsUrl.length-9)+"newsAjax/typeId/<?php echo $typeId; ?>/page/"+page+"/num/"+num,
            async:async,
            dataType:'json',
            success : function(datas) {
                var dataListHTML="";
                if(datas.hasNextPage==0){
                    hasMoreData=false;
                }else{
                    page++;
                }
                for (var i = 0; i<datas.newsList.length; i++) {
                    var data=datas.newsList[i];
                    var imgHTML='';
                    var containerStyle='';
                    if(data['image_url']&&data['image_url']!=''){
                       imgHTML='<div class="container-img"><img src="'+data['image_url']+'"></div>'; 
                    }else{
                        containerStyle='style="width:95%;"';
                    }
                    var dataHTML='<a href="javascript:openDetail('+data['id']+');" class="container">'+imgHTML+'<div class="container-text" '+containerStyle+'><div class="text-title">'+data['title']+'</div><div class="text-con">'+data['description']+'</div><div class="text-date clearfix">'+data['create_time'].substr(5,5)+'</div></div></a>';
                    dataListHTML+=dataHTML;
                }
                if(opt=='up'){
                    dataListHTML=$("#dataList").html()+dataListHTML;
                }
                $("#dataList").html(dataListHTML);
                if(opt=='up'){
                    pullUpFlag=false;
                }else if(opt=='down'){
                    pullDownFlag=false;
                }
            }
        });
    }

    function openIndex(){
        location.href="<?php echo url('Mobile/index'); ?>";
    }

    function openNews(id){
        location.href=newsUrl.substr(0,newsUrl.length-5)+"/channel/mobile/typeId/"+id;
    }

    function openDetail(id){
    	alert(detailUrl.substr(0,detailUrl.length-5)+"/channel/mobile/id/"+id)
        location.href=detailUrl.substr(0,detailUrl.length-5)+"/channel/mobile/id/"+id;
        
    }
</script>
</body>
</html>