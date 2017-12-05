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
            	<!--  <tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>
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
                             		if(isset($gameId) && !empty($gameId)){			
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
                </tr>-->
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">搜索类型</th>
                    <td>
                    	<?=CHtml::dropDownList('type', $type, array('角色名', '角色ID', '账号名', '账号ID'), array('class'=>'input-small'))?>
                    	<input type="text" placeholder="" name="name" value="<?=(isset($_POST['name']) && !empty($_POST['name'])) ? $_POST['name'] : '' ?>" class="input-medium" required="required">
                    </td>
                </tr>     
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 搜索</button> 
                        <button type="reset" class="btn input-small"><i class="icon-search"></i> 重置</button> 
                    </td>
                </tr>
            </table>
        	<?=CHtml::endForm(); ?>
        	<?php if(empty($info)){ ?>
			<div class="alert alert-block">
			    <button type="button" class="close" data-dismiss="alert">&times;</button>
			    <h4>Warning!</h4>
			    暂时没有相关数据
			</div>
			<?php }else { ?>	
			<table class="table table-hover table-striped">
			    <thead>
			        <tr>
			            <th>账号ID</th>
			            <th>服务器ID</th>
			            <th>角色名</th>
			            <th>角色ID</th>  
			            <th>账号</th>		          
			            <th>分包ID</th> 
			            <th>渠道账号</th>             
			            <th>状态</th>
			            <th>设备ID</th>
			        </tr>
			    </thead>
			    <tbody>
			       	<?php  
					?>
			        <tr>
			            <td><?=$info['id']?></td>
			            <td><?=$info['server_id']?></td>
					    <td><?=$info['name'] ?></td>
					    <td><?=$info['user_id']?></td>
					    <td><?=$info['NAME']?></td>		      
					    <td><?=$info['dwFenBaoID'] ?></td>
					    <td><?=$info['channel_account'] ?></td>
					    <td><?=$info['limitType']== 0 ? '正常':'禁用' ?></td>
					    <td><?=$info['dwFenBaoUserID']?></td>
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