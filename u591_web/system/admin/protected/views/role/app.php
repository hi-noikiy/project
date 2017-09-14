<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	<div class="container-fluid">
		<ul class="breadcrumb">
            <li>授权列表<span class="divider">/</span></li>
            
            <li class="active">
            	应用授权
            	<span class="divider">/</span>
			</li>
            <li>
            	<a href="<?=$this->createUrl('role/model',array('id'=>$id)); ?>">模块授权</a>
            	<span class="divider">/</span>	
            </li>
			<li>
				<a href="<?=$this->createUrl('role/operate',array('id'=>$id)); ?>">操作授权</a>
				<span class="divider">/</span>			
			</li>   
		</ul>
		<?=CHtml::beginForm($this->createUrl('role/setApp'),'post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>
         	<?=CHtml::hiddenField('id', $id)?>
           	<table class="table table-hover">
				<?php 
	                foreach ($node as $k => $v){ 
				?>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=$v->title; ?></th>     
                    <td>
                    	<?php 
                    		$checked = in_array($v->id, $datas) ? "checked" : '';
                    	?>
                    	<?=CHtml::checkBox('groupAppId[]', $checked, array('value'=>$v->id))?>
                    
                   </td>
               	</tr>
               	<?php 
               		}
               	?>
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