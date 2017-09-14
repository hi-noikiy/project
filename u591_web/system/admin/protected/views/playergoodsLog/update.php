<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
		<?=CHtml::beginForm('', 'POST', array(
				'class'=>'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',
				'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
			));?>
            <table class="table table-hover">
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>     
                    <td>
                    	<?=CHtml::activeDropDownList($model, 'game_id', $game, array('class'=>'input-medium','id'=>'gameId','required'=>'required')); ?>
					  	<select name="PlayergoodsLog[server_id]" id="serverId" class="input-medium" required="required">
							<option value="">区服</option>
							<?php
								if(isset($gameId) && !empty($gameId)){
									foreach ($gameServer[$gameId] as $k => $v) {
										$selected = (isset($model->server_id) && $model->server_id == $k) ? 'selected' : '';
										echo "<option value='$k' $selected>$v</option>";
									}
								}
							?>
						</select>
                    </td>
               	</tr>
               	<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">角色名</th>
                    <td>
						<?=CHtml::activeDropDownList($model, 'type',array('角色名', '角色ID'), array('class'=>'input-small')); ?>
						<?=CHtml::activeTextField($model, 'name', array('placeholder'=>'多个角色名 例如(张三;李四)', 'class'=>'input-xlarge', 'required'=>'required')); ?>
					</td>
                </tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品1</th>
      				<td>
						<?=CHtml::activeDropDownList($model, 'type1', $goodsType, array('class'=>'input-medium', 'required'=>'required')); ?>
      					<?=CHtml::activeTextField($model, 'param1',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
						<?=CHtml::activeTextField($model, 'amount1', array('placeholder'=>'物品数量', 'class'=>'input-medium')); ?>

					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品2</th>
      				<td>
						<?=CHtml::activeDropDownList($model, 'type2', $goodsType, array('class'=>'input-medium')); ?>
						<?=CHtml::activeTextField($model, 'param2',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
						<?=CHtml::activeTextField($model, 'amount2', array('placeholder'=>'物品数量', 'class'=>'input-medium')); ?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品3</th>
					<td>
						<?=CHtml::activeDropDownList($model, 'type3', $goodsType, array('class'=>'input-medium')); ?>
						<?=CHtml::activeTextField($model, 'param3',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
						<?=CHtml::activeTextField($model, 'amount3', array('placeholder'=>'物品数量', 'class'=>'input-medium')); ?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品4</th>
					<td>
						<?=CHtml::activeDropDownList($model, 'type4', $goodsType, array('class'=>'input-medium')); ?>
						<?=CHtml::activeTextField($model, 'param4',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
						<?=CHtml::activeTextField($model, 'amount4', array('placeholder'=>'物品数量', 'class'=>'input-medium')); ?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">领取物品提示消息</th>
      				<td>
						<?=CHtml::activeTextArea($model, 'message', array('style'=>'width: 300px; height: 80px;', 'required'=>true)); ?>
      				</td>
      			</tr>
				<tr>
					<th style="width:130px;line-height:30px;text-align:right">备注</th>
					<td>
						<?=CHtml::activeTextArea($model, 'remark', array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
					</td>
				</tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton"  class="btn btn-primary input-small"><i class="icon-save"></i> 提交</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
						<a href="<?=$this->createUrl('playergoodsLog/index');?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
					</td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">	
   		$(document).ready(function() {
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
		        	$serverId.html('<option value="">区服</option>');
			    }
				
		    });
			//验证物品ID
			var $itemtypeId = $(".itemtypeId");
			$itemtypeId.change(function () {
				var $this = $(this);
				var val = $this.val();
				$.ajax({
					type: 'POST',
					url: "<?=$this->createUrl('ajax/checkAddGodd')?>",
					data: {itemtypeId: val},
					dataType: 'json',
					success: function($data){
						if($data.status == 1){
							$this.val("").focus();
							alert($data.msg);
						}
						

					},
				});
			});

        });
    </script>
</body>
</html>