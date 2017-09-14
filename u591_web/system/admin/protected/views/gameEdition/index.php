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
					<th>位置</th>
					<th>版本</th>
					<th>标题</th>
					<th style="width:50px;text-align:center">操作</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($info as $v){ ?>
					<tr>
						<td><?=isset($siteArr[$v['show_type']]) ? $siteArr[$v['show_type']] : $v['show_type'];?></td>
						<td><?=$v['edition_version']; ?></td>
						<td><?=$v['show_name']; ?></td>

						<td style="text-align:center"><a href="<?=$this->createUrl('gameEdition/update/id/'.$v['id'].'/serverId/'.$serverId); ?>"  title="修改"><i class="icon-edit"></i> 修改</a></td>

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
					<button type="submit" class="btn btn-primary input-small" title="搜索"><i class="icon-search"></i> 搜索</button>
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
		<p><a href="<?=$this->createUrl('gameEdition/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a></p>

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