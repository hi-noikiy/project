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
	            <th>游戏ID</th>
	            <th>渠道</th>
	       		<th>账号ID</th>
	       		<th>Token</th>
				<th>设备码</th>
	            <th>过期时间</th>
	            <th>录入时间</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
	            <td><?=$v->game_id; ?></td>
				<td><?=$v->channel;?></td>
				<td><?=$v->account_id; ?></td>
	         	<td><?=$v->token?></td>
				<td><?=$v->mac?></td>
				<td><?=$v->expired_time ? date('Y-m-d H:i:s', $v->expired_time) : '--'; ?></td>
	         	<td><?=$v->addtime ? date('Y-m-d H:i:s', $v->addtime) : '--'; ?></td>
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
                            <input type="text" placeholder="账号ID" name="accountId" value="<?=isset($_POST['accountId']) ?$_POST['accountId'] : '';?>" class="input-small">
                          
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