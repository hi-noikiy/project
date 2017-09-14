<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google" value="notranslate" />
<?php if ((empty($ns_iscn) ? ('0') : ($ns_iscn)) == '1') { ?>
<meta property="qc:admins" content="15617710013636" />
<?php } else { ?>
<meta property="qc:admins" content="1252251640611636375" />
<?php } ?>
<meta property="wb:webmaster" content="abb686998c49b59c" />
<meta http-equiv="Window-target" content="_top">
<meta name="author" content="xhb">
<meta name="keywords" content="美女视频 美女直播 视频交友 真人秀场">
<meta name="description" content="福建第一人气帅哥美女主播娱乐互动平台！福建最大的真人互动视频直播社区。在线秀场直播间，支持数万人同时在线视频聊天、视频交友、在线K歌跳舞。海量美女主播，每天劲歌热舞，明星歌手，精彩视频，<?php echo $webType['name']; ?>一网打尽。">
<meta name="robots" content="index">
<link rel="icon" href="<?php echo $this->url->getStatic('web/cssimg/91ns/91ns.ico.png'); ?>" mce_href="<?php echo $this->url->getStatic('web/cssimg/91ns/91ns.ico.png'); ?>" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo $this->url->getStatic('web/cssimg/91ns/91ns.ico.png'); ?>" mce_href="<?php echo $this->url->getStatic('web/cssimg/91ns/91ns.ico.png'); ?>" type="image/x-icon">
    <title>91NS—美女视频</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/base.css"); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/icon/style-icon.css"); ?>"/>

<script type="text/javascript">
    var nsConfig = {
        userUid:'<?php echo (empty($ns_userUid) ? (0) : ($ns_userUid)); ?>',
        pre:'<?php echo $this->url->getStatic(''); ?>',
        iscn:'<?php echo (empty($ns_iscn) ? (0) : ($ns_iscn)); ?>',
        ns_source_login:'<?php echo (empty($ns_source_login) ? (0) : ($ns_source_login)); ?>',
        sourceType:'<?php echo (empty($ns_source) ? (0) : ($ns_source)); ?>',
        channelType:"<?php echo $webType['channelType']; ?>",
        domain:'<?php echo $webType['domain']; ?>',
        name:'<?php echo $webType['name']; ?>',
        logoURL:'<?php echo $this->url->getStatic($webType['logoURL']); ?>',
        roomLogoURL:'<?php echo $this->url->getStatic($webType['roomLogoURL']); ?>',
        roomLoadingURL:'<?php echo $this->url->getStatic($webType['roomLoadingURL']); ?>',
        jsURL:'<?php echo $this->url->getStatic($jsURL); ?>',
        cssURL:'<?php echo $this->url->getStatic($cssURL); ?>',
        GMQQ:['<?php echo $GMQQ[0]; ?>', '<?php echo $GMQQ[1]; ?>', '<?php echo $GMQQ[2]; ?>']
    };
    function requireInit(){
        require.config({
            baseUrl:nsConfig.pre + '<?php echo $jsURL; ?>',
            urlArgs:1,
            paths: {
                jquery:     'tool/jquery-1.11.3.min',
                JSON:       'tool/json2',
                md5:        'tool/md5',
                com:        'tool/com',
                utils:      'tool/utils',
                rankModule: 'module/rank',
                swfobject:  'tool/swfobject'
            }
        });
    };
</script>


    <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/index.css"); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/pagination2.css"); ?>" />
    <script type="text/javascript">
    var SWF_URL = "<?php echo (empty($swfUrl) ? ('web/swf') : ($swfUrl)); ?>";
    </script>
