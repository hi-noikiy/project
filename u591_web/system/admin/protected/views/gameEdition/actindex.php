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
	       		<th style="width:650px;display:inline-block;word-wrap: break-word; overflow:hidden; text-overflow:ellipsis;">区服</th>
	       		<th>显示时间</th>
	       		<th>图片</th>
	            <th style="width:180px;text-align:center">状态</th>
	            <th style="width:200px;text-align:center">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v['id']; ?></td>
				<td style="width:650px;display:inline-block; word-wrap: break-word; overflow:hidden; text-overflow:ellipsis;"><?=$v['serverid']; ?></td>
				<td><?=$v['showtime']; ?></td>
				<td><img src="<?=$v['imgurl']; ?>"/></td>
	            <td style="text-align:center"><label for='radio<?=$v['id']; ?>1'><input  attrid ="<?=$v['id']; ?>" type='radio' name='radio<?=$v['id']; ?>' id='radio<?=$v['id']; ?>1' value="0" <?php if(!$v['isShowActivity']){ ?>checked<?php }?>/>显示</label>
	            <label for='radio<?=$v['id']; ?>2'><input  attrid ="<?=$v['id']; ?>" type='radio' name="radio<?=$v['id']; ?>" id='radio<?=$v['id']; ?>2' value="1" <?php if($v['isShowActivity']){ ?>checked<?php }?>/>不显示</label></td>
	            <td style="text-align:center"><a href="<?=$this->createUrl('gameEdition/actupdate/id/'.$v['id']); ?>"  title="修改"><i class="icon-edit"></i> 修改</a></td>
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
							<?=CHtml::dropDownList('status', $status, array('0'=>'全部',1=>'正在进行的活动',2=>'已完结或者不显示的活动'), array('class'=>'input-small'))?>
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
        <p><a href="<?=$this->createUrl('gameEdition/actadd'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a></p>
	
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
	                pageSize:20,
	                currentPage: 1,
	                onPageChange: function(num, type) {
		                $('#paginationinput').val(num);
		                $.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
		                	var id,status;
		                	$('#tablelist').html(data);
		                    $('input[type="radio"]').change(function(){
			                     id = $(this).attr('attrid');
			                     status = $(this).val();
		            			$.get("<?=$this->createUrl('ajax/actedit'); ?>",{id:id,status:status},function(json){});
		                    });
	                 	});
	              	 }
	        });
	        <?php } ?>
        });
    </script>
</body>
</html>
<?php } ?>