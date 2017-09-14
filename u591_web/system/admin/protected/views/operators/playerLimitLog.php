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
				<th>区服</th>
	            <th>角色ID</th>
	            <th>角色名</th>
	            <th>封号/解号</th>
	       		<th>封号原因</th>
	       		<th>操作人</th>
	       		<th>截至时间</th>
	            <th>操作时间</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v->server_id;?></td>
	            <td><?=$v->player_id; ?></td>
	            <td><?=$v->player_name; ?></td>
				<td><?=($v->type == 0) ? '解号' : '封号';?></td>
				<td><?=$v->reason; ?></td>
				<td><?=$v->operator; ?></td>
				<td><?=($v->endtime == 0) ? ($v->type == 0 ? '--' : '永久') : date('Y-m-d H:i:s', $v->endtime); ?></td>
	         	<td><?=$v->addtime; ?></td>
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
                            <input type="text" placeholder="角色ID或角色名" name="accountId" value="<?=isset($_POST['playerId']) ?$_POST['playerId'] : '';?>" class="input-medium">
                           
                  
                            <button type="submit" class="btn btn-primary input-small" title="查询会员"><i class="icon-search"></i> 搜索</button>
                            
                            <!-- <button type="submit" name="load" class="btn btn-primary input-small" title="导出"><i class="icon-download-alt"></i> 导出</button> -->
                            
                            
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
        <p><a href="<?php echo $this->createUrl('operators/playerLimit'); ?>" title="角色封解" class="btn"><i class="icon-plus"></i> 角色封解</a></p>
	
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