<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
	<div class="alert alert-block">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <h4>Warning!</h4>
	    暂时没有相关数据
	</div>
	<?php }else { ?>
	<div class="container-fluid">
	<p><a href="<?php echo $this->createUrl('manager/add'); ?>" title="新增会员" class="btn"><i class="icon-plus"></i> 新增会员</a></p>
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>编号</th>
	            <th>账号</th>
	       		<th>名字</th>
	    		<th>游戏</th>
				<th>渠道</th>
	            <th>登陆次数</th>
	            <th>最后一次登陆时间</th>
	            <th>最后一次登陆IP</th>
	            <th>注册时间</th>
	            <th>状态</th>
	            
	            <th style="width:50px;text-align:center">删除</th>
	            <th style="width:50px;text-align:center">修改</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
	            <td><?=$v->id; ?></td>
				<td><?=$v->login_name; ?></td>
				<td><?=$v->nickname; ?></td>
				<td><?=isset($game[$v->game_id]) ? $game[$v->game_id] : '0' ; ?></td>
				<td><?=isset($channelInfo[$v->channel_id]) ? $channelInfo[$v->channel_id] : '0' ; ?></td>
				<td><?=$v->login_num; ?></td>
				<td><?=$v->login_time ? date('Y-m-d H:i:s', $v->login_time) : ''; ?></td>
				<td><?=$v->login_ip;?></td>
				<td><?=$v->reg_time ? date('Y-m-d', $v->reg_time) : ''; ?></td>
				<td><?=($v->status) ? '<i class="icon-ok"></i>': '<i class="icon-remove"></i>'; ?></td>
	            <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('manager/del/id/'.$v->id); ?>"><i class="icon-remove"></i> 删除</a></td>
	            <td style="text-align:center"><a href="<?php echo $this->createUrl('manager/update/id/'.$v->id); ?>"  title="修改供会员"><i class="icon-edit"></i> 修改</a></td>
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
                            <select name="g_id" class="input-medium">
                                <option value="">会员姓名</option>
                            </select>
                            <input type="text" placeholder="会员姓名" name="name" value="<?php  if (isset($_POST['name'])) echo $_POST['name'];?>" class="input-small">
                            <button type="submit" class="btn btn-primary input-small" title="查询会员"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?php echo $count; ?>" />
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?php echo CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?php echo $count; ?></strong>个会员</small>
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