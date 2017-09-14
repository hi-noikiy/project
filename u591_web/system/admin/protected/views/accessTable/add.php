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
        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">基本资料</a></li>
            <li><a href="#profile" data-toggle="tab">员工权限</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <table class="table table-hover">
                    <tr>
                        <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'title'); ?></th>
                        <td><?=CHtml::activeTextField($model, 'title',array('placeholder'=>'输入表名','class'=>'input-xlarge','required'=>'required')); ?></tr>
                    <tr class="controller">
                        <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'name'); ?></th>
                        <td><?=CHtml::activeTextField($model, 'name',array('placeholder'=>'输入字段名(field1,fiedl2,...)','class'=>'input-xlarge','required'=>'required')); ?></td>
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
            </div>
            <div class="tab-pane fade" id="profile">
                <?php
                    foreach ($managerInfo as $v) {
                ?>
                <div class="control-group">
                    <label class="control-label"><strong>&nbsp;</strong></label>
                    <div class="controls">
                        <label class="checkbox inline"><input type="checkbox" name="access[]" value="<?=$v->id?>"><?=$v->login_name?>(<?=$v->nickname?>)</label>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>