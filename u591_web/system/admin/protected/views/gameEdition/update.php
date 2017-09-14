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
            <?=CHtml::hiddenField('id', $id);?>
            <?=CHtml::hiddenField('serverId', $serverId);?>
            <table class="table table-hover">
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">位置</th>
                    <td><?=CHtml::dropDownList('site', $info['show_type'], $siteArr, array('required'=>'required'));?></td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">标题</th>
                    <td><?=CHtml::textField('name', $info['show_name'], array('placeholder'=>'输入名称','class'=> 'input-xlarge','required'=>'required'));?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">版本号</th>
                    <td><?=CHtml::textField('version', $info['edition_version'], array('placeholder'=>'输入版本号','class'=> 'input-xlarge','required'=>'required'));?></td>
                </tr>

                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述1</th>
                    <td><?=CHtml::TextArea('desc1',$info['edition_desc_1'],array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述2</th>
                    <td><?=CHtml::TextArea('desc2',$info['edition_desc_2'],array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述3</th>
                    <td><?=CHtml::TextArea('desc3','',array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述4</th>
                    <td><?=CHtml::TextArea('desc4','',array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述5</th>
                    <td><?=CHtml::TextArea('desc5','',array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">描述6</th>
                    <td><?=CHtml::TextArea('desc6','',array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <a href="<?=$this->createUrl('gameEdition/index/serverid/'.$serverId); ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
</html>