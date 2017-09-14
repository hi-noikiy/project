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
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'login_name'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'login_name',array('placeholder'=>'输入账号名称', 'class'=> 'input-xlarge', 'required'=>'required', 'disabled'=>'disabled')); ?></td>
               </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'login_pass'); ?></th>
                    <td><?=CHtml::activePasswordField($model, 'login_pass',array('placeholder'=>'输入密码', 'class'=> 'input-xlarge',  'value'=>'')); ?></td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'repassword'); ?></th>
                    <td><?=CHtml::activePasswordField($model, 'repassword',array('placeholder'=>'确认密码', 'class'=> 'input-xlarge',  'value'=>'')); ?></td>
                </tr>
                
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'nickname'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'nickname',array('placeholder'=>'输入姓名', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
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
</body>
</html>