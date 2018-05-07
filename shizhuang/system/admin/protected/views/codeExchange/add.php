<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>新增</legend>
        </div>
		<?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px',
				'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
				));
        ?>     
            <table class="table table-hover">
                
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'game_type'); ?></th>     
                    <td>
                        <select class="input-medium" required="required" name="CodeExchange[game_type]" id="CodeExchange_game_type">
                            <?php
                                foreach ($gameList as $k => $v) {
                                    $selected = $gameId == $k ? 'selected' : '';
                                    echo "<option value='$k' $selected>$v</option>";
                                }
                            ?>
                        </select>
                    </td>
                  </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'dwFenBaoID'); ?></th>
                    <td>
                        <?php
                            $is_blank = CHtml::checkBoxList('dwFenBaoID', '0', $dwfenbaoList,  array('class'=> 'input-medium','separator'=>' '));
                            $is_blank= str_replace("<label", "<span", $is_blank);
                            $is_blank= str_replace("</label", "</span", $is_blank);
                            echo $is_blank;
                        ?>
                    </td>
                </tr>
                  <tr>
                    <th style="width:130px;line-height:30px;text-align:right">生成数量</th>     
                    <td><?=CHtml::textField('number', '10000', array('placeholder'=>'生成数量', 'class'=> 'input-medium', 'required'=>'required'));?></td>
                  </tr>
                  
                  <tr>
                    <th style="width:130px;line-height:30px;text-align:right">批次</th>     
                    <td>
                    	<?=CHtml::textField('used_type', '0', array('placeholder'=>'批次', 'class'=> 'input-medium', 'required'=>'required'));?>
                    	&nbsp;<font color="red">第一次生存不需要填写</font>
                    </td>
                  </tr>
                  
                  <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'param'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'param',array('placeholder'=>'掉落ID', 'class'=> 'input-medium', 'required'=>'required')); ?></td>
                  </tr>
          
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'time_limit'); ?></th>
                    <td>
                    	<?=CHtml::activeTextField($model, 'time_limit', array( 'placeholder'=>'过期日期', 'class'=> 'form_datetime, input-medium' ,'required'=>'required', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd'})")); ?>
                    	<font>该批次激活码过期时间，格式“yyyy-mm-dd”,如“2014-06-06”；"0"表示不限</font>
                    </td>
                </tr>
               
               <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'register_time'); ?></th>
                    <td>
                    	<?php 
                    		$is_blank = CHtml::activeRadioButtonList($model, 'register_type', array(0=>'不限', 1=>'不能早于', 2=>'不能晚于'), array('separator'=>' '));
                    		$is_blank= str_replace("<label", "<span", $is_blank);
                    		$is_blank= str_replace("</label", "</span", $is_blank);
                    		echo $is_blank;
                    	?>
                    
                    	<?=CHtml::activeTextField($model, 'register_time', array( 'placeholder'=>'注册时间', 'class'=> 'form_datetime, input-medium' ,'required'=>'required', 'onclick'=>"WdatePicker({dateFmt: 'yyyy-MM-dd'})")); ?>
                    	<font>格式“yyyy-mm-dd”,如“2014-06-06”；"0"表示不限</font>
                    </td>
                </tr>
                
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'is_limit_one'); ?></th>
                    <td>
                    	<?php 
                    		$is_blank = CHtml::activeRadioButtonList($model, 'is_limit_one', array(0=>'一个', 1=>'无限'), array('separator'=>' '));
                    		$is_blank= str_replace("<label", "<span", $is_blank);
                    		$is_blank= str_replace("</label", "</span", $is_blank);
                    		echo $is_blank;
                    	?>
                    </td>
                </tr>
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'number'); ?></th>
                    <td>
                    	<?=CHtml::activeTextField($model, 'number',array('placeholder'=>'兑换次数', 'class'=> 'input-medium', 'required'=>'required'));?>
                    </td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton" class="btn btn-primary input-small"><i class="icon-save"></i> 生成</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">
    	var $isLimitOne =  $("input[name='CodeExchange[is_limit_one]']");
    	var $number = $("input[name='CodeExchange[number]']");
    	$isLimitOne.change(function(){
        	var val = $(this).val();
        	if(val == 0){
				$number.show();
            } else {
				$number.hide();
            }
        });
    </script>
</body>
</html>