<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend>配置信息</legend>
        </div>
		<?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px'
				));
        ?>     
            <table class="table table-hover">
            <tr>
                    <th style="width:130px;line-height:30px;text-align:right">选择充值渠道</th>
                    <td><select name="channel" class="input-medium">
                                <?php           	
                                	foreach ($channel as $k => $v) {
                                		echo "<option value='$k'>$v</option>";
                                	}
                                ?>
                            </select></td></tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">选择客户端渠道</th>
                    <td><select name="fenbao" class="input-medium">
								<option value="0">渠道</option>
								<?php
								if(isset($fenbao) && !empty($fenbao)){
									foreach ($fenbao as $k => $v) {
										$selected = (isset($_POST['channel']) && $_POST['channel'] == $k) ? 'selected' :'';
										echo "<option value='$k' $selected>$v</option>";
									}
								}
								?>
							</select></td></tr>
                <tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">年月份</th>
                    <td><input type='text' name='ydate' value="2017-01"/></td>
                </tr>
				<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">倍数</th>
                    <td><input type='text' name='rate' value="0"/></td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" class="btn btn-primary input-small"><i class="icon-save"></i>生成数据</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                        <!--<a href="<?php echo Yii::app()->request->urlReferrer; ?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>-->
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
</body>
<script></script>
</html>