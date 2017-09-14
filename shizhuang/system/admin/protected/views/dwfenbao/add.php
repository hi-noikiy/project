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
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'fenbao_id'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'fenbao_id',array('placeholder'=>'输入分包编号', 'class'=> 'input-xlarge', 'required'=>'required')); ?></tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'name'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'name',array('placeholder'=>'输入分包名称', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>

                <?php if(in_array('income', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'income'); ?></th>
                    <td>
                        <?=CHtml::activeTextField($model, 'income',array('placeholder'=>'输入流水', 'class'=> 'input-xlarge')); ?>
                        <font color="red">例如：150#150~200（阶梯流水用#隔开，单位万，对应分成比例）</font>

                    </td>
                </tr>
                <?php } ?>

                <?php if(in_array('income_split', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'income_split'); ?></th>
                    <td>
                        <?=CHtml::activeTextField($model, 'income_split',array('placeholder'=>'输入分成比例', 'class'=> 'input-xlarge')); ?>
                        <font color="red">例如：20:80#25:75（阶梯价格用#隔开，对应流水）默认0不限制</font>
                    </td>

                </tr>
                <?php } ?>

                <?php if(in_array('tariff', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'tariff'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'tariff',array('placeholder'=>'输入税率', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <?php } ?>

                <?php if(in_array('channel_cost', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'channel_cost'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'channel_cost',array('placeholder'=>'输入渠道成本比率', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <?php } ?>

                <?php if(in_array('deal_date', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'deal_date'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'deal_date',array('placeholder'=>'输入合同时间', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <?php } ?>

                <?php if(in_array('remark', $dwAccessArr)) { ?>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'remark'); ?></th>
                    <td>
                        <?=CHtml::activeTextArea($model, 'remark', array('style'=>'width:550px; height:100px;'))?>
                    </td>
                </tr>
                <?php } ?>
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