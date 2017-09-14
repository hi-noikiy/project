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
	            <th>激活码</th>
	            <th>所属游戏</th>
	       		<th>生成时间</th>
	            <th>过期时间</th>
	            <th>注册时间限制</th>
	            <th>批次</th>
	            <th>是否无限</th>
	            <th>使用状态</th>
				<th>使用次数</th>
				<th>已使用次数</th>
	            <th>使用账号</th>
	            <th>使用时间</th>
	
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
	            <td><?=$v->code_id; ?></td>
				<td><?=$v->game_type;?></td>
				<td><?=date('Y-m-d H:i', strtotime('20'.$v->time_stamp)); ?></td>
				<td><?=$v->time_limit ? date('Y-m-d', $v->time_limit) : '--'; ?></td>
				<td>
					<?=$v->register_time ? ($v->game_type? '晚于' : '早于').$v->register_time : '不限';?>
				</td>
	         	<td><?=$v->used_type; ?></td>
	         	<td><?=$v->is_limit_one ? '是' : '否'?></td>
				<td><?=$v->used ? '已使用' : '未使用'; ?></td>
				<td><?=$v->number; ?></td>
				<td>
					<?php
						if($v->number >  1){
					?>
					<a target="_blank" href="<?=$this->createUrl('codeExchange/codeLog/codeId/'.$v->code_id); ?>"><?=$v->number_used;?></a>
					<?php } else { echo '--'; } ?>
				</td>
	         	<td><?=$v->account_id ? $v->account_id :  '--'; ?></td>
	         	<td><?=$v->used_time_stamp ? $v->used_time_stamp :  '--'; ?></td>
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
                            <input type="text" placeholder="激活码" name="code_id" value="<?=isset($_POST['code_id']) ?$_POST['code_id'] : '';?>" class="input-small">
                            <input type="text" placeholder="批次" name="used_type" value="<?=isset($_POST['used_type']) ?$_POST['used_type'] : '';?>" class="input-small">
                          	<input type="text" placeholder="生成时间" name="time_stamp" value="<?=isset($_POST['time_stamp']) ?$_POST['time_stamp'] : '';?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm'})">
                            <input type="text" placeholder="过期时间" name="time_limit" value="<?=isset($_POST['time_limit']) ?$_POST['time_limit'] : '';?>" class="form_datetime input-small" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})">
                            
                            是否使用
                            <select name="used" class="input-medium">
                           	 	<option value="-1" <?=(isset($_POST['used']) && $_POST['used'] == '-1') ? 'selected' : ''?>>不限</option>
                                <option value="0" <?=(isset($_POST['used']) && $_POST['used'] == '0') ? 'selected' : ''?>>未使用</option>
                                <option value="1" <?=(isset($_POST['used']) && $_POST['used'] == '1') ? 'selected' : ''?>>已使用</option>
                            </select>
                            
                            
                            
                            <button type="submit" class="btn btn-primary input-small" title="查询会员"><i class="icon-search"></i> 搜索</button>
                            
                            <button type="submit" name="textLoad" class="btn btn-primary input-small" title="导出"><i class="icon-download-alt"></i> 导出</button>
                            
                            
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?php echo $count; ?>" />
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