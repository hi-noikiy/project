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
	            <th>游戏</th>
	            <th>区服</th>
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
	            <td><?=isset($gameInfo[$v->game_id]) ? $gameInfo[$v->game_id] : $v->game_id; ?></td>
				<td><?=isset($gameServer[$v->game_id][$v->server_id]) ? $gameServer[$v->game_id][$v->server_id] : $v->server_id; ?></td>
				<td><?=$v->player_id; ?>(<?=$v->name; ?>)</td>
				<td>
					<?=$goodsType[$v->type1].' '; ?><?=$v->param1 ? getAddGoodName($v->param1).' ': '';?><?=$v->amount1.';';?>
					<?=$v->amount2 ? $goodsType[$v->type2].' ' : ''; ?><?=$v->param2 ? getAddGoodName($v->param2).' ': '';?><?=$v->amount2 ? $v->amount2.';' :'' ;?>
					<?=$v->amount3 ? $goodsType[$v->type3].' ' : ''; ?><?=$v->param3 ? getAddGoodName($v->param3).' ': '';?><?=$v->amount3 ? $v->amount3.';' :'' ;?>
					<?=$v->amount4 ? $goodsType[$v->type4].' ' : ''; ?><?=$v->param4 ? getAddGoodName($v->param4).' ': '';?><?=$v->amount4 ? $v->amount4.';' :'' ;?>

				</td>
				<td><?=$v->addtime ? date('Y-m-d H:i:s', $v->addtime) : '--';?></td>
				<td><?=$v->operator;?></td>
	         	<td><?=$v->message;?></td>
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