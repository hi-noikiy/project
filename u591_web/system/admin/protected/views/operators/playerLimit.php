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
			));?>   
            <table class="table table-hover">
            	<tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>
                    <td>
                    	<select name="gameid" id="gameId" class="input-medium" required="required">
                                <?php 
                                	foreach ($game as $k => $v) {
										$selected = '';
										if(isset($_POST['gameid']) && $_POST['gameid'] == $k)
											$selected = 'selected';
                                		echo "<option value='$k' $selected>$v</option>";
                                	}
                                ?>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">区服</th>
                    <td id="serverId">
                    	<?php 
                    		$i = 0;
                    		if(isset($_POST['gameid'])){
								$array =array();
								if(isset($_POST['serverId']))
									$array = $_POST['serverId'];
                    			foreach ($gameServer[$_POST['gameid']] as $k => $v){
									$checked = '';
									if(in_array($k, $array))
										$checked = 'checked';
									$br = '';
									if( ($k+1)%8 == 0 && $k != 0)
										$br = '<br>';
									echo "<input name='serverId[]' value='$k' type='checkbox' $checked> $v".$br;	
									$i ++ ;
                    			}
                    		}   	
                    	?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">操作</th>
                    <td>
                    	<input name="operate" type="radio" value="1" checked>封号&nbsp;&nbsp;
                    	<input name="operate" type="radio" value="0">解号&nbsp;&nbsp;
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">类型</th>
                    <td>
                    	<select name="type" class="input-small">
                    		<option value="0" <?=(isset($_POST['type']) && $_POST['type'] == 0) ? 'selected' : ''?>>角色名</option>
                    		<option value="1" <?=(isset($_POST['type']) && $_POST['type'] == 1) ? 'selected' : ''?>>角色ID</option>
                    	</select>
                    	<input type="text" placeholder="角色或角色ID" name="name" value="<?=isset($_POST['name']) ? $_POST['name'] : '';?>" class="input-medium" required="required">
                    </td>
                </tr>
              
              <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">截至时间</th>
                    <td>
                    	<input type="text" placeholder="" name="endtime" value="<?=isset($_POST['endtime']) ? $_POST['endtime'] : '0';?>" class="input-medium form_datetime" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})">                	
                    	&nbsp;<font color="red">0为永久封号</font>
                    </td>
                </tr>
              
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">封号/解号原因</th>
                    <td>
                    	<textarea style="width:500px; height:70px;" name="reason"></textarea>                   	
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
			var $gameId = $("#gameId");
			var $serverId = $("#serverId");

			var $operate = $("input[name='operate']");

			var $endtime = $("input[name='endtime']");
			
			
			$operate.change(function(){
				$endtime.parents("tr").show();
				if($(this).val() == 0){
					$endtime.parents("tr").hide();
				}
			});
			
	        $gameId.change(function(){
		        var val = $(this).val();

		        if(val !=0 && val != ''){
					$.ajax({
						  type: 'POST',
						  url: "<?=$this->createUrl('ajax/getCheckServerList2')?>",
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