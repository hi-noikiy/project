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
	            <th>日期</th>
	            <th>金额</th>
				<th>转<?=isset($_POST['unityCurrency']) ? $_POST['unityCurrency'] : '';?>金额</th>
	            <th>充值渠道</th>
	       		<th>充值人数</th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php
	       		$i = 1;
				if($type == 0) {
					foreach ($info as $k => $v) {
						?>
						<tr>
							<td><?=$i ?></td>
							<td><?=$k; ?></td>
							<td><?=sortOutput($v['payTotal']);?></td>
							<td><?=addTogether($v['total']);?></td>
							<td>全部</td>
							<td><?=sortOutput($v['payIdTotal']); ?></td>
						</tr>
				<?php
						$i++;
					}
				} else {
					foreach ($info as $k => $v) {
						?>
						<tr>
							<td><?=$i ?></td>
							<td><?=date('Y-m-d', strtotime($startTime)) . '~' . date('Y-m-d', strtotime($endTime)); ?></td>
							<td><?=sortOutput($v['payTotal']); ?></td>
							<td><?=addTogether($v['total']);?></td>
							<td><?=isset($channel[$k]) ? $channel[$k] : $k;?></td>
							<td><?=sortOutput($v['payIdTotal']); ?></td>
						</tr>
						<?php
						$i++;
					}
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
                            
                            <select name="gamefenbao" class="input-medium">
                                <option value="0" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 0) ? 'selected' : ''?>>全部游戏</option>
                             	<option value="1" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 1) ? 'selected' : ''?>>口袋进化</option>
                             	<option value="2" <?=(isset($_POST['gamefenbao']) && $_POST['gamefenbao'] == 2) ? 'selected' : ''?>>精灵世界</option>
                            </select>
							<select name="payCode" class="input-medium">
								<option value="0" selected>货币类型</option>
								<option value="CNY" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'CNY') ? 'selected' : ''?>>人民币(CNY)</option>
								<option value="TWD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'TWD') ? 'selected' : ''?>>台湾币(TWD)</option>
								<option value="USD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'USD') ? 'selected' : ''?>>美元(USD)</option>
							</select>

							<select name="unityCurrency" class="input-medium">
								<option value="CNY" <?=(isset($_POST['unityCurrency']) && $_POST['unityCurrency'] == 'CNY') ? 'selected' : ''?>>转人民币(CNY)</option>
								<option value="TWD" <?=(isset($_POST['unityCurrency']) && $_POST['unityCurrency'] == 'TWD') ? 'selected' : ''?>>转台湾币(TWD)</option>
								<option value="USD" <?=(isset($_POST['unityCurrency']) && $_POST['unityCurrency'] == 'USD') ? 'selected' : ''?>>转美元(USD)</option>
							</select>

                            统计方式:
                           <input type="radio" name="type" value="0" <?=($type == 0 )? 'checked' : ''?>>时间
                           <input type="radio" name="type" value="1" <?=($type == 1 )? 'checked' : ''?>>渠道&nbsp;&nbsp;
                           
                          	<input type="text" placeholder="统计时间" name="startTime" value="<?=$startTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">~
                            <input type="text" placeholder="统计时间" name="endTime" value="<?=$endTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">
                          	<button class="btn" type="button" onclick="$('#more_search').fadeToggle(); $('#financequerysearch').val($('#financequerysearch').val() == 1?0:1)"><i class="icon-rss"></i> 更多搜索</button>
                            
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                    <tr id="more_search"<?=(isset($_POST['financequerysearch']) && $_POST['financequerysearch'] ==1)  ? '' : ' style="display:none"'?>>
                        <td>
                            <input type="hidden" name="financequerysearch" id="financequerysearch" value="<?=isset($_POST['financequerysearch'])  ? $_POST['financequerysearch'] : 0?>" />
                            <input type="text" placeholder="机型" name="clientType" value="<?=isset($_POST['clientType'])  ? $_POST['clientType'] : '';?>" class="input-medium">
                          	<input type="text" placeholder="经销商分包ID" name="dwFenBaoID" value="<?=isset($_POST['dwFenBaoID'])  ? $_POST['dwFenBaoID'] : '';?>" class="input-small">    
                          	<select name="testServer" class="input-medium">
                                <option value='0' >全部</option>
                                <option value='1'  <?=(isset($_POST['testServer']) && $_POST['testServer'] == '1') ? 'selected' : ''?>>测试</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
            <small><i class="icon-info-sign"></i> 总金额<strong><?=$totalMoney; ?></strong></small>
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