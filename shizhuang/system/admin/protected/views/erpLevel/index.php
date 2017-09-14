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
	            <th>标题</th>
	       		<th>等级</th>
	            <th style="width:50px;text-align:center">删除</th>
	            <th style="width:50px;text-align:center">修改</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v->title; ?></td>
				<td><?=$v->level; ?></td>
				<td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?=$this->createUrl('erpLevel/del/id/'.$v->id); ?>"><i class="icon-remove"></i> 删除</a></td>
	            <td style="text-align:center"><a href="<?=$this->createUrl('erpLevel/update/id/'.$v->id); ?>"  title="修改供"><i class="icon-edit"></i> 修改</a></td>
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
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td>
                            <input type="text" placeholder="标题" name="title" value="<?=isset($_POST['title'])  ? $_POST['title'] : '';?>" class="input-small">
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
        <p><a href="<?=$this->createUrl('erpLevel/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a></p>
	
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