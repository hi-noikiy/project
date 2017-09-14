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
	            <th>服饰名称</th>
	       		<th>服饰类型</th>
	            <th>发布季节</th>
	            <th>品牌</th>      
	            <th>风格1-3</th>
	            <th>颜色</th>
	            <th>图案</th>
	            <th>材质</th>
	            <th>领型</th>
	            <th>价格</th>
	            <th>录入时间</th>
	            <th>icon</th>
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
				<td><?=$menuInfo[$v->sort]; ?> <?=$menuInfo[$v->sort2]; ?></td>
				<td><?=$menuInfo[$v->season]; ?></td>
				<td><?=$brandInfo[$v->brand]; ?></td>
				
				<td>
					<?=$v->style1 ? '【'.$menuInfo[$v->style1].' '.$menuInfo[$v->style21].'】'  : ''?>
					<?=$v->style2 ? '【'.$menuInfo[$v->style2].' '.$menuInfo[$v->style22].'】'  : ''?> 
					<?=$v->style3 ? '【'.$menuInfo[$v->style3].' '.$menuInfo[$v->style23].'】'  : ''?>
				</td>
				<td><?=$menuInfo[$v->color1]; ?> <?=$menuInfo[$v->color2]; ?></td>
				<td><?=$menuInfo[$v->pattern1]; ?> <?=$menuInfo[$v->pattern1]; ?></td>
				<td><?=$menuInfo[$v->material1]; ?> <?=$menuInfo[$v->material1]; ?></td>
				
				<td><?=$menuInfo[$v->collar]; ?></td>
				
				<td><?=$v->price ? '金钱:'.$v->price : '钻石'.$v->emoney; ?></td>
				<td><?=date('Y-m-d H:i:s', $v->create_time); ?></td>
				<td><?=$v->icon; ?></td>
	        	<td style="text-align:center"><a href="<?=$this->createUrl('vgShopitem/update/id/'.$v->id); ?>"><i class="icon-edit"></i>修改</a></td>
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
                         	<input type="text" placeholder="服饰名称" name="name" value="<?=isset($_POST['name'])  ? $_POST['name'] : '';?>">
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