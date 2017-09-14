<div id="article" class="container">
	<div class="tab" style="margin-top: 20px;">
		<a href="/">首页</a><span>&gt;</span> 
		<a href="/<?=$en_name?>"><?=$model->cate_name?></a><span>&gt;</span>
		<a href="javascript:void(0);"><?=$model->title?></a>
	</div>
	<div class="container clearfix" >
		<p class="title"><?=$model->title?></p>
		<p class="sub_title">时间：<?=date('Y:m:d H:i:s', $model->addtime)?></p>
		<div class="content">
			<?=$model->content?>
		</div>
	</div>
</div>