</head>
<body>
<div class="wrap">
    <div class="wrapHd w_1200 clearfix">
	<h1 class="logo">
		<a href="/" title="91NS">
            <?php if ($webType['channelType'] != 2) { ?>
			<img alt="91NS" src="<?php echo $this->url->getStatic('web/cssimg2/logo.png'); ?>">
            <?php } else { ?>
            <img alt="秀吧" src="<?php echo $this->url->getStatic('web/cssimg/91ns/douzilogo.png'); ?>" style="width: 118px;">
            <?php } ?>
        </a>
	</h1>
	<div class="nav">
		<a href="/" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'index') { ?>cur<?php } ?>">首页</a>
		<a href="/rank" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'rank') { ?>cur<?php } ?>">排行榜</a>
		<a href="/family" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'family') { ?>cur<?php } ?>">家族</a>
		<?php if ($ns_userUid > 0) { ?>
		<a href="/charging" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'charging') { ?>cur<?php } ?>">
			充值
			<?php if ((empty($ns_userType) ? (0) : ($ns_userType)) == 3) { ?>
			<img class="douzi-change" src="<?php echo $this->url->getStatic('web/cssimg/91ns/exchange.gif'); ?>">
			<?php } ?>
		</a>
		<?php } else { ?>
		<a onclick="userLogin();" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'charging') { ?>cur<?php } ?>">充值</a>
		<?php } ?>

		<a href="/shop" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'shop') { ?>cur<?php } ?>">商城</a>
		<a href="/download" class="<?php if ((empty($ns_active) ? ('nonePage') : ($ns_active)) == 'download') { ?>cur<?php } ?>">APP下载</a>
	</div>
	<div class="right">
		<?php if ((empty($ns_active) ? ('none') : ($ns_active)) == 'index') { ?>
			<?php if ($ns_userUid > 0) { ?>
                <?php if ((empty($isSignAnchor) ? (false) : ($isSignAnchor))) { ?>
                        <a href="/<?php echo $ns_userUid; ?>" target="_blank" class="sprite-index IWantPlay">我要直播</a>
                <?php } else { ?>
                        <a href="/transition" target="_blank" class="sprite-index IWantPlay">申请入驻</a>
                <?php } ?>
			<?php } else { ?>
				<a class="sprite-index IWantPlay" onclick="userLogin();">申请入驻</a>
			<?php } ?>
		<?php } else { ?>
			<?php if ($ns_userUid > 0) { ?>
                <?php if ((empty($isSignAnchor) ? (false) : ($isSignAnchor))) { ?>
                <a href="/<?php echo $ns_userUid; ?>" target="_blank" class="sprite-index IWantPlay">我要直播</a>
                <?php } else { ?>
                <a href="/transition" target="_blank" class="sprite-index IWantPlay">申请入驻</a>
                <?php } ?>
				<div class="uInfoBox">
                    <a href="/personal"><img src="" nsdata="userAvatar"></a>
					<div class="moreInfo">
						<div class="info-base clearfix">
							<img src="" nsdata="userAvatar">
							<div class="right">
								<div class="names">
									<span class="nickName" nsdata="userNickName"></span>
									<!-- <i></i> -->
								</div>
								<div class="userid">
									(<span class="userUID" nsdata="userUID"></span>)
									<a href="/charging">充值</a>
								</div>
							</div>
						</div>
						<div class="info-cash clearfix">
							<i class="sprite-concern coin" title="聊币"></i>
							<span nsdata="cash"></span>
							<i class="sprite-concern integral" title="积分"></i>
							<span nsdata="integral"></span>
							<i class="sprite-concern beans" title="聊豆"></i>
							<span nsdata="coin"></span>
						</div>
						<div class="info-control clearfix">
							<a href="/personal" class="goCenter pull-left">进入个人中心</a>
							<?php if ((empty($isSignAnchor) ? (false) : ($isSignAnchor))) { ?>
							<span style="margin-left:4px;color: #D9D9D9;">|</span>
			                <a class="myspace" href="/home?uid=<?php echo (empty($ns_userUid) ? (0) : ($ns_userUid)); ?>">我的空间</a>
			                <?php } ?>
							<a href="/user/loginout" class="exit pull-right">退出</a>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<a class="sprite-index IWantPlay" onclick="userLogin();">申请入驻</a>
				<i class="showWin register" id="reg_home">注册</i>
				<i class="hr"></i>
				<i class="showWin login" id="login_home">登录</i>
			<?php } ?>
		<?php } ?>
		<div class="searchC">
			<input id="seAnInput" type="text" value="昵称/房号" isSeAnInput="true">
			<div class="rBtn" isSeAnInput="true"></div>
			<div id="seAnRes" class="shRes">
				<div class="shRoom"></div>
				<div class="hr"></div>
				<div class="shAbout"></div>
			</div>
		</div>
	</div>
