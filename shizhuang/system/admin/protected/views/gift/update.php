<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
<div class="container-fluid">

    <div id="legend" class="">
        <legend>添加礼包</legend>
    </div>
    <?php echo CHtml::beginForm('','post',array(
        'class'=>'form-horizontal',
        'accept-charset'=>'utf-8',
        'style'=>'margin:0px'
    ));
    ?>
    <table id ="table"  class="table table-hover">

        <tr>
            <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'name'); ?></th>
            <td><?=CHtml::activeTextField($model, 'name',array('placeholder'=>'礼包名称', 'class'=> 'input-medium', 'required'=>'required')); ?></td>
        </tr>

        <tr>
            <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'desc'); ?></th>
            <td><?=CHtml::activeTextField($model, 'desc',array('placeholder'=>'礼包描述', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
        </tr>

        <tr>
            <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'used_type'); ?></th>

            <td>
                <?=CHtml::activeDropDownList($model, 'used_type', $usedTypeList, array('class'=>'input-small', 'required'=>'required'));?>
                <font color="red">1.运营后台生成激活码；2.在礼包列表倒入生成的激活码；3.选择倒入的批次。</font>
            </td>
        </tr>

        <tr>
            <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'status'); ?></th>
            <td>
                <?=CHtml::activeDropDownList($model, 'status', array('上架','下架'), array('class'=>'input-small'));?>
            </td>
        </tr>

        <tr>
            <th style="width:130px;line-height:30px;text-align:right">
                <span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
            </th>
            <td>
                <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 添加</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
                <a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
            </td>
        </tr>
    </table>
    <?=CHtml::endForm(); ?>
</div>
<?php $this->renderPartial('/public/js');  ?>

</body>
</html>