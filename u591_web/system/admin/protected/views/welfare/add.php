<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>新增菜单</legend>
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
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'player_name'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'player_name',array('placeholder'=>'输入角色名称', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'real_name'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'real_name',array('placeholder'=>'输入真实名称', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'emoney'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'emoney',array('placeholder'=>'输入钻石', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'pay_date'); ?></th>
                    <td>
                        <?=CHtml::activeTextField($model, 'pay_date',array('placeholder'=>'输入起始时间','value'=>$payDate,'class'=> 'input-xlarge form_datetime',  'required'=>'required','onclick'=>"WdatePicker({dateFmt: 'yyyyMM'})")); ?>
                        <font color="red">默认起始领取时间当月，说明已领取</font>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>