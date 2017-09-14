<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
	<div class="alert alert-block">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <h4>Warning!</h4>
	    暂时没有相关数据
	</div>
	<?php }else { ?>
	<div class="container-fluid">
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	       		<th>类型</th>
				<th>物品</th>
	            <th>录入时间</th>
	            <th>操作者</th>
	            <th>提示信息</th>
	            <th>状态</th>
				<?php if (showMenu('PLAYERGOODSLOG.UPDATE')) { ?>
					<th style="width:50px;text-align:center">编辑</th>
				<?php } ?>
				<?php if (showMenu('PLAYERGOODSLOG.REPASS')) { ?>
					<th style="width:50px;text-align:center">退回</th>
				<?php } ?>
				<?php if (showMenu('PLAYERGOODSLOG.PASS')) { ?>
					<th style="width:50px;text-align:center">通过</th>
				<?php } ?>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v->player_id; ?>(<?=$v->name; ?>)</td>
				<td>
					<?=$v->awardmoney?$v->awardmoney."金币":' '; ?>
					<?=$v->awardemoney?$v->awardemoney."钻石":' '; ?>
					<?=$v->awardtired?$v->awardtired."体力":' '; ?>
					<?=$v->awardrose?$v->awardrose."玫瑰":' '; ?>
					<?=$v->awardlily?$v->awardlily."百合":' '; ?>
					<?=$v->awardnarcissus?$v->awardnarcissus."水仙":' '; ?>
					<?=$v->awarditemtype1 ? getAddGoodName($v->awarditemtype1).' ': '';?>
					<?=$v->awarditemtype2 ? getAddGoodName($v->awarditemtype2).' ': '';?>
					<?=$v->awarditemtype3 ? getAddGoodName($v->awarditemtype3).' ': '';?>
					<?=$v->awarditemtype4 ? getAddGoodName($v->awarditemtype4).' ': '';?>

				</td>
				<td><?=$v->addtime ? date('Y-m-d H:i:s', $v->addtime) : '--';?></td>
				<td><?=$v->operator;?></td>
	         	<td><?=$v->mail_describe;?></td>
	         	<td><?=$statusArr[$v->status];?></td>

				<?php if (showMenu('PLAYERGOODSLOG.UPDATE')) { ?>
				<td style="text-align:center">
					<?php if($v->status <= 0) { ?>
					<a href="<?=$this->createUrl('playergoodsLog/update/id/'.$v->id); ?>"  title="修改"><i class="icon-edit"></i> 修改</a>
					<?php } ?>
				</td>
				<?php } ?>
				<?php if (showMenu('PLAYERGOODSLOG.REPASS')) { ?>
				<td style="text-align:center">
					<?php if($v->status == 0) { ?>
					<a data-trigger="confirm" data-content="你确定要退回吗？退回后无法恢复" href="<?=$this->createUrl('playergoodsLog/repass/id/'.$v->id); ?>"><i class="icon-remove"></i> 退回</a>
					<?php } ?>
				</td>
				<?php } ?>
				<?php if (showMenu('PLAYERGOODSLOG.PASS')) { ?>
				<td style="text-align:center">
					<?php if($v->status == 0) { ?>
					<a data-trigger="confirm" data-content="你确定要通过吗？通过后无法恢复" href="<?=$this->createUrl('playergoodsLog/pass/id/'.$v->id); ?>"><i class="icon-remove"></i> 通过</a>
					<?php } ?>
				</td>
				<?php } ?>
			</tr>
	       <?php } ?>     
	    </tbody>
	</table>
	</div>
	<?php } ?>
<?php } else { ?>
<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	<div class="container-fluid">
	       <?php echo CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td>
                            <?=CHtml::dropDownList('status', $status, $statusArr, array('class'=>'input-small'))?>
                            <button type="submit" class="btn btn-primary input-small" title="搜索"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?php echo CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条数据</small>
        </p>
	    <p>
			<?php if (showMenu('PLAYERGOODSLOG.ADD')) { ?>
			<a href="<?=$this->createUrl('playergoodsLog/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a>
			<?php } ?>
			<?php if (showMenu('PLAYERGOODSLOG.ONEKEYPASS')) { ?>
			<a data-trigger="confirm" data-content="你确定要一键审核吗？" href="<?=$this->createUrl('playergoodsLog/oneKeyPass'); ?>" title="一键审核" class="btn"><i class="icon-plus"></i> 一键审核</a>
			<?php } ?>
		</p>
		<div id="tablelist">
		</div>
	    <div class="pagination pagination-centered">
	    	<ul id='pagination'>
	
	        </ul>
		</div>
	</div> 
   <?php $this->renderPartial('/public/js');  ?>
   <script type="text/javascript">	
   		$(document).ready(function() {
	        <?php if (!empty($count)) { ?>
	        $('#pagination').jqPaginator({
	        totalCounts: <?=$count; ?>,
	                pageSize:<?=$pages->pageSize; ?>,
	                currentPage: 1,
	                onPageChange: function(num, type) {
		                $('#paginationinput').val(num);
		                $.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
		                	$('#tablelist').html(data);
	                 	});
	              	 }
	        });
	        <?php } ?>
        });
    </script>
</body>
</html>
<?php } ?>