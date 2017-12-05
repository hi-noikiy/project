<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	<div class="container-fluid">
		<ul class="breadcrumb">
            <li>授权列表<span class="divider">/</span></li>
            
            <li class="active">
            	<a href="<?=$this->createUrl('role/app',array('id'=>$id)); ?>">应用授权</a>
            	<span class="divider">/</span>
			</li>
            <li>
            	模块授权
            	<span class="divider">/</span>	
            </li>
			<li>
				<a href="<?=$this->createUrl('role/operate',array('id'=>$id)); ?>">操作授权</a>
				<span class="divider">/</span>			
			</li>   
		</ul>
		<?php echo CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
         	<?=CHtml::hiddenField('groupId', $id)?>
           	<table class="table table-hover">
                <tbody>

                    <tr>
                        <td>
                            <select name="appId" class="input-medium">
                            	<option value="0">请选择应用...</option>
                            	<?php foreach ($datas as $k => $v) {?>
                                <option value="<?=$k?>" <?=(isset($_REQUEST['appId']) && $_REQUEST['appId'] == $k)  ? 'selected' : ''?>><?=$v?></option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=count($node); ?></strong>条记录</small>
        </p>
        
        <div id="tablelist">
			<?=CHtml::beginForm($this->createUrl('role/setModel'),'post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>
		<?=CHtml::hiddenField('groupId', $id)?>
		<?=CHtml::hiddenField('appId', $appid)?>
		<table class="table table-hover table-striped">
		    <tbody>
		       		<?php 
		                foreach ($node as $k => $v){
					?>
					<?php
						if($k%8 == 0){
					?>
	                <tr>
	                <?php  } ?>
	                
	                    <th style="width:130px;line-height:30px;text-align:right"><?=$v->title; ?></th>     
	                    <td>
	                    	<?php 
	                    		$checked = in_array($v->id, $model) ? "checked" : '';
	                    	?>
	                    	<?=CHtml::checkBox('groupModuleId[]', $checked, array('value'=>$v->id))?>
	                   </td>
                    <?php
						if( ($k+1)%8 == 0 && $k != 0){
					?>
	               			</tr>
	               	<?php 
						} 
					?>
	               	<?php 
	               		}
	               	?>
	               	<tr>
	                    <th style="width:130px;line-height:30px;text-align:right" colspan="6">
	                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
	                    </th>
	                    <td colspan="10">
	                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
	                        <a href="<?=Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
	                    </td>
                	</tr>
		    </tbody>
		</table>
		<?=CHtml::endForm(); ?>
	 	</div>
    </div>
    <?php $this->renderPartial('/public/js');  ?>   
</body>
</html>
