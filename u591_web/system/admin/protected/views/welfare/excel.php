<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
<div class="container-fluid">

    <div id="legend" class="">
        <legend>上传</legend>
    </div>
    <?php
    $this->beginWidget('CActiveForm', array(
        'id' => 'upload-form',
        'method' => 'POST',
        'htmlOptions' => array('class'=>'form-horizontal', 'enctype' => 'multipart/form-data', 'accept-charset'=>'utf-8','style'=>'margin:0px'),
    ));
    ?>
    <table id ="table"  class="table table-hover">
        <tr>
            <th style="width:130px;line-height:30px;text-align:right">excel上传</th>
            <td>
                <?=CHtml::fileField('attachment', '' ,array('required'=>'required'));?>
            </td>
        </tr>
        <tr>
            <th style="width:130px;line-height:30px;text-align:right">
                <span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
            </th>
            <td>
                <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 导入</button>
                <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
                <a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
            </td>
        </tr>
    </table>
    <?php $this->endWidget(); ?>
</div>
<?php $this->renderPartial('/public/js');  ?>
</body>
</html>