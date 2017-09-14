<div id="index_header" class="clearfix">
	<div class="left carousel">
		<div class="carousel_container">
			<ul class="clearfix" style="width: 2040px;">
				<li class="left">
					<a href="#" title="" target="_blank"><img src="<?=ASSETS_URL?>images/1458183099221_thum.jpg" width="510" height="250"></a>
				</li>
				<li class="left">
					<a href="#" title="" target="_blank"><img src="<?=ASSETS_URL?>images/1452664800675_thum.jpg" width="510" height="250"></a>
				</li>
				<li class="left">
					<a href="#" title="" target="_blank"><img src="<?=ASSETS_URL?>images/1451989280350_thum.jpg" width="510" height="250"></a>
				</li>
					<li class="left"><a href="#" title="#" target="_blank"><img src="<?=ASSETS_URL?>images/1445401566418_thum.jpg" width="510" height="250"></a>
				</li>
			</ul>
		</div>
		<ol class="numbers">
			<li class="left" data-value="0"><span></span></li>
		</ol>
	</div>
	<div class="right announces">
		<ul class="tab clearfix">
            <li class="left on" data-id="newdata" style="padding-left: 12px; padding-right: 12px; margin-right: 0px;">最新</li>
            <li class="left" data-id="news" style="padding-left: 12px; padding-right: 12px; margin-right: 0px;">新闻</li>
            <li class="left" data-id="announce" style="padding-left: 12px; padding-right: 12px; margin-right: 0px;">公告</li>
            <li class="left" data-id="activity" style="padding-left: 12px; padding-right: 12px; margin-right: 0px;">活动</li>
			<li class="left" data-id="info" style="padding-left: 12px; padding-right: 12px; margin-right: 0px;">攻略</li>
			<a class="btn_more right" href="news" style="width: 48px; height: 24px; padding: 5px 0px 5px 12px; margin-right: 0px; border-right-width: 0px; border-left-width: 20px;">更多<i></i></a>
		</ul>
		<ul class="container clearfix" id="news" style="display: block;">
			<?php foreach ($noticeInfo as $v) {?>
			<li class="clearfix">
				<a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->summary?>】<?=$v->title?></span></a>
				<span class="date"><?=date('m-d', $v->addtime)?></span>
			</li>
			<?php } ?>
		</ul>
		<ul class="container clearfix" id="announce" style="display: none;">
			<?php foreach ( $notice as $v) {?>
			<li class="clearfix">
				<a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->summary?>】<?=$v->title?></span></a>
				<span class="date"><?=date('m-d', $v->addtime)?></span>
			</li>
			<?php } ?>
		</ul>
        <ul class="container clearfix" id="activity" style="display: none;">
            <?php foreach ($dataInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->summary?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>
        </ul>
        <ul class="container clearfix" id="info" style="display: none;">
            <?php foreach ($strategyInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->summary?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>
        </ul>
        <ul class="container clearfix" id="atl" style="display: none;">
            <?php foreach ($atlasInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>
        </ul>
        <ul class="container clearfix" id="newdata" style="display: none;">
            <?php foreach ($noticeInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->cate_name?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>
            <?php foreach ($dataInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->cate_name?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>

            <?php foreach ($strategyInfo as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->cate_name?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>

            <?php foreach ( $notice as $v) {?>
                <li class="clearfix">
                    <a href="article/<?=$v->id?>" title="<?=$v->title?>"><span class="left title">&nbsp;【<?=$v->cate_name?>】<?=$v->title?></span></a>
                    <span class="date"><?=date('m-d', $v->addtime)?></span>
                </li>
            <?php } ?>

        </ul>
	</div>
    <div id="atlas" class="clearfix">
        <!--<div class="left strategy"
			style="width: 465px; margin-right: 10px;">
			<h5 class="t_header clearfix">
				<span class="t_title">游戏攻略</span><a href="strategy" target="_blank">更多<i></i></a>
			</h5>
			<ul class="container">
				<?php foreach ($strategyInfo as $v) { ?>
				<li class="clearfix">
					<a href="/article/<?=$v->id?>" target="_blank" title="<?=$v->title?>">
						<span class="left title"><span class="title_dot">?</span>&nbsp;<?=$v->title?></span>
					</a>
					<span class="date">[<?=date('m-d', $v->addtime)?>]</span>
				</li>
				<?php } ?>
			</ul>
		</div>-->
		<div class="left hot_topic" style="width: 952px;">
            <h5 class="t_header clearfix" style="background-color: #394359;">
                <span class="t_title"><font size="3" color="white"> 精灵图鉴 </font></span><a href="atlas" target="_blank"><font size="3" color="white">更多</font><i></i></a>
            </h5>
                    <ul class="container" style="height: 260px;">
                        <?php foreach ($atlasInfo as $v) { ?>
                            <li class="clearfix">
                                <a href="/article/<?=$v->id?>" target="_blank" >
                                    <img src="<?=IMAGESURL.$v->images?>" data-url="" width="173px" height="221px" title="<?=$v->title?>">

                                    <p><?=$v->title?></p>
                                </a>
                            </li>
                        <?php } ?>
			</ul>
		</div>
	</div>
</div>
<!--<div id="atlas">
	<h5 class="t_header clearfix">
		<span class="t_title">宠物图鉴</span><a href="atlas">更多<i></i></a>
	</h5>
	<div class="container clearfix" id="handbook_1" data-value="0">
		<div class="scroll_wrapper">
			<ul class="clearfix">
				<?php foreach ($atlasInfo as $v) { ?>
				<li class="left">
					<a href="/article/<?=$v->id?>">
						<img src="<?=IMAGESURL.$v->images?>" data-url="" width="173px" height="221px" title="<?=$v->title?>">
						<p><?=$v->title?></p>
				</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>-->