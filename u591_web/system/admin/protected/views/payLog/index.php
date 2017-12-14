<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
	<div class="alert alert-block">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <h4>Warning!</h4>
	    暂时没有相关数据
	</div>
	<?php }else { ?>
	<div class="container-fluid">
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>编号</th>
	            <th>充值渠道</th>
	       		<th>充值方式</th>
	            <th>账号</th>
	            <th>账号ID</th>      
	            <th>金额</th>
	            <th>卡号</th>
	            <th>密码</th>
	            <th>银行编码</th>
	            <th>游戏</th>
	            <th>区服</th>
	            <th>订单号</th>
	            <th>充值状态</th>
	            <th>游服入库状态</th>
	            <th>提交时间</th>
	            <th>提交状态</th>
	            <th style="width:50px;text-align:center">补单</th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php 
	       		$i = 1;
	       		foreach($info as $v){ 
			?>
	        <tr>
	            <td><?=$i ?></td>
				<td><?=isset($channel[$v->CPID]) ? $channel[$v->CPID] : ''; ?></td>
				<td><?=$v->PayCode; ?></td>
				
				<td><?=$v->PayName; ?></td>
				<td><?=$v->PayID; ?></td>
				<td><?=$v->PayMoney; ?></td>
				<td><?=$v->CardNO; ?></td>
				<td><?=$v->CardPwd; ?></td>
				<td><?=$v->BankID; ?></td>
				<td><?=isset($game[$v->game_id]) ? $game[$v->game_id] : '' ; ?></td>
				<td><?=isset($gameServer[$v->game_id][$v->ServerID]) ? $gameServer[$v->game_id][$v->ServerID] : $v->ServerID; ?></td>
				<td><?=$v->OrderID; ?></td>
				
				<td><?=$v->rpCode == 1 ? '成功' : ($v->rpCode == 2 ? '失败' : '无回执'); ?></td>
				<td><?= $v->IsUC == 1 ? '未到账' : '已到账'; ?></td>
				<td><?=$v->Add_Time; ?></td>
				<td><?=$v->SubStat; ?></td>
	        	<td style="text-align:center"><a target="_blank" href="<?=$this->createUrl('payLog/supplement/orderid/'.$v->OrderID); ?>"><i class="icon-plus"></i> 补单</a></td>
	        </tr>
	       <?php 
					$i++;
				} 
			?>
	    </tbody>
	</table>
	</div>
	<?php } ?>
