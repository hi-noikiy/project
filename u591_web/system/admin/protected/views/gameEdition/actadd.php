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
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right">显示时间</th>
                    <td>
                    
                    	<?=CHtml::textField('starttime',$startTime, array( 'placeholder'=>'开始时间', 'class'=> 'form_datetime, input-medium' ,'required'=>'required', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})")); ?>~
                    	<?=CHtml::textField('endtime',$endTime, array( 'placeholder'=>'结束时间', 'class'=> 'form_datetime, input-medium' ,'required'=>'required', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})")); ?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">图片url</th>
                    <td><?=CHtml::textField('textureResPath', '', array('placeholder'=>'输入图片地址','class'=> 'input-xlarge','required'=>'required'));?></td>
                </tr>
  				<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">设置开服几天内不显示</th>
                    <td><?=CHtml::textField('noshowday', '0', array('placeholder'=>'设置开服前几天不显示','class'=> 'input-xlarge'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">设置开服几天内显示</th>
                    <td><?=CHtml::textField('showday', '0', array('placeholder'=>'设置开服前几天显示','class'=> 'input-xlarge'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">是否显示精灵详情</th>
                    <td><?=CHtml::dropDownList('isShowDetail', '', array('0'=>'显示','1'=>'不显示'), array('required'=>'required'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">精灵模板id</th>
                    <td><?=CHtml::textField('eudemonId', '0', array('placeholder'=>'精灵模板id','class'=> 'input-xlarge'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">精灵详情位置</th>
                    <td><?=CHtml::textField('eudDetailPos', '', array('placeholder'=>'精灵详情位置','class'=> 'input-xlarge'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">是否显示立即前往</th>
                    <td><?=CHtml::dropDownList('isShowGotoBtn', '', array('0'=>'显示','1'=>'不显示'), array('required'=>'required'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">前往按钮位置</th>
                    <td><?=CHtml::textField('gotoBtnPos', '', array('placeholder'=>'前往按钮位置','class'=> 'input-xlarge'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">前往按钮跳转模块</th>
                    <td><?=CHtml::textField('jumpModule', '0',array('placeholder'=>'前往按钮跳转模块','class'=> 'input-xlarge','required'=>'required'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">前往按钮跳转参数</th>
                    <td><?=CHtml::textField('jumpParam', '', array('placeholder'=>'前往按钮跳转参数(如：网址)','class'=> 'input-xlarge'));?></td>
                </tr>
                 
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
                        <a href="<?=$this->createUrl('gameEdition/actindex'); ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
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