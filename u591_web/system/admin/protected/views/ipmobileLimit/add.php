<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
        <?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>   
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
        
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">类型</th>
                    <td>
                    	<?php 
                    	$is_blank = CHtml::activeRadioButtonList($model, 'type', array(1=>'IP', 2=>'机型'), array('separator'=>' '));
                    	$is_blank= str_replace("<label", "<span", $is_blank);
                    	$is_blank= str_replace("</label", "</span", $is_blank);
                    	echo $is_blank;
                    	?>
                    </td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">ip/机型</th>
                    <td>
                    	<?=CHtml::activeTextField($model, 'ipmobile', array('class'=> 'input-medium', 'required'=>'required')); ?>
                    </td>
                </tr>
                
              
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">原因</th>
                    <td>
                    	<?=CHtml::activeTextArea($model, 'reason', array('style'=>'width:500px; height:70px;'))?>    	
                    </td>
                </tr>
                 
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                    </td>
                </tr>
            </table>
        	<?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>