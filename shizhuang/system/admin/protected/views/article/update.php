<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>编辑菜单</legend>
        </div>
		<?php 
 		$form = $this->beginWidget('CActiveForm', array(
 				'id' => 'upload-form',
 				'method' => 'POST',
 				'htmlOptions' => array('class'=>'form-horizontal', 'enctype' => 'multipart/form-data', 'accept-charset'=>'utf-8','style'=>'margin:0px'),
 		));
        ?>     
        
            <table class="table table-hover">
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'game_id'); ?></th>
                	<td>
                    	<?=CHtml::activeDropDownList($model, 'game_id', $gameList, array('class'=> 'input-medium', 'id'=>'gameId', 'required'=>'required'))?>
                    </td>    
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'title'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'title',array('placeholder'=>'标题', 'class'=> 'input-xlarge', 'required'=>'required')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'cate_name'); ?></th>
                	<td>
                    	<?=CHtml::activeDropDownList($model, 'cate_name', $cateList, array('class'=> 'input-medium', 'required'=>'required'))?>
                    </td>    
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'summary'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'summary',array('placeholder'=>'概要', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'images'); ?></th>
                	<td>
                    	<?=CHtml::activeFileField($model, 'images')?>
                    	<!--  <a data-trigger="modal" href="" data-title="新增收入" title="新增收入" class="btn"><i class="icon-plus"></i> 显示</a> -->
                    	<?=$model->images ? '<span id="btnShow" class="button button-primary">显示</span>' : ''?>
                    </td>
                </tr>
                <tr>
                	<?php 
                	$this->widget('ext.kindeditor.KindEditor',
                    array(
                        'model'=>$model,
                        'attribute'=>'content',
                        )
                    ); 
                 ?>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'content'); ?></th>
                	<td>
                    	<?=CHtml::activeTextArea($model, 'content',array('style'=> 'width:860px; height:400px;')); ?>
                    </td>    
                </tr>
                
                 <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'come'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'come',array('placeholder'=>'来自', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'author'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'author',array('value'=>$this->mangerInfo['nickname'],'required'=>'required', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_title'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_title',array('placeholder'=>'seo标题','required'=>'required', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_keyword'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_keyword',array('placeholder'=>'seo关键字','required'=>'required',  'class'=> 'input-xlarge')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'seo_desc'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'seo_desc',array('placeholder'=>'seo描述', 'required'=>'required', 'class'=> 'input-xlarge')); ?></td>
                </tr>
         		
         		<tr class="closeable">
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline">
                    		<?=CHtml::activeCheckBox($model, 'status'); ?>
                            状态
                        </label></td>
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
            <?php $this->endWidget(); ?>
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
      		bodyContent:'<p><img src="<?=IMAGESURL.$model->images?>"></p>'
    	});
  		$('#btnShow').on('click',function () {
    		dialog.show();
  		});
    </script>
    <script type="text/javascript">
		var $gameId = $("select[name='Article[game_id]']");
		var $cateName = $("select[name='Article[cate_name]");
		$gameId.change(function(){
    		var id = $(this).val();
			$.ajax({  
  	         	type:'post',      
  	         	url:'<?=$this->createUrl('ajax/getCateList')?>',  
  	         	data: {gameId: id},  
  	         	cache:false,  
  	         	dataType:'text',  
  	         	success:function(data){
      	         	$cateName.html(data);
  	         	}  
  	     	});  
    	});
	</script>
    <link href="<?=ASSETS_URL; ?>css/bs3/dpl.css" rel="stylesheet" type="text/css" /> 
    <link href="<?=ASSETS_URL; ?>css/bs3/bui.css" rel="stylesheet" type="text/css" />
</body>
</html>