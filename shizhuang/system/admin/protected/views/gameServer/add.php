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
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'game_id'); ?></th>
                    <td><?=CHtml::activeDropDownList($model, 'game_id', $gameList)?></td>   
               	</tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'server_id'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'server_id',array('placeholder'=>'区服ID', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'server_name'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'server_name',array('placeholder'=>'区服名称', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
                
                <!-- <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'link'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'link',array('placeholder'=>'数据库IP', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
                
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'port'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'port',array('placeholder'=>'数据库端口', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr> -->
         
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