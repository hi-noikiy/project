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
                        	是否定价&nbsp;
                        	<select name="snapicon" class="input-small">
                        		<option value="0" <?=$snapicon == 0 ? 'selected' : ''?>>未定价</option>
                        		<option value="1" <?=$snapicon == 1 ? 'selected' : ''?>>已定价</option>
                        	</select>
                        	服饰类型&nbsp;
                        	<select name="sort" class="input-small">
                        		<option name="">请选择...<?php ?>
                        		<?php
                        			if(isset($menuIdArr)){ 
                        					foreach ($menuIdArr as $v){
												$selected = $sort == $v->menuid ? 'selected' : '';
                        						echo '<option value="'.$v->menuid.'" '.$selected.'>'.$v->name.'</option>';
                        					}		
                        			}
                        		?>
                        	</select>
                         	<button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <div class="container-fluid">
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>服饰ID</th>
	            <th>服饰名称</th>
	       		<th>档次</th>
	            <th>价格</th>
	            <th>百分比</th>
	            <th style="width:50px;text-align:center">操作</th>	  
	        </tr>
	    </thead>
	    <tbody>
	       	<?php
	       		foreach($priceInfo as $v){ 
			?>
	        <tr>
	            <td><?=$v->id ?></td>
				<td><?=$v->name; ?></td>
				<td><?=$v->level; ?></td>
				<td><?=$v->price1?>~<?=$v->price2?></td>
				<td><?=$v->percent; ?>%</td>
	        	<td style="text-align:center">
	        		<input name="level" type="radio" value="<?=$v->level?>">
	        	</td>
	        </tr>
	        <?php  } ?>   
	    </tbody>
	</table>
	</div>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=count($info)?></strong>条记录</small>
            <span>共有:<font id="sumTotal"><?=count($info)?></font>件&nbsp;</span>
			<span>已配置:<font id="setTotal"><?=$configured;?></font>&nbsp;</span>
			<span>配置比例:<font id="percent"><?=count($info)? round($configured/count($info), 4)*100 : '0'?></font>%</span>
        </p>
        <div id="tablelist">
			<table class="table table-hover table-striped">
		    <tbody>
		       		<?php 
		                foreach ($info as $k => $v){
					?>
					<?php
						if($k%8 == 0){
					?>
	                <tr>
	                <?php  } ?>
	                
	                    <th style="width:100px;height:100px;text-align:right;">
	                    	<img width="100" height="100" src="<?=SMALL_IMG_U_PATH.$v->icon?>.jpg"><br />
	                    	<?=$v->name; ?><br>
	                    	
	                    	<input type="checkbox" value="<?=$v->id?>" name="item[]" <?=$v->checked?>>&nbsp;
	                    	
	                    	<input type="text" name="price" data-id="<?=$v->id?>" value="<?=$v->price?>" placeholder="价格" style="width:30px;" id="price_<?=$v->id?>">
	                
	                    </th>
                    <?php
						if( ($k+1)%8 == 0 && $k != 0){
					?>
	               			</tr>
	               	<?php 
						} 
					?>
	               	<?php 
	               		}
	               	?>
	               	<tr>
	                    <th style="width:130px;line-height:30px;text-align:right" colspan="6">
	                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
	                    </th>
	                    <td colspan="10">
	                        <button id="synchronize" type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 定价同步</button> 
	                        <p><font color="red">以上定价配置完成之后，在同步！</font></p>
	                     </td>
                	</tr>
		    </tbody>
		</table>
	 	</div>
	    <div class="pagination pagination-centered">
	    	<ul id='pagination'>
	
	        </ul>
	    </div>
    </div>
   <?php $this->renderPartial('/public/js');  ?>
   <script type="text/javascript">
   $(function(){
   		var $item = $("input[name='item[]']");
   		var $sumTotal = $("#sumTotal");
   		var $setTotal = $("#setTotal");
   		var $percent = $("#percent");
   		
   		$item.click(function(){
   			var $this = $(this);
   			var menuid = <?=$sort?>;
   			var id = $this.val();
   			var level = $('input:radio[name="level"]:checked').val();
   			var $price = $("#price_"+$this.val());
   		
   			if(menuid == 0){
   				alert('请选择服饰类型！');
   				return false;
   			}
   			if(typeof(level) == 'undefined' || level == null){
   				alert('请选择星级！');
   				return false;
   			}
   			$.ajax({
   		    	url:'<?=$this->createUrl('vgAjax/shopitemLevel')?>',  
   		    	data:{shopitemId:id, level: level, menuid: menuid},    
   		    	type:'post',    
   		    	cache:false,    
   		    	dataType:'json',    
   		    	success:function(data) {
   			    	if(data.status == 1){
   			    		alert(data.msg);
   			    		$this.attr("checked",false);
   			    		return false;
   					} else if(data.status == 2) {
   						alert(data.msg);
   			    		$this.attr("checked",true);
   			    		return false;
   					} else{
   						$price.val(data.data.price);

   						$sumTotal.text(data.data.total);
   				   		$setTotal.text(data.data.setTotal);
   				   		$percent.text(data.data.percent);
   						
   					}
   		     	},
   			});
   		});
   		var $price = $("input[name='price']");
		$price.change(function(){
			var price = $(this).val();
			var levelId = $(this).attr("data-id");
			if(price == 0){
   				alert('价格不能为空！');
   				return false;
   			}
			$.ajax({
   		    	url:'<?=$this->createUrl('vgAjax/setShopitemLevelPrice')?>',  
   		    	data:{levelId:levelId, price: price},    
   		    	type:'post',    
   		    	cache:false,    
   		    	dataType:'json',    
   		    	success:function(data) {
   			    	if(data.status == 0){
   			    		alert(data.msg);
   			    		return false;
   					} else{
   						alert(data.msg);
   					}
   		     	},
   			});
		});
   			
   	var $synchronize = $("#synchronize");
   	$synchronize.click(function(){
   		
   		var menuid = <?=$sort?>;
   		if($sumTotal.text() != $setTotal.text()){
   			alert('还未配置完成');
   			return false;
   		}
   		$.ajax( {
   		    url:'<?=$this->createUrl('vgAjax/shopitemSynchronize')?>',  
   		    data:{menuid: menuid},    
   		    type:'post',    
   		    cache:false,    
   		    dataType:'json',    
   		    success:function(data) {
   		    	alert(data.msg);
   		    },
   		});
   	});
   });
 </script>
</body>
</html>