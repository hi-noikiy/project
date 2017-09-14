<?php if($this->beginCache($id,array('duration'=>3600,'varyByParam'=>array('page')))) { ?>
<div id="info" class="wrap clearfix"style="height: 590px;">
	<ul class="tab clearfix"style="margin-top: 20px;">
		<li class="left on"><a href="javascript:void();"><?=$cate; ?></a></li>

		<li class="left"><a href="active">活动</a></li>
        <li class="left"><a href="notice">公告</a></li>
        <li class="left"><a href="strategy">游戏攻略</a></li>
	</ul>
	<div class="clear"></div>
	<ul class="content">
		<?php
			if(empty($list)){
				echo '<p class="nothing_tip">暂时没有'.$cate.'发布，敬请期待！！！</p>';
			} else {

			foreach ($list as $k => $v){ ?>
			<li class="clearfix <?= ($k== 0) ? '' : 'line_odd' ?>">
				<a href="article/<?=$v->id?>" title="<?=$v->title?>">
					<span class="left title">【<?=$v->summary?>】&nbsp;<?=$v->title?></span>
					<span class="right time"><?=date('m-d H:i', $v->addtime);?></span>
				</a>
			</li>
			<?php
					}
				}
			?>
	</ul>
	<div class="btn_page_wrapper">
	
		<?php
		    $this->widget('CLinkPager',array(  
		        'header'=>'',  
		        'firstPageLabel' => '首页',  
		        'lastPageLabel' => '末页',  
		        'prevPageLabel' => '',  
		        'nextPageLabel' => '',  
		        'pages' => $pages,  
		        'maxButtonCount'=>13  
		        )  
		    );  
	    ?>
	</div>
</div>
<?php $this->endCache(); } ?>