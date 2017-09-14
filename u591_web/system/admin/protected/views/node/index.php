

<?php if(empty($model)){ ?>
<p><a href="<?php echo $this->createUrl('node/add'); ?>" title="新增菜单" class="btn"><i class="icon-plus"></i> 新增菜单</a></p>
<div class="alert alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>Warning!</h4>
    暂时没有相关数据
</div>
<?php }else { ?>
<div class="container-fluid">
<p><a href="<?php echo $this->createUrl('node/add'); ?>" title="新增菜单" class="btn"><i class="icon-plus"></i> 新增菜单</a></p>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
       		<th>显示名</th>
            <th>备注</th>
            <th>状态</th>
            <th style="width:50px;text-align:center">删除</th>
            <th style="width:50px;text-align:center">修改</th>
        </tr>
    </thead>
    <tbody>
       	<?php foreach($model as $v){ ?>
        <tr>
            <td><?php echo $v->id; ?></td>
			<td><a href="<?php echo $this->createUrl('node/index',array('id'=>$v->id)); ?>"><?php echo $v->name; ?></a></td>
			<td><?php echo $v->title; ?></td>
			<td><?php echo $v->remark; ?></td>
			<td><?php if($v->status) echo '<i class="icon-ok"></i>'; ?></td>
         
            <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('node/del/id/'.$v->id); ?>"><i class="icon-remove"></i> 删除</a></td>
            <td style="text-align:center"><a href="<?php echo $this->createUrl('node/update/id/'.$v->id); ?>"  title="修改供会员"><i class="icon-edit"></i> 修改</a></td>
        </tr>
       <?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    
   <?php $this->renderPartial('/public/js');  ?>
</body>
</html>