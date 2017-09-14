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
                    	<?=CHtml::dropDownList('gameid', '', $game, array('class'=>'input-medium', 'id'=>'gameId', 'required'=>'required'))?>
                    	
                    	 <select name="serverid" id="serverId" class="input-medium" required="required">
                                <option value="">区服</option>
                             	<?php 
                             		if(isset($_POST['gameid']) && !empty($_POST['gameid'])){			
                             			foreach ($gameServer[$_POST['gameid']] as $k => $v) {
											$selected = '';
											if(isset($_POST['serverid']) && $_POST['serverid'] == $k)
												$selected = 'selected';
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
                    	<select class="input-small" name="accountType" id="type">
							<option value="0" <?=$accountType == 0 ? 'selected' : '';?>>角色名</option>
							<option value="1" <?=$accountType == 1 ? 'selected' : '';?>>角色ID</option>
						</select>
                    	<input type="text" placeholder="多个角色名 例如(张三;李四)" name="playerName" value="" class="input-xlarge", required="required">
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
                        <button type="submit" id="submitButton"  class="btn btn-primary input-small"><i class="icon-save"></i> 提交</button> 
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
        });
    </script>
</body>
</html>