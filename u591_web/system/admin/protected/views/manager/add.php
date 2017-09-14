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
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'login_name'); ?></th>     
                    <td><?=CHtml::activeTextField($model, 'login_name',array('placeholder'=>'输入账号', 'class'=> 'input-xlarge', 'required'=>'required')); ?></tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'login_pass'); ?></th>
                    <td><?=CHtml::activePasswordField($model, 'login_pass',array('placeholder'=>'输入密码', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
                <tr class="action">
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'nickname'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'nickname',array('placeholder'=>'输入姓名', 'class'=> 'input-xlarge',  'required'=>'required')); ?></td>
                </tr>
              
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'status'); ?></th>
                    <td><?=CHtml::activeDropDownList($model,'status',array(0=>'否', 1=>'是')); ?></td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'level'); ?></th>
                    <td><?=CHtml::activeDropDownList($model,'level', $levelInfo); ?></td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'game_id'); ?></th>
                    <td>
                        <?=CHtml::activeDropDownList($model,'game_id', $game, array('class'=>'input-medium','id'=>'gameId')); ?>
                        <select class="input-medium" id="serverId" name="Manager[server_id]">
                            <option value="0" selected="selected">区服</option>
                        </select>
                        &nbsp;<font color="red">渠道对应的区服。比如应用宝对应3开头。</font>
                    </td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'channel_id'); ?></th>
                    <td><?=CHtml::activeDropDownList($model,'channel_id', $channelInfo); ?></td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right"><?=CHtml::activeLabel($model, 'dwFenbao'); ?></th>
                    <td><?=CHtml::activeTextField($model, 'dwFenbao',array('placeholder'=>'输入分包ID，多个逗号隔开.默认不需要.', 'class'=> 'input-xlarge',  'value'=>'')); ?></td>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var $gameId = $("#gameId");
            var $serverId = $("#serverId");
            $gameId.change(function(){
                var val = $(this).val();
                if(val !=0 && val != ''){
                    $.ajax({
                        type: 'POST',
                        url: "<?=$this->createUrl('ajax/getServerPre')?>",
                        data: {gameId: val},
                        dataType: 'html',
                        success: function($data){
                            $serverId.html($data);
                        },
                    });
                } else {
                    $serverId.html('<option value="0">区服</option>');
                }

            });
        });
    </script>
</body>
</html>