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
                            <select name="serverid" id="serverId" class="input-medium" required>
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
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">搜索类型</th>
                    <td>
                    	<?=CHtml::dropDownList('type', $type, array('账号', '角色名'), array('class'=>'input-small'))?>
                    	<input type="text" placeholder="账号或者角色名" name="username" value="<?=isset($_POST['username'])  ? $_POST['username'] : '';?>" class="input-medium" required>
                          	
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
        
       <?=CHtml::beginForm('', 'POST', array(
       				'class'=>'form-inline',
					'id'=>"forminventorysupplier",
         			'accept-charset'=>'utf-8',	
				));
       		?> 
       		<input type="hidden" name="action" value="pay">
          	<input type="hidden" name="username" value="<?=$username?>">
          	<input type="hidden" name="type" value="<?=$type?>">
          	<input type="hidden" name="serverid" value="<?=$serverId?>">
          	<input type="hidden" name="gameid" value="<?=$gameId?>">
            <table class="table table-hover">
            	<tr>
                    <th style="width:35%; text-align:right;">充值账号</th>
                    <td><?=$accountInfo ? $accountInfo['NAME'] : '';?></td>
                </tr>
                <tr>
                    <th style="width:35%; text-align:right;">充值角色/区/游戏</th>
                    <td><?=$playerList ? $playerList['name'] : ''?>/<?=$serverId ? $gameServer[$gameId][$serverId] : '' ?>/<?=$gameId ? $game[$gameId] :  ''?></td>
                </tr>

				<tr>
					<th style="width:35%; text-align:right;">订单号</th>
					<td>
						<input type="text" placeholder="默认不写，自动生成" name="order_id" value="" class="input-large">
					</td>
				</tr>

              <?php if(isset($accountInfo['dwFenBaoID'])) { ?>
                <tr>
                    <th style="width:35%; text-align:right;">分包ID</th>
                    <td>
                    	<?=CHtml::dropDownList('dwFenBaoID', $accountInfo['dwFenBaoID'], $dwInfo)?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th style="width:35%; text-align:right;">金额</th>
                    <td>
                    	<input type="text" placeholder="金额" name="emoney" value="<?=isset($_POST['emoney'])  ? $_POST['emoney'] : '';?>" class="input-medium" required=required>    	
                    </td>
                </tr>
				<tr>
					<th style="width:35%; text-align:right;">备注</th>
					<td>
						<input type="text" placeholder="备注" name="remark" value="<?=isset($_POST['remark'])  ? $_POST['remark'] : '';?>" class="input-large" required=required>
					</td>
				</tr>


                <tr>
                     <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                    	<button type="submit" class="btn btn-primary input-small" title="充值"><i class="icon-search"></i> 充值</button>
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>  
        <div class="container-fluid">
        	<?php if(!empty($playerList)){ ?>
			<table class="table table-hover table-striped">
			    <thead>
			        <tr>
			            <th>账号ID</th>
			            <th>账号</th>
			            <th>服务器ID</th>
			            <th>角色ID</th>
			       		<th>角色名称</th>
			       		<th>分包ID</th>  
			        </tr>
			    </thead>
			    <tbody>
			        <tr>
			            <td><?=$accountInfo['id']?></td>
					    <td><?=$accountInfo['NAME']?></td>
					    <td><?=$playerList['serverid']?></td>
					    <td><?=$playerList['id']?></td>
					    <td ><?=$playerList['name']?></td>
					    <td ><?=isset($dwInfo[$accountInfo['dwFenBaoID']]) ? $dwInfo[$accountInfo['dwFenBaoID']] : $accountInfo['dwFenBaoID']; ?></td> 
			        </tr>
			    </tbody>
			</table>
			<?php } ?>
		</div>
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