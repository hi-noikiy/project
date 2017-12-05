<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>用户列表</legend>
        </div>
		<?=CHtml::beginForm($this->createUrl('role/setUser'),'post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>
        <?=CHtml::hiddenField('roleId', $_GET['id'])?>
            <table class="table table-hover">
				<?php 
	                foreach ($manager as $k => $v){ 
				?>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=$manager[$k]['login_name']; ?>(<?=$manager[$k]['nickname']; ?>)</th>     
                    <td>
                    	<?=CHtml::checkBox('userId[]', $manager[$k]['checked'], array('value'=>$manager[$k]['id']))?>
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