<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
        <?=CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>   
            <table class="table table-hover">
            	<tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>
                    <td>
                    	<select name="gameid" id="gameId" class="input-medium" required="required">
                                <?php 
                                	foreach ($game as $k => $v) {
										$selected = '';
										if(isset($gameId) && $gameId == $k)
											$selected = 'selected';
                                		echo "<option value='$k' $selected>$v</option>";
                                	}
                                ?>
                            </select>
                            <select name="serverid" id="serverId" class="input-medium" required="required">
                                <option value="">区服</option>
                             	<?php 
                             		if(isset($gameServer[$gameId]) && !empty($gameServer[$gameId])){
                             			foreach ($gameServer[$gameId] as $k => $v) {
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
                    <th style="width:130px;line-height:30px;text-align:right">命令类型</th>
                    <td>
                    	<?=CHtml::dropDownList('type', '', $typeList, array('required'=>'required'))?>
                    </td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">角色名</th>
                    <td>
                    	<input type="text" placeholder="角色名" name="name" value="">
                    </td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">充值金额</th>
                    <td>
                    	<input type="text" placeholder="充值金额" name="awardType1" value="">
                    </td>
                </tr>
                
                 <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">时间(单位分)</th>
                    <td>
                    	<input type="text" placeholder="" name="banTime" value="60">
                    </td>
                </tr>
                 
                 
                 <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">禁言截至时间</th>
                    <td>
                    	<input type="text" placeholder="" name="talkEndtime" value="" class="form_datetime" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">
                    </td>
                </tr>
                 <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">公告截至时间</th>
                    <td>
                    	<input type="text" placeholder="" name="bulletinEndtime" value="" class="form_datetime" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">
                    </td>
                </tr>
                 
                 
                 <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">滚动消息</th>
                    <td>
                    	<textarea name="message" style="width: 300px; height: 80px;"></textarea>
                    </td>
                </tr>
                       
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                    </td>
                </tr>
            </table>
        	<?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">	
   		$(document).ready(function() {
   	   		var $type = $("#type");
   	   		var $banTime = $("input[name='banTime']");
   	   	   	var $awardType1 = $("input[name='awardType1']");
   	   	   	var $message = $("textarea[name='message']");
   	   	   	var $talkEndtime = $("input[name='talkEndtime']");
   	   	   	var $bulletinEndtime = $("input[name='bulletinEndtime']");
   	   	   	
			$type.change(function(){
				var val = $(this).val();
				$awardType1.parents("tr").hide();
				$message.parents("tr").hide();
				$talkEndtime.parents("tr").hide();
				$bulletinEndtime.parents("tr").hide();
				if(val == 2){//禁言
					$talkEndtime.parents("tr").show();
					$banTime.parents("tr").hide();
				} else if(val == 6 || val == 66){
					$banTime.parents("tr").show();
					$bulletinEndtime.parents("tr").hide();
					$(this).parents("tr").next(".controller").hide();
					$message.parents("tr").show();
					if(val == 66){
						$banTime.parents("tr").hide();
						$message.parents("tr").hide();
					}
				} else if(val == 8){
					$banTime.parents("tr").hide();
					$(this).parents("tr").next(".controller").show();
					$message.parents("tr").show();
				} else {
					$banTime.parents("tr").hide();
					$(this).parents("tr").next(".controller").show();
					if(val == 7){
						$awardType1.parents("tr").show();
					}
				}
			});
   
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