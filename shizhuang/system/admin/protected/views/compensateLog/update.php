<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
		<?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',
				'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
			));?>
            <table class="table table-hover">
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>     
                    <td>
						<?=CHtml::activeDropDownList($model, 'game_id', $game, array('class'=>'input-medium','id'=>'gameId','required'=>'required')); ?>
                    </td>
               	</tr>
               	<tr>
                    <th style="width:130px;line-height:30px;text-align:right">区服</th>     
                    <td id="serverId">
					</td>
               	</tr>
               	
               	<tr>
      				<th style="width:130px;line-height:30px;text-align:right">唯一标识(index_id)</th>
      				<td>
						<?=CHtml::activeTextField($model, 'index_id', array('class'=>'input-medium','required'=>'required'));?>
      				</td>
      			</tr>
               	
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">补偿时间</th>
      				<td>
						<?=CHtml::textField('begin_time', $beginDate, array('placeholder'=>'开始时间','class'=>'form_datetime input-medium', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})", 'required'=>'required')); ?>
						<?=CHtml::textField('end_time', $endDate, array('placeholder'=>'结束时间', 'class'=>'form_datetime input-medium', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})", 'required'=>'required')); ?>

					</td>
      			</tr>
          		<tr>
      				<th style="width:130px;line-height:30px;text-align:right">角色创建时间限制</th>
      				<td>
						<?=CHtml::textField('role_begin_time', $roleBeginDate, array('placeholder'=>'开始时间', 'class'=>'form_datetime input-medium', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})")); ?>
						<?=CHtml::textField('role_end_time', $roleEndDate, array('placeholder'=>'结束时间', 'class'=>'form_datetime input-medium', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})")); ?>
					</td>
      			</tr>
      			
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">角色等级限制(留空为不限制)</th>
      				<td>
						<?=CHtml::activeTextField($model, 'level_min', array('class'=>'input-medium','placeholder'=>'开始等级'));?>
						<?=CHtml::activeTextField($model, 'level_max', array('class'=>'input-medium','placeholder'=>'结束等级'));?>
      				</td>
      			</tr>
				<?php for($i=1;$i<=7;$i++){ ?>
				<tr>
					<th style="width:130px;line-height:30px;text-align:right">道具<?=$i?></th>
					<td>
						<?=CHtml::activeDropDownList($model, "type".$i, $goodsType, array('class'=>'input-medium', 'required'=>'required')); ?>
						<?=CHtml::activeTextField($model, "param".$i,array('value'=>'','placeholder'=>'物品ID或者数据或者等级', 'class'=>'input-medium'));?>
					</td>
				</tr>
				<?php } ?>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">领取物品提示消息</th>
      				<td>
						<?=CHtml::activeTextArea($model, 'message', array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
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
                        <button type="submit" id="submitButton" class="btn btn-primary input-small"><i class="icon-save"></i> 提交</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
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
						  url: "<?=$this->createUrl('ajax/getCheckServerList')?>",
						  data: {gameId: val, selected:"<?=$model->server_id;?>"},
						  dataType: 'html',
						  success: function($data){
								$serverId.html($data);
						   },
						});
		        } else {
		        	$serverId.html('<option value="">区服</option>');
			    }
				
		    });
			$gameId.change();
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
						}
						alert($data.msg);

					},
				});
			});
        });
    </script>
</body>
</html>