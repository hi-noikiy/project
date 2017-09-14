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
                    	<?=CHtml::dropDownList('gameid', '', $game, array('class'=>'input-medium', 'id'=>'gameId', 'required'=>'required'))?>
                    </td>
               	</tr>
               	<tr>
                    <th style="width:130px;line-height:30px;text-align:right">区服</th>     
                    <td id="serverId"></td>
               	</tr>
               	
               	<tr>
      				<th style="width:130px;line-height:30px;text-align:right">唯一标识(index_id)</th>
      				<td>
      					<input type="text" placeholder="" value="<?=$indexId?>" name="indexId" class="input-medium" required="required">
      				</td>
      			</tr>
               	
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">补偿时间</th>
      				<td>
      					<input type="text" placeholder="开始时间" name="begin_date" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})" required>
      					<input type="text" placeholder="结束时间" name="end_date" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})" required>
      				</td>
      			</tr>
          		<tr>
      				<th style="width:130px;line-height:30px;text-align:right">角色创建时间限制</th>
      				<td>
      					<input type="text" placeholder="开始时间" name="role_begin_date" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})">
      					<input type="text" placeholder="结束时间" name="role_end_date" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:m:s'})">
      				</td>
      			</tr>
      			
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">角色等级限制(留空为不限制)</th>
      				<td>
      					<input type="text" placeholder="开始等级" name="levelMin" class="input-medium">
      					<input type="text" placeholder="结束等级" name="levelMax" class="input-medium">
      				</td>
      			</tr>
      			
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品1</th>
      				<td>
      					<?=CHtml::dropDownList('type1', '', $type, array('class'=>'input-medium', 'required'=>'required'))?>
      					<?=CHtml::textField('param1', '', array('placeholder'=>'物品ID', 'class'=>'input-medium'))?>&nbsp;
      					<?=CHtml::textField('amount1', '', array('placeholder'=>'物品数量', 'class'=>'input-medium'))?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品2</th>
      				<td>
      					<?=CHtml::dropDownList('type2', '', $type, array('class'=>'input-medium'))?>
      					<?=CHtml::textField('param2', '', array('placeholder'=>'物品ID', 'class'=>'input-medium'))?>&nbsp;
      					<?=CHtml::textField('amount2', '', array('placeholder'=>'物品数量', 'class'=>'input-medium'))?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品3</th>
      				<td>
      					<?=CHtml::dropDownList('type3', '', $type, array('class'=>'input-medium'))?>
      					<?=CHtml::textField('param3', '', array('placeholder'=>'物品ID', 'class'=>'input-medium'))?>&nbsp;
      					<?=CHtml::textField('amount3', '', array('placeholder'=>'物品数量', 'class'=>'input-medium'))?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品4</th>
      				<td>
      					<?=CHtml::dropDownList('type4', '', $type, array('class'=>'input-medium'))?>
      					<?=CHtml::textField('param4', '', array('placeholder'=>'物品ID', 'class'=>'input-medium'))?>&nbsp;
      					<?=CHtml::textField('amount4', '', array('placeholder'=>'物品数量', 'class'=>'input-medium'))?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">领取物品提示消息</th>
      				<td>
      					<?=CHtml::textArea('message', '', array('style'=>'width: 300px; height: 80px;', 'required'=>true))?>
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
        });
    </script>
</body>
</html>