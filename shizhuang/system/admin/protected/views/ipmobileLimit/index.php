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
	            <th>ip/机型</th>
	            <th>封号/解号</th>
	       		<th>封号原因</th>
	       		<th>操作人</th>
	            <th>操作时间</th>
	            
	            <th style="width:50px;text-align:center">操作</th>
	            <th style="width:50px;text-align:center"></th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
	            <td><?=$v->ipmobile; ?></td>
				<td><?=($v->type == 1) ? 'IP' : '机型';?></td>
				<td><?=$v->reason; ?></td>
				<td><?=$v->operator; ?></td>
	         	<td><?=$v->addtime; ?></td>
	         	<td style="text-align:center"><a data-trigger="confirm" data-content="你确定要取消吗？取消后无法恢复" href="<?php echo $this->createUrl('ipmobileLimit/cancel/id/'.$v->id); ?>"><i class="icon-remove"></i> 取消</a></td>
	            <td style="text-align:center"></td>       
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
                            <input type="text" placeholder="ip、机型" name="ipmobile" value="<?=isset($_POST['ipmobile']) ?$_POST['ipmobile'] : '';?>" class="input-medium">
                           
                  
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            
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
        <p><a href="<?=$this->createUrl('ipmobileLimit/add'); ?>" title="添加" class="btn"><i class="icon-plus"></i> 添加</a></p>
	
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