<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>修改菜单</legend>
        </div>
		<?=CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>     
            <table class="table table-hover">
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'type'); ?></th>
                    <td>
                        <?=CHtml::activeDropDownList($model, 'type', array('问题反馈', '意见收集'), array('class'=>'input-medium'));?>
                    </td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'server_id'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'server_id',array('placeholder'=>'服务器ID','class'=>'input-medium','required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'username'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'username',array('placeholder'=>'角色名','class'=>'input-medium','required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'phone'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'phone',array('placeholder'=>'手机','class'=>'input-medium','required'=>'required')); ?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'desc'); ?></th>
                    <td>
                        <?=CHtml::activeTextArea($model, 'desc', array('style'=>'width:300px; height:80px;'));?>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'image'); ?></th>
                    <td>
                       <?=$model->image ? '<span id="btnShow" class="button button-primary">显示</span>' : '无'?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'model'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'model',array('placeholder'=>'手机型号','class'=>'input-medium','required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'system'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'system',array('placeholder'=>'系统版本','class'=>'input-medium','required'=>'required')); ?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'addtime'); ?></th>
                    <td><?=date('Y-m-d H:i:s', $model->addtime)?></td>
                </tr>
            
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <a href="<?=Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript" src="<?=ASSETS_URL; ?>js/bui.js"></script>
    <script type="text/javascript">
        var Overlay = BUI.Overlay
        var dialog = new Overlay.Dialog({
            title:'图片预览',
            width:500,
            height:300,
            mask: true,
            buttons:[],
            bodyContent:'<p><img src="<?=IMAGESURL.$model->image?>"></p>'
        });
        $('#btnShow').on('click',function () {
            dialog.show();
        });
    </script>
    <link href="<?=ASSETS_URL; ?>css/bs3/dpl.css" rel="stylesheet" type="text/css" />
    <link href="<?=ASSETS_URL; ?>css/bs3/bui.css" rel="stylesheet" type="text/css" />
</body>
</html>