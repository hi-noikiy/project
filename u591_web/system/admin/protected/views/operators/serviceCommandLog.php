<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
		<div class="alert alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Warning!</h4>
			暂时没有相关数据
		</div>
	<?php }else { ?>
		<div class="container-fluid">
			<?php if ($type ==6) { ?>
				<p><a href="<?=$this->createUrl('operators/cancelServiceCommand/type/66/serverId/'.$serverId); ?>" title="取消" class="btn"><i class="icon-remove"></i> 全服取消公告</a></p>
			<?php } ?>
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th>类型</th>
					<th>服务器ID</th>
					<th>角色ID(角色名)</th>
					<th>截至时间</th>
					<th>时间(分)</th>
					<th>滚动消息</th>
					<th>状态</th>
					<th style="width:50px;text-align:center">操作</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($info as $v){ ?>
					<tr>
						<td><?=$typeList[$v['type']]; ?></td>
						<td><?=$v['serverid'];?></td>
						<td><?=($v['type'] == 2) ? $v['param'] : '--';  ?></td>
						<td><?=($v['award_type1'] == 0) ? '0' : date('Y-m-d H:i:s', $v['award_type1']);?></td>
						<td><?=($v['type'] == 2) ? '--' : $v['param'];  ?></td>
						<td><?=($v['type'] == 2) ? '--' : $v['message']; ?></td>
						<td><?=$v['status'] == 0 ? '使用中' : '停用'?></td>
						<td style="text-align:center">
							<?php if($v['type'] == 6) { ?>
								<a data-trigger="confirm" data-content="你确定要禁用吗？禁用后无法恢复" href="<?=$this->createUrl('operators/CancelServiceCommand/id/'.$v['id'].'/serverId/'.$v['serverid'].'/gameId/'.$gameId); ?>"><i class="icon-remove"></i> 禁用</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
<?php } else { ?>
	<?php $this->renderPartial('/public/header'); ?>
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
					<select name="gameid" id="gameId" class="input-medium" required="required">
						<?php
						foreach ($game as $k => $v) {
							$selected = $gameId == $k ? 'selected' : '';
							echo "<option value='$k' $selected>$v</option>";
						}
						?>
					</select>

					<select name="serverid" id="serverId" class="input-medium" required="required">
						<option value="">区服</option>
						<?php
						if(!empty($gameServer[$gameId])){
							foreach ($gameServer[$gameId] as $k => $v) {
								$selected = ($serverId == $k) ? 'selected': '';
								echo "<option value='$k' $selected>$v</option>";
							}
						}
						?>
					</select>
					<?=CHtml::dropDownList('type', $type, $typeList, array('class'=>'input-small', 'required'=>'required'))?>


					<button type="submit" class="btn btn-primary input-small" title="搜索"><i class="icon-search"></i> 搜索</button>

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
				pageSize:<?=$pages; ?>,
				currentPage: 1,
				onPageChange: function(num, type) {
					$('#paginationinput').val(num);
					$.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
						$('#tablelist').html(data);
					});
				}
			});
			<?php } ?>
			var $gameId = $("#gameId");
			var $serverId = $("#serverId");
			$gameId.change(function(){
				var val = $(this).val();
				if(val !=0 && val != ''){
					$.ajax({
						type: 'POST',
						url: "<?=$this->createUrl('ajax/getServerList')?>",
						data: {gameId: val},
						dataType: 'html',
						success: function($data){
							$serverId.html($data);
						},
					});
				} else {
					$serverId.html('<option value="0">区服</option>');
				}

			});
		});
	</script>
	</body>
	</html>
<?php } ?>