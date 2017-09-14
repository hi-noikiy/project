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
                    <th style="width:130px;line-height:30px;text-align:right">目录</th>
                    <td>
                        <div id="meun_category"> 
                           	<?php echo CHtml::activeDropDownList($model, 'm_id', array(), array('class'=>'input-medium meun_grade', 'data-value'=>$m_id)) ?>            
                        	<?php echo CHtml::activeDropDownList($model, 'parentid', array(), array('class'=>'input-medium meun_parentid', 'data-value'=>$parentid)) ?>
                        </div>
						
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">菜单名称</th>     
                    <td><?php echo CHtml::activeTextField($model, 'name',array('placeholder'=>'输入分类名称', 'class'=> 'input-xlarge', 'required'=>'required')); ?></tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">CONTROLLER</th>
                    <td><?php echo CHtml::activeTextField($model, 'controller',array('placeholder'=>'输入CONTROLLER', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right">ACTION</th>
                    <td><?php echo CHtml::activeTextField($model, 'action',array('placeholder'=>'输入ACTION', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">备注</th>
                    <td><?php echo CHtml::activeTextField($model, 'remark',array('placeholder'=>'输入备注', 'class'=> 'input-xlarge')); ?></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline">
                    		<?php echo CHtml::activeCheckBox($model, 'status'); ?>
                             显示
                        </label></td>
                </tr>
                <tr class="closeable">
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline">
                    		<?php echo CHtml::activeCheckBox($model, 'closeable'); ?>
                            可关闭
                        </label></td>
                </tr>
                <tr class="closeable">
                    <th style="width:130px;line-height:30px;text-align:right"></th>
                    <td><label class="checkbox inline">
                    		<?php echo CHtml::activeCheckBox($model, 'home'); ?>
                            首页
                        </label></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?php echo ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i> 保存</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <a href="<?php echo $this->createUrl('menu/index'); ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
                    </td>
                </tr>
            </table>
        <?php echo CHtml::endForm(); ?>
    </div>

    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#meun_category').cxSelect({
                selects: ['meun_grade', 'meun_parentid'],
                nodata: 'none',
                url: "<?php echo $this->createUrl('ajax/menu'); ?>"
            });
            $('.meun_grade').change(function() {
                meungrade = $(this).val();
                if (meungrade == 0) {
                    $('.action').hide();
                    $('.controller').show();
                    $('.closeable').hide();
                } else if (meungrade > 0) {
                    $('.controller').hide();
                    $('.action').hide();
                    $('.closeable').hide();
                }
            })
            $('.meun_parentid').change(function() {
                meunvar = $(this).val();
                if (meunvar == 0) {
                    $('.controller').hide();
                    $('.action').hide();
                    $('.closeable').hide();
                } else if (meunvar > 0) {
                	$('.controller').show();
                    $('.action').show();
                    $('.closeable').show();
                }
            })
        });
    </script>   
</body>
</html>