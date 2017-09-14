<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>配置信息</legend>
        </div>
		<?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>     
            <table class="table table-hover">
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'TWD'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'TWD',array('placeholder'=>'输入台湾币汇率', 'class'=> 'input-xlarge', 'required'=>'required')); ?></tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'USD'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'USD',array('placeholder'=>'输入美元汇率', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
				<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">输入越南盾汇率</th>
                    <td><?=CHtml::activeTextField($model, 'VND',array('placeholder'=>'输入越南盾汇率', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <!--<a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>-->
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>