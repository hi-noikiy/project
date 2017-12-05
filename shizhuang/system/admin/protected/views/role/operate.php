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
            	<a href="<?=$this->createUrl('role/model',array('id'=>$id)); ?>">模块授权</a>
            	<span class="divider">/</span>	
            </li>
			<li>
				操作授权
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
                            <select name="appId" class="input-medium" id="appId">
                            	<option value="0">请选择应用...</option>
                            	<?php foreach ($app as $k => $v) {?>
                                <option value="<?=$k?>" <?=($appId == $k)  ? 'selected' : ''?>><?=$v?></option>
                                <?php } ?>
                            </select>
                            
                            <select name="moduleId" class="input-medium" id="moduleId">
                            	<option value="0">请选择模块...</option>
                            	<?php foreach ($model as $k => $v) {?>
                                <option value="<?=$k?>" <?=($moduleId == $k)  ? 'selected' : ''?>><?=$v?></option>
                                <?php } ?>
                            </select>
                            
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=count($operate); ?></strong>条记录</small>
        </p>
        
        <div id="tablelist">
			<?=CHtml::beginForm($this->createUrl('role/setOperate'),'post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>
		<?=CHtml::hiddenField('groupId', $id)?>
		<?=CHtml::hiddenField('appId', $appId)?>
		<?=CHtml::hiddenField('moduleId', $moduleId)?>
		<table class="table table-hover table-striped">
		    <tbody>
		       		<?php 
		                foreach ($operate as $k => $v){
					?>
	                <tr>   
	                    <th style="width:130px;line-height:30px;text-align:right"><?=$v['title']; ?></th>     
	                    <td>       
	                    	<?=CHtml::checkBox('groupActionId[]', $v['checked'], array('value'=>$v['id']))?>
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
		    </tbody>
		</table>
		<?=CHtml::endForm(); ?>
	 	</div>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">
		var $appId = $("#appId");
		var $moduleId = $("#moduleId");
		
		$appId.change(function(){
			var appId = $(this).val();
			if(appId == 0){
				alert('请选择应用!');	
				return false;
			}
			$.ajax({
				  type: 'POST',
				  url: "<?=$this->createUrl('ajax/roleModel')?>",
				  data: {appId: appId, id: <?=$id?>},
				  dataType: 'html',
				  success: function($data){
						$moduleId.html($data);
				   },
				});

			
		});
		
		
    </script>
</body>
</html>
