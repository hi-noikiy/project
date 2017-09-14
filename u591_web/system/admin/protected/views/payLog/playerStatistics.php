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
	            <th>账号ID</th>
				<th>区服</th>
	            <th>金额</th>
	            <th>CNY</th>
	       		<th></th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php 
	       		$i = 1;
	       		foreach($info as $k=>$v){ 
			?>
	        <tr>
	            <td><?=$i ?></td>
				<td><?=$v->PayID; ?></td>
				<td><?=(isset($gameId) && isset($gameServer[$gameId][$v->ServerID])) ? $gameServer[$gameId][$v->ServerID] : $v->ServerID; ?></td>
				<td><?=$v->payTotal; ?></td>
				
				<td><?=$newinfo[$k]; ?></td>
				<td></td>	       
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
							<select name="payCode" class="input-medium">
								<option value="0" selected>货币类型</option>
								<option value="CNY" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'CNY') ? 'selected' : ''?>>人民币(CNY)</option>
								<option value="TWD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'TWD') ? 'selected' : ''?>>台湾币(TWD)</option>
								<option value="USD" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'USD') ? 'selected' : ''?>>美元(USD)</option>
								<option value="VND" <?=(isset($_POST['payCode']) && $_POST['payCode'] == 'VND') ? 'selected' : ''?>>越南盾(VND)</option>
							</select>
                          	<input type="text" placeholder="统计时间" name="startTime" value="<?=$startTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">~
                            <input type="text" placeholder="统计时间" name="endTime" value="<?=$endTime?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})">
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
							<button type="submit" name="excelLoad" class="btn btn-primary input-small" title="导出"><i class="icon-download-alt"></i> 导出</button>

							<input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                  
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
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