</div>

    <div class="theBanner" id="carouselHeader">
        <div class="theBannerLogin">
            <!--unloginBox 登录前-->
            <div id="unloginBox" class="unloginBox" style="display:none;">
                <!-- <div class="err" style="display:none;"><span>用户名不能为空</span></div> -->
                <ul>
                    <li>
                        <label class="label_username" jsShowType="normalLogin"></label>
                        <label class="label_tel" jsShowType="telLogin"></label>
                        <span defaultdisplay="default" class="inp_placeholder" loginShowTip>用户名</span>
                        <input type="text" class="inp_txt" id="login_username"></li>
                    <li>
                        <label class="label_password"></label>
                        <span defaultdisplay="default" class="inp_placeholder" loginShowTipPWD>密码</span>
                        <input type="password" class="inp_txt" id="login_password">
                        <a href="/forgetpwd?ul=1" class="forgetPassword" jsShowType="normalLogin">
                            <i>忘记密码</i>
                        </a>
                        <i class="getTelCode" id="indexLoginGetCode" jsShowType="telLogin">获取验证码<i></i></i>
                    </li>
                </ul>
                <div class="p_rem">
                    <?php if ($webType['channelType'] != 2) { ?>
                    <label class="login_save clearfix" id="login_changeType" onselectstart="return false;">
                        <i class="sprite-index telValidIco"></i>
                        <span jsShowType="normalLogin">手机登录</span>
                        <span jsShowType="telLogin">返回普通登录</span>
                    </label>
                    <?php } ?>
                    <a class="login_reg" id="reg_home">注册用户</a>
                </div>
                <span class="login_btn" id="userLogin">登录</span>
                <?php if ($webType['channelType'] != 2) { ?>
                <p><a class="sprite-index_third thirdWeixin" onclick="thirdLogin('weixin');return false;"></a><a class="sprite-index_third thirdQQ" onclick="thirdLogin('qqdenglu');return false;"></a><a class="sprite-index_third thirdWeibo" onclick="thirdLogin('sinaweibo');return false;"></a></p>
                <?php } ?>
            </div>
            <!--loginedBox 登录后-->
            <div class="loginedBox" id="loginedBox" style="display:none;">
                <div id="index_userInfo" class="user_info_div">
                    <div class="user_pic">
                        <img src="" nsdata="userAvatar">
                    </div>
                    <div class="user_info">
                        <p class="user_data clearfix"><span class="userNickName" nsdata="userNickName"></span><i class="line"></i><span nsdata="userUID"></span><!-- <a class="user_edit"></a> --></p>
                        <p class="user_coin">
                            <span>聊币:</span>
                            <span class="datas" nsdata="cash">0</span>
                            <span>积分:</span>
                            <span nsdata="integral">0</span>
                            <span>聊豆:</span>
                            <span nsdata="coin">0</span>
                        </p>
                    </div>
                    <div class="user_nav_list" id="index_centerContent" style="display:none;">
                        <a target="_blank" href="/personal/info">个人资料</a>
                        <a target="_blank" href="/personal/concern">我的关注</a>
                        <!--<a target="_blank" href="/personal/myfans">我的粉丝</a>-->
                        <i class="line"></i>
                        <a target="_blank" href="/personal/props">我的道具</a>
                        <a target="_blank" href="/personal/mybill">我的账单</a>
                        <i class="line"></i>
                        <a target="_blank" href="/personal/savecenter">安全中心</a>
                        <a href="/user/loginout">退出登录</a>
                        <i class="bottom"></i>
                    </div>
                </div>
                <p class="user_nav"><a class="user_msg" href="/message" target="_blank">消息</a><a class="user_center" id="index_userCenter">用户中心</a><a class="user_pay" href="/charging" target="_blank">充值</a></p>
                <?php if ((empty($isSignAnchor) ? (false) : ($isSignAnchor))) { ?>
                <a class="sprite-btn-space myspace" target="_blank" href="/home?uid=<?php echo (empty($ns_userUid) ? (0) : ($ns_userUid)); ?>">我的空间</a>
                <?php } ?>
            </div>
        </div>
        <div class="theBannerCont" id="carouselBody">
            <div class="control left" jsdata="left">
                <i class="bgs"></i>
                <i class="sprite-index arrow2Left"></i>
            </div>
            <div class="control right" jsdata="right">
                <i class="bgs"></i>
                <i class="sprite-index arrow2Right"></i>
            </div>
            <?php foreach ($bannerList as $i) { ?>
            <ul style="background-color:<?php echo $i['backgroundcolor']; ?>">
                <li><a href="<?php echo $i['extracontent']; ?>" target="_blank"><img src="<?php echo $i['bannerurl']; ?>"></a></li>
            </ul>
            <?php } ?>
            <ul style="background-color:<?php echo $bannerList[0]['backgroundcolor']; ?>">
                <li><a href="<?php echo $bannerList[0]['extracontent']; ?>" target="_blank"><img src="<?php echo $bannerList[0]['bannerurl']; ?>"></a></li>
            </ul>
        </div>
        <div class="theBannerCont_control" id="carouselFooter">
            <p class="clearfix">
                <?php foreach (range(0, ($this->length($bannerList) - 1)) as $i) { ?>
                <a <?php if ($i == 0) { ?>class="cur"<?php } ?> data="<?php echo $i; ?>"></a>
                <?php } ?>
            </p>
        </div>
    </div>
    <div class="wrapBd">
        <div class="w_1200">
            <div class="wrapCont">
                <div class="Hot_V_tit"><h3>推荐主播</h3><span>RECOMMENDED HOSTESS</span><div class="line"></div></div>
                <div id="roomListContent" class="Hot_V_list clearfix">
                    <div class="js-data-demo-anchorItem" style="display:none;"><li class="m-item"><a href="#" target="_blank" class="i-user" title=""><img src="" class="i-avatar" alt="" title=""><span class="i-tags index-roomlist-1 _status"><i class="txt">直播</i></span><i class="index-roomlist-1 _play ico-play"></i><div class="i-bg"></div><div class="i-info2"><i class="index-roomlist-1 _number"></i><span class="number">0</span><span class="i-city"></span></div></a><div class="i-info"><div class="i-info1"><div class="i-name"><a href="#" target="_blank" title="" class="name"></a></div><span class="i-actor sprite-zb_level"></span><i class="sprite-family_level"></i></div></div></li></div>
                    <ul id="roomTheOne" class="clearfix roomTheOne ">
                        <li id="roomlist-0">
                            <a href="#" target="_blank" class="i-user">
                                <div id="roomTheOneSwf"></div>
                            </a>
                            <div class="i-info">
                                <div class="i-info1 clearfix">
                                    <div class="i-name">
                                        <a href="#" target="_blank" title="" class="name"></a>
                                    </div>
                                    <i class="sprite-family_level"></i>
                                    <span class="i-actor sprite-zb_level"></span>
                                    <span class="number"></span>
                                    <i class="index-roomlist-1 _number"></i>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul id="roomRecommendList1" class="clearfix anchorsRoomLists roomRecommendList1"></ul>
                    <ul id="roomRecommendList2" class="clearfix anchorsRoomLists roomRecommendList2"></ul>
                    <div class="Hot_V_tit"><h3>热门主播</h3><span>POPULAR ANCHIR</span><div class="line"></div></div>
                    <ul id="roomList" class="clearfix anchorsRoomLists roomList"></ul>
                </div>
                <div class="addMore"><a id="addMore">点击看看更多主播</a><i class="animate-load"></i></div>
            </div>
            <div class="wrapAside">
                <div class="R_cont R_cont_act">
                    <div class="R_cont_hd"><h2>活动进行中</h2></div>
                    <div class="R_cont_more"><a href="/activities" target="_blank">更多+</a></div>
                    <div class="R_cont_bd">
                        <div class="RCAct_pic" id="activitiesList">
                            <div class="control left" jsdata="left">
                                <i class="bgs"></i>
                                <i class="sprite-index arrow1Left"></i>
                            </div>
                            <div class="control right" jsdata="right">
                                <i class="bgs"></i>
                                <i class="sprite-index arrow1Right"></i>
                            </div>
                            <ul class="clearfix">
                            </ul>
                        </div>
                        <div class="RCAct_control" id="activitiesControl"></div>
                    </div>
                </div>
                <div class="R_cont R_cont_rank R_cont_star">
    <div class="R_cont_hd">
        <h2>明星榜</h2>
        <p class="RCB_tab" nsdata="rankUpdate" nstype="anchor">
            <a class="cur" nsdate="day">日</a><em></em>
            <a nsdate="week">周</a><em></em>
            <a nsdate="month">月</a>
        </p>
    </div>
    <div class="R_cont_bd">
        <ul id="rank-anchor"></ul>
    </div>
