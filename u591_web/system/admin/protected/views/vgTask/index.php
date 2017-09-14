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
	            <th>编号</th>
	            <th>任务名称</th>
	       		<th>Sort</th>
	            <th>Type</th>
	            <th>发布季节</th>      
	            <th>消耗体力</th>
	            <th>完成奖励(金钱)</th>
	            <th>3.5星奖励(服饰)</th>
	            <th>4星奖励(元宝)</th>
	            <th>4.5星奖励(服饰)</th>
	            <th>5星奖励(元宝)</th>
	            <th>开始时间</th>
	            <th>投票开始时间</th>
	            <th>结束时间</th>
	            <th>任务状态</th>
	            <th style="width:50px;text-align:center">操作</th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php 
	       		$i = 1;
	       		foreach($info as $v){ 
			?>
	        <tr>
	            <td><?=$i ?></td>
				<td><?=$v->name; ?></td>
				<td><?=$sortArr[$v->sort]; ?></td>
				<td><?=$typeArr[$v->type]; ?></td>
				<td><?=$v->season; ?></td>
				
				<td><?=$v->need_tiredness; ?></td>
				<td><?=$v->bonus_money; ?></td>
				<td><?=$v->bonus_item1; ?></td>
				<td><?=$v->bonus_emoney4; ?></td>
				<td><?=$v->bonus_item2; ?></td>
				<td><?=$v->bonus_emoney5; ?></td>
				<td><?=date('ymdH', $v->time_begin); ?></td>
				<td><?=date('ymdH', $v->time_vote); ?></td>
				<td><?=date('ymdH', $v->time_end); ?></td>
				<td><?=$stateArr[$v->state]; ?></td>
	        	<td style="text-align:center"><a href="<?=$this->createUrl('vgTask/update/id/'.$v->id); ?>"><i class="icon-edit"></i>修改</a></td>
	        </tr>
	       <?php 
					$i++;
				} 
			?>     
	    </tbody>
	</table>
	</div>
	<?php } ?>
<?php } else { ?>
<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	    <div class="container-fluid">
   
       <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
                <tbody>

                    <tr>
                        <td>
                         	<input type="text" placeholder="任务名称" name="name" value="<?=isset($_POST['name'])  ? $_POST['name'] : '';?>">
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
        </p>
         <p><a href="<?=$this->createUrl('vgTask/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a></p>
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