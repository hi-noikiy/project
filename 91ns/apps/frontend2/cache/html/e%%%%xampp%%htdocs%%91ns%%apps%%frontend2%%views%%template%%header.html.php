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
