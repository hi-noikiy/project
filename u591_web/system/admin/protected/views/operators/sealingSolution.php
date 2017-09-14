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
                    		} else {
                    			echo '<font color="red">区服单选.</font>';
							}
                    	?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">操作</th>
                    <td>
                    	<input name="operate" type="radio" value="1">封号&nbsp;&nbsp;
                    	<input name="operate" type="radio" value="0">解号&nbsp;&nbsp;
                    	<input name="operate" type="radio" value="2" checked>查询
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">账号类型</th>
                    <td>
                    	<select name="type" class="input-small">
                    		<option value="1" <?=(isset($_POST['type']) && $_POST['type'] == 1) ? 'selected' : ''?>>账号</option>
                    		<option value="2" <?=(isset($_POST['type']) && $_POST['type'] == 2) ? 'selected' : ''?>>账号ID</option>
                    	</select>
                    	<input type="text" placeholder="账号或者账号ID" name="name" value="<?=isset($_POST['name']) ? $_POST['name'] : '';?>" class="input-medium" required="required">
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
						<a href="<?=$this->createUrl('operators/sealingSolutionLog');?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
					</td>
                </tr>
            </table>
        	<?=CHtml::endForm(); ?>
        	<?php 	if(!empty($info)){ ?>
			<table class="table table-hover table-striped">
			    <thead>
			        <tr>
			            <th>账号ID</th>
			            <th>账号</th>
			            <th>状态</th>      
			        </tr>
			    </thead>
			    <tbody>
			        <tr>
			            <td><?=$info['id']?></td>
					    <td><?=$info['NAME']?></td>
					    <td><?=$info['limitType'] == '0' ? '使用中' : '停用中'?></td>		   
			        </tr>
			    </tbody>
			</table>
			<?php } ?>
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
						  url: "<?=$this->createUrl('ajax/getCheckServerList2')?>",
						  data: {gameId: val},
						  dataType: 'html',
						  success: function($data){
								$serverId.html($data);
						   },
						});
		        } else {
		        	$serverId.html('');
			    }
		    });
        });
    </script>
</body>
</html>