</div>
<div class="R_cont R_cont_rank R_cont_rich">
    <div class="R_cont_hd">
        <h2>富豪榜</h2>
        <p class="RCB_tab" nsdata="rankUpdate" nstype="richer">
            <a class="cur" nsdate="day">日</a><em></em>
            <a nsdate="week">周</a><em></em>
            <a nsdate="month">月</a>
        </p>
    </div>
    <div class="R_cont_bd">
        <ul id="rank-richer"></ul>
    </div>
</div>
<div class="R_cont R_cont_rank R_cont_hot">
    <div class="R_cont_hd">
        <h2>人气榜</h2>
        <p class="RCB_tab" nsdata="rankUpdate" nstype="fans">
            <a class="cur" nsdate="day">日</a><em></em>
            <a nsdate="week">周</a><em></em>
            <a nsdate="month">月</a>
        </p>
    </div>
    <div class="R_cont_bd">
        <ul id="rank-fans"></ul>
    </div>
</div>
                <!-- <div class="R_cont R_cont_news">
                    <div class="R_cont_hd"><h2>公告帮助</h2></div>
                    <div class="R_cont_bd">
                        <ul id="noticeList">
                        </ul>
                        <div class="row-fluid pageType1">
                            <div id="pagination" class="pagination alternate pull-right"></div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
