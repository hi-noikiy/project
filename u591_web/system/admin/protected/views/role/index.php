<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
	<div class="alert alert-block">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <h4>Warning!</h4>
	    暂时没有相关数据
	</div>
	<?php }else { ?>
	<div class="container-fluid">
	<p><a href="<?php echo $this->createUrl('role/add'); ?>" title="新增角色" class="btn"><i class="icon-plus"></i> 新增角色</a></p>
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>编号</th>
	            <th>组名</th>
	       		<th>上级组</th>
	            <th>描述</th>
	            <th>状态</th>
	            <th>录入时间</th>
	            <th>更新时间</th>
	            <th style="width:50px;text-align:center">删除</th>
	            <th style="width:50px;text-align:center">修改</th>
	            <th style="width:50px;text-align:center">授权</th>
	            <th style="width:50px;text-align:center">用户</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
	            <td><?php echo $v->id; ?></td>
				<td><a href="<?php echo $this->createUrl('role/index/id/'.$v->id.''); ?>"><?php echo $v->name; ?></a></td>
				<td><?php echo $v->pid; ?></td>
				<td><?php echo $v->remark; ?></td>
				<td><?php if($v->status) echo '<i class="icon-ok"></i>'; ?></td>
	         	<td><?php echo date('Y-m-d H:i:s',$v->create_time); ?></td>
				<td><?php echo date('Y-m-d H:i:s',$v->update_time); ?></td>
	         	
	            <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('role/del/id/'.$v->id); ?>"><i class="icon-remove"></i> 删除</a></td>
	            <td style="text-align:center"><a href="<?php echo $this->createUrl('role/update/id/'.$v->id); ?>"  title="修改供会员"><i class="icon-edit"></i> 修改</a></td>
	       		<td style="text-align:center"><a href="<?php echo $this->createUrl('role/app',array('id'=>$v->id)); ?>"  title="授权"><i class="icon-edit"></i> 授权</a></td>
	        	<td style="text-align:center"><a href="<?php echo $this->createUrl('role/manager',array('id'=>$v->id)); ?>"  title="用户列表"><i class="icon-search"></i> 列表</a></td>
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
	<div id="tablelist">

        </div>
        <div class="pagination pagination-centered">
            <ul id='pagination'>

            </ul>
        </div>
   <?php $this->renderPartial('/public/js');  ?>
   <script type="text/javascript">	
   		$(document).ready(function() {
	        <?php if (!empty($count)) { ?>
	        $('#pagination').jqPaginator({
	        totalCounts: <?php echo $count; ?>,
	                pageSize:<?php echo $pages->pageSize; ?>,
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