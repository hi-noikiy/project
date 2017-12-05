<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">
            <div id="legend" class="">
                <legend class="">新增节点<a href="<?=$_SESSION['nodePid'] ? $this->createUrl('node/index/id/'.$_SESSION['nodePid']) : $this->createUrl('node/index');?>" class="btn" title="查看"><i class="icon-double-angle-left"></i> 返回</a></legend>
            </div>
        <?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>
            <table class="table table-hover">
            	 <?php echo CHtml::activeHiddenField($model, 'pid',array('value'=>$_SESSION['nodePid'])); ?>
        		 <?php echo CHtml::activeHiddenField($model, 'level',array('value'=>$pid+1));?>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?php echo CHtml::activeLabel($model, 'name') ?></th>
                    <td><?php echo CHtml::activeTextField($model, 'name',array('placeholder'=>'输入Model名称', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                     
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"> <?php echo CHtml::activeLabel($model, 'title') ?></th>
                    <td><?php echo CHtml::activeTextField($model, 'title',array('placeholder'=>'输入显示名称', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?php echo CHtml::activeLabel($model, 'remark') ?></th>
                    <td><?php echo CHtml::activeTextArea($model, 'remark',array('rows'=>'3', 'class'=>'input-xlarge')); ?></td>
                </tr>
 	
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline">
                    		<?php echo CHtml::activeCheckBox($model, 'status'); ?>
                             <?php echo CHtml::activeLabel($model, 'status') ?>
                        </label></td>
                </tr>
               
                <tr>
                     <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?php echo ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
                    </td>
                </tr>
            </table>
         <?php echo CHtml::endForm(); ?>
    </div>

    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>