<div class="right_fixed clearfix">
    <span class="telIco"><i></i>400-999-0591</span>
    <?php if ($webType['channelType'] != 2) { ?>
    <span class="right_fixed-head">
        <div class="fixed-head-ns">91女神手机端</div>
        <div class="fixed-head-QRcode">
            <img src="http://cdn.91ns.com/web/cssimg/91ns/QRcode.png">
        </div>
    </span>
    <?php } ?>
    <div class="Customer">
        <div class="_Customer">客服
            <ul class="CustomerList">
                <li>
                    <a class="a_qq off" href="tencent://message/?uin=<?php echo $GMQQ[0]; ?>&Menu=yes&Site=91ns&Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a595b1714f9d45"><span>秀场客服</span></a>
                </li>
                <li style="border-top: 0px;border-bottom: 0px;">
                    <a class="a_qq" href="tencent://message/?uin=<?php echo $GMQQ[1]; ?>&Menu=yes&Site=91ns&Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a595b1714f9d45"><span>秀场客服</span></a>
                </li>
                <li>
                    <a class="a_qq" href="tencent://message/?uin=<?php echo $GMQQ[2]; ?>&Menu=yes&Site=91ns&Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a595b1714f9d45"><span>主播招募</span></a>
                </li>
            </ul>
        </div>
        </div>
    <div class="right_fixed-bottom clearfix">
         <div class="help pull-left">
            <a class="a_help" href="/help/helpanchor" target="_blank"><i class="helpImg"></i><span>帮助</span></a>
        </div>
        <div class="hr pull-left"></div>
        <div class="back pull-left">
            <a class="a_help" id="index_goTop"><i class="goTopImg"></i><span>回顶部</span></a>
        </div>
        <!--<a class="a_help a_back" id="index_goTop" style="display: inline-block;margin-top: 7px;width: 128px;height: 42px;"><i class="goTopImg"></i><span style="display: inline-block;vertical-align: 1px;margin-left: 5px;">回顶部</span></a>-->
    </div>
</div>
<div id="room_iframe_content">
    <iframe id="room_iframe" width="100%" height="100%" style="position:absolute;top:0;left:0;border:0;z-index:-1;opacity: 0; filter: alpha(opacity=0);"></iframe>
</div>
    <div class="wrapBm">
    <div class="w_1200 clearfix">
        <p style="padding-top:20px;padding-bottom:5px;">
            <?php if ($webType['channelType'] != 2) { ?>
            <a href="/about/about" target="_blank">关于我们</a>
            |
            <a href="/about/partner" target="_blank">商务合作</a>
            <?php } ?>
            <!-- <i></i>
            <a href="#">意见反馈</a>
            <i></i>-->
            |
            <a href="/help/helpanchor" target="_blank">帮助中心</a>
        </p>
        <p>网络文化经营许可证：<a href="http://www.miitbeian.gov.cn/" target="_blank">沪网文[2015]0711-161号</a> &nbsp;&nbsp;|&nbsp;&nbsp; 组织机构代码证：NO.2014 5734555 &nbsp;&nbsp;|&nbsp;&nbsp; 营业执照：04000000201504070097<br>地址：徐汇区华泾路509号7幢243室 &nbsp;&nbsp;|&nbsp;&nbsp; 商务QQ：438559282 &nbsp;&nbsp; <span class="t_arial">©</span> 2015 - 2018 91ns All Rights Reserved</p>
    </div>
</div>

<div class="JS-ALERT-BACKGROUND"></div>
<div class="ns-alert js-id-alert">
    <div class="win">
        <div class="header">
            <span class="_title"><?php echo $webType['name']; ?></span><span class="_tip">提示您</span>
            <i class="ico-main-2 alert-exit js-exitAlert-control"></i>
        </div>
        <div class="body clearfix"></div>
        <div class="bottom"></div>
    </div>
</div>
<div class="reg-avatar-upload" id="reg-avatar-upload">
    <div class="reg-avatar-flash" id="reg-avatar-flash"></div>
</div>
<!--dialog-login-->
<div id="theDayFirstLogin" style="height:0px;width:0px;overflow: visible;"></div>
<div class="dialog-mask"></div>

