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
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'game_id'); ?></th>     
                    <td>
                    	<?=CHtml::activeDropDownList($model, 'game_id', $gameList, array('class'=> 'input-medium', 'required'=>'required'))?>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'name'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'name',array('placeholder'=>'输入名称', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                    
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'en_name'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'en_name',array('placeholder'=>'输入英文名称', 'class'=> 'input-xlarge')); ?></td>
                </tr>
         	
         		<tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_title'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_title',array('placeholder'=>'输入SEO标题', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_keyword'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_keyword',array('placeholder'=>'输入SEO关键字', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_desc'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_desc',array('placeholder'=>'输入SEO描述', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'sort'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'sort',array('placeholder'=>'输入排序', 'class'=> 'input-xlarge')); ?></td>
                
                </tr>
         		
         		<tr class="closeable">
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline"><?=CHtml::activeCheckBox($model, 'status'); ?>状态</label></td>
                </tr>
         		<tr class="closeable">
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline"><?=CHtml::activeCheckBox($model, 'is_index'); ?>首页</label></td>
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