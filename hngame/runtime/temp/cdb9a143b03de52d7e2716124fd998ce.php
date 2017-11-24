<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"/data/home/hngame/public/../application/index/view/join/join.html";i:1510763848;s:62:"/data/home/hngame/public/../application/index/view/header.html";i:1510535219;s:62:"/data/home/hngame/public/../application/index/view/footer.html";i:1510764126;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="__static__/index/css/style.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/about_us.css" />
    <link rel="stylesheet" type="text/css" href="__static__/index/css/game.css" />
    <title>海牛游戏</title>
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
        <img src="__static__/index/img/join.png" alt="game">
    </div>
</div>
<div class="content cn" style="display: none;">
    <div class="con_body">
        <?php if(is_array($jobList) || $jobList instanceof \think\Collection || $jobList instanceof \think\Paginator): $i = 0; $__LIST__ = $jobList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="news_border">
            <div class="news_block join_block clearfix">
                <div class="join_tit">
                    <?php echo $vo['name']; ?>
                </div>
                <div class="join_detail">
                    <?php echo $vo['description']; ?>
                </div>
                <a class="apply_btn" href="javascript:applyJob(<?php echo $vo['id']; ?>)">申请该职位</a>
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
                <?php $__FOR_START_1664583505__=1;$__FOR_END_1664583505__=$pageNum;for($i=$__FOR_START_1664583505__;$i <= $__FOR_END_1664583505__;$i+=1){ ?>
                <option <?php if($i==$nowPage): ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="content en" style="display: none;">
    <div class="con_body">
        <?php if(is_array($jobList) || $jobList instanceof \think\Collection || $jobList instanceof \think\Paginator): $i = 0; $__LIST__ = $jobList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="news_border">
            <div class="news_block join_block clearfix">
                <div class="join_tit">
                    <?php echo $vo['name_en']; ?>
                </div>
                <div class="join_detail">
                    <?php echo $vo['description_en']; ?>
                </div>
                <a class="apply_btn" href="javascript:applyJobEn(<?php echo $vo['id']; ?>)">Apply for this job</a>
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
                <?php $__FOR_START_1381418014__=1;$__FOR_END_1381418014__=$pageNum;for($i=$__FOR_START_1381418014__;$i <= $__FOR_END_1381418014__;$i+=1){ ?>
                <option <?php if($i==$nowPage): ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div id="jobInfo" class="cover chn">
    <a class="close" href="javascript:void(0)"><img src="__static__/index/img/close.png"></a>
    <table>
        <tr><td><div>工作地点：</div></td><td name="address"></td></tr>
        <tr><td><div>需求人数：</div></td><td name="needs"></td></tr>
        <tr><td><div>联系人：</div></td><td name="contacts"></td></tr>
        <tr><td><div>联系电话：</div></td><td name="phone"></td></tr>
        <tr><td><div>Email：</div></td><td name="email"></td></tr>
    </table>
</div>
<div id="jobInfoEn" class="cover eng">
    <a class="close" href="javascript:void(0)"><img src="__static__/index/img/close.png"></a>
    <table>
        <tr><td>Address：</td><td name="address"></td></tr>
        <tr><td>Needs：</td><td name="needs"></td></tr>
        <tr><td>Contacts：</td><td name="contacts"></td></tr>
        <tr><td>Phone：</td><td name="phone"></td></tr>
        <tr><td>Email：</td><td name="email"></td></tr>
    </table>
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
<script>
$(".close").click(function(){
    $(this).parent().hide();
});

function applyJob(id){
    setJobInfo(id,'jobInfo');
}

function applyJobEn(id){
    setJobInfo(id,'jobInfoEn');
}

function setJobInfo(id,divId){
    $.ajax({
        type : "get",
        url : "jobInfo?id="+id,
        dataType:'json',
        success : function(value) {
            var address='',need='',contacts='',phone='',email='';
            if(divId=='jobInfoEn'){
                address=value.address_en;
                contacts=value.contacts_en;
            }else{
                address=value.address;
                contacts=value.contacts;
            }
            need=value.need;
            phone=value.phone;
            email=value.email;
            $("#"+divId+" [name='address']").text(address);
            $("#"+divId+" [name='needs']").text(need);
            $("#"+divId+" [name='contacts']").text(contacts);
            $("#"+divId+" [name='phone']").text(phone);
            $("#"+divId+" [name='email']").text(email);
            $("#"+divId).show();
        }
    });
}

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