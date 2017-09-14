<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	    <div class="container-fluid">
   
       		<?=CHtml::beginForm('', 'POST', array(
       				'class'=>'form-inline',
					'id'=>"forminventorysupplier",
         			'accept-charset'=>'utf-8',	
				));
       		?>
            <table class="table table-hover">
            	<tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>
                    <td>
						<select name="gameid" id="gameId" class="input-medium" required>
                                <?php
                                	foreach ($game as $k => $v) {
										$selected = '';
										if(isset($gameId) && $gameId == $k)
											$selected = 'selected';
                                		echo "<option value='$k' $selected>$v</option>";
                                	}
                                ?>
						</select>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">停用账号</th>
                    <td>
						<?=CHtml::textField('oldAccount', $oldAccount , array('class'=>'input-xlarge', 'required'=>'required')); ?>
                    </td>
                </tr>
				<tr>
					<th style="width:130px;line-height:30px;text-align:right">启用账号</th>
					<td>
						<?=CHtml::textField('newAccount', $newAccount, array('class'=>'input-xlarge', 'required'=>'required')); ?>
					</td>
				</tr>
                <tr>
                     <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                    	<button type="submit" class="btn btn-primary input-small" title="校验"><i class="icon-search"></i> 校验</button>
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>

        <div class="container-fluid">
        	<?php if(!empty($accountInfo)){ ?>
			<?=CHtml::beginForm('', 'POST', array(
				'class'=>'form-inline',
				'id'=>"forminventorysupplier",
				'accept-charset'=>'utf-8',
			));
			?>
			<input type="hidden" name="action" value="change">
			<input type="hidden" name="gameid" value="<?=$gameId?>">
			<input type="hidden" name="oldId" value="<?=isset($accountInfo[0]['id']) ? $accountInfo[0]['id'] : '';?>">
			<input type="hidden" name="newId" value="<?=isset($accountInfo[1]['id']) ? $accountInfo[1]['id'] : '';?>">
			<input type="hidden" name="oldAccount" value="<?=isset($accountInfo[0]['NAME']) ? $accountInfo[0]['NAME'] : '';?>">
			<input type="hidden" name="newAccount" value="<?=isset($accountInfo[1]['NAME']) ? $accountInfo[1]['NAME'] : '';?>">
			<table class="table table-hover table-striped">
			    <thead>
			        <tr>
			            <th>类型</th>
			            <th>账号ID</th>
			       		<th>账号</th>
			       		<th>账号渠道</th>
			        </tr>
			    </thead>
			    <tbody>
					<?php foreach ($accountInfo as $v){ ?>
			        <tr>
			            <td><?=$v['type']?></td>
					    <td><?=$v['id']?></td>
					    <td><?=$v['NAME']?></td>
						<td><?=$v['channel_account']?></td>
					</tr>
					<?php } ?>
					<tr>
						<th style="width:130px;line-height:30px;text-align:right" colspan="2">
							<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
						</th>
						<td colspan="2">
							<?php if (isset($accountInfo[1]['NAME'])){ ?>
							<button type="submit" class="btn btn-primary input-small" title="转换"><i class="icon-save"></i> 转换</button>
							<?php } ?>
						</td>
					</tr>
			    </tbody>
			</table>
			<?=CHtml::endForm(); ?>
			<?php } ?>
		</div>
    </div>
   <?php $this->renderPartial('/public/js');  ?>
</body>
</html>