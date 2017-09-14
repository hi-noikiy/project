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
	            <th>ID</th>
	            <th>type</th>
	            <th>账号ID</th>      
	            <th>金额</th>
	            <th>区服</th>
	            <th>订单号</th>
	            <th>used</th>
	            <th>领取时间</th>
	            <th>status</th>
	            <th style="width:50px;text-align:center">操作</th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php 
	       		if($info){ 
			?>
	        <tr>
	            <td><?=$info['id'] ?></td>
				<td><?=$info['type']; ?></td>
				
				<td><?=$info['account_id']; ?></td>
				<td><?=$info['data']; ?></td>
				<td><?=isset($gameServer[$gameId][$info['server_id']]) ? $gameServer[$gameId][$info['server_id']] : ''; ?></td>
				<td><?=$info['ref_id']; ?></td>
				<td><?=($info['used'] == 1) ? '领取' : '未领取'; ?></td>
				<td><?=$info['used_time_stamp']; ?></td>
				
				<td><?=$info['status']; ?></td>
				
	        	<td style="text-align:center"></td>
	        </tr>
	       <?php 
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
                            <input type="text" placeholder="订单号" name="orderid" value="<?=isset($_POST['orderid'])  ? $_POST['orderid'] : '';?>" class="input-big"> 
                                  
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="1" />
                    	</td>
                    </tr>
                 
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$info ? 1 : 0; ?></strong>条记录</small>
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
      		 $.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
              	$('#tablelist').html(data);
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
		        	$serverId.html('<option value="0">区服</option>');
			    }
				
		    });
        });
    </script>
</body>
</html>
<?php } ?>