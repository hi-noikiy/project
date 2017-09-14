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
        		'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
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
                    <td id="serverId"></td>
                </tr>
                        
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">标识</th>
                    <td>
                    	<input type="text" placeholder="" name="indexId">
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
                        <a href="<?=$this->createUrl('vgOperators/bulletinLog');?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
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
						  url: "<?=$this->createUrl('ajax/getCheckServerList')?>",
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