<?php } else { ?>
<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	    <div class="container-fluid">
   
       <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
                <tbody>

                    <tr>
                        <td>
                        	<select name="channel" class="input-medium">
                                <?php           	
                                	foreach ($channel as $k => $v) {
										$selected = $channelId == $k ? 'selected' : '';
                                		echo "<option value='$k' $selected>$v</option>";
                                	}
                                ?>
                            </select>
                        
                            <input type="text" placeholder="账号" name="name" value="<?=isset($_POST['name'])  ? $_POST['name'] : '';?>" class="input-small">
                            <input type="text" placeholder="账号ID" name="accountId" value="<?=isset($_POST['accountId'])  ? $_POST['accountId'] : '';?>" class="input-small">
                            
                            <input type="text" placeholder="订单号" name="orderid" value="<?=isset($_POST['orderid'])  ? $_POST['orderid'] : '';?>" class="input-small">
                           
                           <select name="gameid" id="gameId" class="input-medium">
                                <?php 
                                	foreach ($game as $k => $v) {
										$selected = $gameId == $k ? 'selected' : '';
                                		echo "<option value='$k' $selected>$v</option>";
                                	}
                                ?>
                            </select>
                            
                            <select name="serverid" id="serverId" class="input-medium">
                                <option value="0">区服</option>
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
                            
                            <select name="gamefenbao" class="input-medium">
                                <option value="0" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 0) ? 'selected' : ''?>>全部游戏</option>
                             	<option value="1" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 1) ? 'selected' : ''?>>口袋进化</option>
                             	<option value="2" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 2) ? 'selected' : ''?>>精灵世界</option>
                            </select>
                            
                            <select name="rpCode" class="input-medium">
                                <option value="0" <?=(isset($_POST['rpCode']) && $_POST['rpCode'] == 0) ? 'selected' : ''?>>充值状态</option>
                             	<option value="1" <?=(isset($_POST['rpCode']) && $_POST['rpCode'] == 1) ? 'selected' : ''?>>成功</option>
                             	<option value="2" <?=(isset($_POST['rpCode']) && $_POST['rpCode'] == 2) ? 'selected' : ''?>>失败</option>
                             	<option value="3" <?=(isset($_POST['rpCode']) && $_POST['rpCode'] == 3) ? 'selected' : ''?>>无回执</option>
                            </select>

							<select name="payCode" class="input-medium">
								<option value="0" selected>货币类型</option>
								<option value="CNY" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'CNY') ? 'selected' : ''?>>人民币(CNY)</option>
								<option value="TWD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'TWD') ? 'selected' : ''?>>台湾币(TWD)</option>
								<option value="USD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'USD') ? 'selected' : ''?>>美元(USD)</option>
							</select>
                            
                            <select name="IsUC" class="input-medium">
                                <option value="0" <?=(isset($_POST['IsUC']) && $_POST['IsUC'] == 0) ? 'selected' : ''?>>游服充值状态</option>
                             	<option value="1" <?=(isset($_POST['IsUC']) && $_POST['IsUC'] == 1) ? 'selected' : ''?>>未到账成功</option>
                            </select>
 
                           	<button class="btn" type="button" onclick="$('#more_search').fadeToggle(); $('#financequerysearch').val($('#financequerysearch').val() == 1?0:1)"><i class="icon-rss"></i> 更多搜索</button>
                            
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
							<button type="submit" name="excelLoad" class="btn btn-primary input-small" title="导出"><i class="icon-download-alt"></i> 导出</button>

							<input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                    <tr id="more_search"<?=(isset($_POST['financequerysearch']) && $_POST['financequerysearch'] ==1)  ? '' : ' style="display:none"'?>>
                        <td>
                            <input type="hidden" name="financequerysearch" id="financequerysearch" value="<?=isset($_POST['financequerysearch'])  ? $_POST['financequerysearch'] : 0?>" />
                            
                            <input type="text" placeholder="机型" name="clientType" value="<?=isset($_POST['clientType'])  ? $_POST['clientType'] : '';?>" class="input-medium">
                           
                           <input type="text" placeholder="金额范围" name="sMoney" value="<?=isset($_POST['sMoney'])  ? $_POST['sMoney'] : '';?>" class="input-small">~
                           <input type="text" placeholder="金额范围" name="eMoney" value="<?=isset($_POST['eMoney'])  ? $_POST['eMoney'] : '';?>" class="input-small">
                          	<input type="text" placeholder="经销商分包ID" name="dwFenBaoID" value="<?=isset($_POST['dwFenBaoID'])  ? $_POST['dwFenBaoID'] : '';?>" class="input-small">
                          
                          	<input type="text" placeholder="充值时间" name="startTime" value="<?=$startTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">~
                            <input type="text" placeholder="充值时间" name="endTime" value="<?=$endTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">
                             
                        
                        </td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
            <small><i class="icon-info-sign"></i> 总金额<strong><?=$totalPayMoney; ?></strong></small>
        </p>
		<p>
			<?php if (showMenu('PAYLOG.ONEKEYPASS')) { ?>
				<a data-trigger="confirm" data-content="你确定要一键补单吗？" href="<?=$this->createUrl('payLog/oneKeyPass'); ?>" title="一键补单" class="btn"><i class="icon-plus"></i> 一键补单</a>
			<?php } ?>
		</p>
		<div id="tablelist">
	
	 	</div>
	    <div class="pagination pagination-centered">
	    	<ul id='pagination'>
	
	        </ul>
	    </div>
    </div>
   <?php $this->renderPartial('/public/js');  ?>
   <script type="text/javascript">	
   		$(document).ready(function() {
	        <?php if (!empty($count)) { ?>
	        $('#pagination').jqPaginator({
	        totalCounts: <?=$count; ?>,
	                pageSize:<?=$pages->pageSize; ?>,
	                currentPage: 1,
	                onPageChange: function(num, type) {
		                $('#paginationinput').val(num);
		                $.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
		                	$('#tablelist').html(data);
	                 	});
	              	 }
	        });
	        <?php } ?>
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
<?php } ?>