<?php if ($webType['channelType'] != 2) { ?>
<div class="dialog-login _register" id="loginbox">
    <div class="dialog-content clearfix">
        <div class="d-l-left">
            <!--登录-->
            <div id="login_user_box" class="login_user_box">
                <div class="row _texts d_focus clearfix">
                    <span class="_login_title">现在登录,</span>
                    <span class="_login_title">您就可以与主播聊天互动了！</span>
                </div>
                <div id="pcLogin" class="_LoginBy _loginShowDiv">
                    <div class="row d_focus clearfix d_point">
                        <span>用户名/手机号</span>
                    </div>
                    <div class="row d_focus clearfix">
                        <input class="login_username_input _regLogin _loginInput" type="text" name="lf_username" id="lf_username" autocomplete="off" size="12" fwin="login">
                    </div>
                    <div class="row d_focus clearfix d_point">
                        <span>密码</span>
                        <span class="_loginmsg status-tips lb-tips" style="float:right;"></span>
                    </div>
                    <div class="row d_focus clearfix">
                        <input class="login_pwd_input _regLogin _loginInput" type="password" id="lf_password" name="lf_password" size="30" class="px p_fre" fwin="login">
                    </div>
                    <div class="row checkbox clearfix" style="margin-top: 16px;">
                        <!--<i id="loginAutoSelect" class="auto-login-div autoLogin"></i>-->
                        <!--<a class="auto-login-lable">自动登录</a>-->
                        <a class="forgetpwd pull-right" href="/forgetpwd?ul=1">忘记密码>></a>
                    </div>
                </div>
                <div id="phoneLogin" class="_LoginBy _loginHideDiv">
                    <div class="_LoginByTelphone">
                        <div class="row d_focus clearfix d_point" style="margin-left: 15px;">
                            <span>手机号码</span>
                            <span id="rTelephone1" class="status-tips lb-tips" style="float:right;"></span>
                        </div>
                        <div class="row d_focus clearfix" style="margin-left: 15px;">
                            <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入手机号码">请输入手机号码</label>
                            <input id="telPhone_Input" class="_regLogin _loginInput" type="text" name="lf_tel" autocomplete="off" size="12" fwin="login">
                            <!--<span id="rTelephone1" class="status-tips lb-tips" style="float:right;margin-top: -62px;"></span>-->

                        </div>
                        <div class="row d_focus clearfix d_point" style="margin-left: 15px;">
                            <!--<span>验证码</span>-->
                            <span id="regSecurityCodeTip1" class="ns_tip_color" style="float: right;margin-right: 80px;"></span>
                        </div>
                        <div class="row d_focus clearfix" id="regSecurityCodeL" style="margin-left: 15px;"></div>
                        <div class="_changeCode" style="margin-left: 205px;">
                            看不到<a onclick="changeSecurityCodeL();">换一换</a>
                        </div>
                    </div>
                    <div class="row d_focus clearfix d_point">
                        <span>验证码</span>
                        <span id="regTelephoneTip1" class="ns_tip_color" style="float: right;margin-right: 128px;"></span>
                    </div>
                    <div class="row d_focus clearfix">
                        <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入验证码">请输入验证码</label>
                        <input id="regTelephone1" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                        <span class="telGetCode" onclick="telGetCodeLogin(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                    </div>
                </div>

                <div id="divShowLogin" class="row btn" style="display: block;">
                    <div class="login-btn">
                        <div id="loginSubmit" class="_btn _loginSub" onclick="loginSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
                <div id="divHideLogin" class="row btn" style="display: none;">
                    <div class="login-btn">
                        <div id="loginTelSubmit" class="_btn _loginSub" onclick="loginTelSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
                <div class="row btn btn2">
                    <div class="login-btn">
                        <div id="loginType" class="_btn _loginSub" onclick="phoneLogin()" onselectstart="return false">手 机 登 录</div>
                    </div>
                </div>
            </div>
            <!--注册-->
            <div id="reg_user_box" class="reg_user_box">
                <div class="row d_focus clearfix _texts regType">
                    <span id="registerByNol" class="active">用户名注册</span>
                    <span id="registerByTel">手机号注册</span>
                    <span class="noContent"></span>
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>用户名</span>
                    <span id="regUserNameTip" class="ns_tip_color" style="float: right;"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="4-12 字母，数字；区分大小写">4-12 字母，数字；区分大小写</label>
                    <input id="regUserName" class="_regLogin" type="text" autocomplete="off" size="12">
                </div>

                <div class="_LoginByTelphone _tel_reg" style="display:none;">
                    <div class="row d_focus clearfix d_point" style="margin-left:15px;*margin-left:26px;">
                        <span>手机号</span>
                        <span id="regTelephoneTip" class="ns_tip_color" style="float: right"></span>
                    </div>
                    <div class="row d_focus clearfix" style="margin-left: 15px;*margin-left:26px;">
                        <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入手机号码">请输入手机号码</label>
                        <input id="regTelephone" class="_regLogin _loginInput" type="text" name="lf_tel" autocomplete="off" size="12" fwin="login">
                    </div>
                    <div class="row d_focus clearfix d_point" style="margin-left: 15px;*margin-left:26px;">
                        <!--<span>验证码</span>-->
                        <span id="regSecurityCodeTipR" class="ns_tip_color" style="float: right;margin-right: 80px;"></span>
                    </div>
                    <div class="row d_focus clearfix" id="regSecurityCodeR" style="margin-left: 15px;*margin-left:26px;"></div>
                    <div class="_changeCode" style="margin-left: 205px;">
                        看不到<a onclick="changeSecurityCodeR();">换一换</a>
                    </div>
                </div>
                <!-- <div class="row d_focus clearfix d_point _tel_reg">
                    <span>手机号</span>
                    <span id="regTelephoneTip" class="ns_tip_color" style="float: right"></span>
                </div> -->
                <!-- <div class="row d_focus clearfix _tel_reg">
                    <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入11位数字">请输入11位数字</label>
                    <input id="regTelephone" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                    <span class="telGetCode" onclick="telGetCode(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                </div> -->
                <div class="row d_focus clearfix d_point _tel_reg">
                    <span>手机验证码</span>
                    <span id="regSmsCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _tel_reg">
                    <label defaultdisplay="default" defaultvalue="" class="_tel_number"></label>
                    <input id="regSmsCode" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                    <span class="telGetCode" onclick="telGetCode(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                </div>

                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>密码</span>
                    <span id="regUserPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD1()" onpropertychange="regCheckPWD1()"-->
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>确认密码</span>
                    <span id="regUserCheckPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserCheckPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserCheckPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD2()" onpropertychange="regCheckPWD2()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>昵称</span>
                    <span id="regNickNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="2-10 个字、不可纯数字">2-10 个字、不可纯数字</label>
                    <input id="regNickName" class="_regLogin" type="text" autocomplete="off" size="12">
                    <!--<span id="regNickNameTip" class="lb-tips _right"></span>-->
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>验证码</span>
                    <span id="regSecurityCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg" id="regSecurityCode"></div>
                <div class="_changeCode _nol_reg">
                    看不到<a onclick="changeSecurityCode();">换一换</a>
                </div>
                <div class="row _agree d_focus clearfix" style="margin-top: 22px;">
                    <i id="agreeAutoSelect" class="auto-login-div autoLogin"></i>
                    <b>我已阅读并同意<a href="/agreement/reggreement" target="_blank">《91NS使用协议》</a></b>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div class="_btn" id="registerSubmit">同意并注册</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="height-line"></div>
        <div class="d-l-right">
            <div class="row row-close clearfix" style="margin-top:5px;">
                <a href="javascript:;" title="关闭" class="close">
                    <i class="ns_icon_close"></i>
                </a>
            </div>
            <div class="right-row" id="loginOrRegisterTip">
                <span class="_tip _login">没有账号？</span>
                <span class="_btn _login" onclick="userRegister()">马上注册</span>
                <span class="_tip _reg">已有账号？</span>
                <span class="_btn _reg" onclick="userLogin()">马上登录</span>
            </div>
            <?php if ((empty($ns_iscn) ? ('0') : ($ns_iscn)) != '1') { ?>
            <div class="right-row d-directly">
                <span class="_tip">快捷登录方式：</span>
            </div>
            <div class="thirdPLogin">
                <i class="sprite-thirdPLogin PLoginweixin" onclick="thirdLogin('weixin');return false;"></i>
                <i class="sprite-thirdPLogin PLoginqq" onclick="thirdLogin('qqdenglu');return false;"></i>
                <i class="sprite-thirdPLogin PLoginweibo" onclick="thirdLogin('sinaweibo');return false;"></i>
            </div>
            <?php } ?>
        </div>
    </div>
    <!--<div class="footer"></div>-->
</div>
<?php } else { ?>
<div class="dialog-login _register" id="douzilogin">
    <div class="dialog-content clearfix">
        <div class="d-l-left">
            <!--登录-->
            <div id="login_user_box" class="login_user_box">
                <div class="row _texts d_focus clearfix">
                    <span class="_login_title">现在登录,</span>
                    <span class="_login_title" style="margin-top: 6px;">您就可以与主播聊天互动了！</span>
                </div>
                <div class="row d_focus clearfix d_point" style="margin-top: 14px;">
                    <span>用户名</span>
                </div>
                <div class="row d_focus clearfix">
                    <input class="login_username_input _regLogin _loginInput" type="text" name="lf_username" id="lf_username" autocomplete="off" size="12" fwin="login">
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>密码</span>
                    <span class="_loginmsg status-tips lb-tips" style="float:right;"></span>
                </div>
                <div class="row d_focus clearfix">
                    <input class="login_pwd_input _regLogin _loginInput" type="password" id="lf_password" name="lf_password" size="30" class="px p_fre" fwin="login">
                </div>
                <div class="row checkbox clearfix" style="margin-top: 16px;">
                    <!--<i id="loginAutoSelect" class="auto-login-div autoLogin"></i>-->
                    <!--<a class="auto-login-lable">自动登录</a>-->
                    <a class="forgetpwd pull-right" href="/forgetpwd">忘记密码>></a>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div id="loginSubmit" class="_btn _loginSub" onclick="loginSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
            </div>
            <!--注册-->
            <div id="reg_user_box" class="reg_user_box">
                <div class="row d_focus clearfix _texts">
                    注&nbsp;册
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>用户名</span>
                    <span id="regUserNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="4-12 字母，数字；区分大小写">4-12 字母，数字；区分大小写</label>
                    <input id="regUserName" class="_regLogin" type="text" autocomplete="off" size="12">
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>密码</span>
                    <span id="regUserPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD1()" onpropertychange="regCheckPWD1()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>确认密码</span>
                    <span id="regUserCheckPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserCheckPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserCheckPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD2()" onpropertychange="regCheckPWD2()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>昵称</span>
                    <span id="regNickNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="2-10 个字、不可纯数字">2-10 个字、不可纯数字</label>
                    <input id="regNickName" class="_regLogin" type="text" autocomplete="off" size="12">
                    <!--<span id="regNickNameTip" class="lb-tips _right"></span>-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>验证码</span>
                    <span id="regSecurityCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix" id="regSecurityCode" style="height: 50px;"></div>
                <div class="_changeCode">
                    看不到<a onclick="changeSecurityCode();">换一换</a>
                </div>
                <div class="row _agree d_focus clearfix" style="margin-top: 10px;">
                    <i id="agreeAutoSelect" class="auto-login-div autoLogin"></i>
                    <b>我已阅读并同意<a href="http://www.7pmi.com/regAgree.html" target="_blank">《棋牌迷用户服务协议》</a></b>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div class="_btn" id="registerSubmit">同意并注册</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="height-line"></div>
        <div class="d-l-right">
            <div class="row row-close clearfix" style="margin-top:5px;">
                <a href="javascript:;" title="关闭" class="close">
                    <i class="ns_icon_close"></i>
                </a>
            </div>
            <div class="right-row" id="loginOrRegisterTip">
                <span class="_tip _login">没有账号？</span>
                <span class="_btn _login" onclick="userRegister()">马上注册</span>
                <span class="_tip _reg">已有账号？</span>
                <span class="_btn _reg" onclick="userLogin()">马上登录</span>
                <span style="padding-top: 10px;color:#6a6a6a;font-size: 12px;display:inline-block; ">支持棋牌迷游戏账号直接登录</span>
            </div>
            <div class="right-row d-directly">
                <span class="_tip">快捷登录方式：</span>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- 多账号登录 -->
<div id="selUserAct" class="selUserAct">
    <div class="header">请选择要登录的账户</div>
    <div class="timeOut">
        <i class="lines"></i>
        <div class="timeCount">
            <span>30</span>秒
        </div>
    </div>
    <div class="ns_icon_close"></div>
    <div class="actBody hasScroll" onSelectStart="return false;">
        <div class="no-wh g-scroll">
            <div class="scroll-bg"></div>
            <div class="scroll-ban"></div>
        </div>
        <ul class="scroll-body">
        </ul>
    </div>
</div>
<div id="userCardInfo" class="userCard clearfix" jsaction="showCard" cardInfo="true">
    <i class="bgr"><i></i></i>
    <div class="cts clearfix">
        <img class="avatar" src="">
        <div class="baseInfo">
            <div class="base1 clearfix">
                <span></span>
            </div>
            <div class="_userInfoUid"><span></span></div>
            <div class="levelInfo">
            </div>
        </div>
        <span class="signature"></span>
    </div>
    <div class="cts anchorInfo clearfix">
        <div class="baseInfo clearfix">
            <span bindData="gender"></span>
            <span>|</span>
            <span bindData="birthday"></span>
            <span>|</span>
            <span bindData="location"></span>
            <span>|</span>
            <a>TA的空间</a>
        </div>
        <div class="btn focus">关注</div>
        <a class="btn goRoom">进入房间</a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->url->getStatic("web/js/20160317/ns.js"); ?>"></script>
<script type="text/javascript">
    var isAddGoogleCode = '<?php echo (empty($isAddGoogleCode) ? (false) : ($isAddGoogleCode)); ?>';
    if(isAddGoogleCode){
        //声明_czc对象:
        var _czc = _czc || [];
        var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_<?php echo $webType['cnzzID']; ?>'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/q_stat.php%3Fid%3D<?php echo $webType['cnzzID']; ?>' type='text/javascript'%3E%3C/script%3E"));
    }
</script>
<script type="text/javascript" src="<?php echo $this->url->getStatic("web/js/20160317/ga.cnzz.js"); ?>"></script>

    <script type="text/javascript" defer async data-main="<?php echo $this->url->getStatic("web/js/20160317/index.js"); ?>" src="<?php echo $this->url->getStatic("web/js/20160317/tool/require.js"); ?>"></script>
</body>
</html>