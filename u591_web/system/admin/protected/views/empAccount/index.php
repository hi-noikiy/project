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
	            <th>账号ID</th>
	       		<th>所属员工</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v->accountid;?></td>
				<td><?=$v->name;?></td>
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
   <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
			<input type="hidden" id="paginationinput" name="pagination" value="1" />
            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
      <?=CHtml::endForm(); ?> 
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
        </p>
        <p><a href="<?=$this->createUrl('empAccount/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a></p>
	
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