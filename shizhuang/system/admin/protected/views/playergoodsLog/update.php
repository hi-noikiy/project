<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
		<?=CHtml::beginForm('', 'POST', array(
				'class'=>'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',
				'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
			));?>
            <table class="table table-hover">
               	<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">角色名</th>
                    <td>
						<?=CHtml::activeDropDownList($model, 'type',array('角色名', '角色ID'), array('class'=>'input-small')); ?>
						<?=CHtml::activeTextField($model, 'name', array('placeholder'=>'多个角色名 例如(张三;李四)', 'class'=>'input-xlarge', 'required'=>'required')); ?>
					</td>
                </tr>
                
                <tr>
      				<th style="width:130px;line-height:30px;text-align:right">金币</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardmoney',array('placeholder'=>'金币', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">钻石</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardemoney',array('placeholder'=>'钻石', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">体力</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardtired',array('placeholder'=>'体力', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">玫瑰</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardrose',array('placeholder'=>'玫瑰', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">百合</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardlily',array('placeholder'=>'百合', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">水仙</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awardnarcissus',array('placeholder'=>'水仙', 'class'=>'input-medium'));?>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品1</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awarditemtype1',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
      					<span></span>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品2</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awarditemtype2',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
      					<span></span>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品3</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awarditemtype3',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
      					<span></span>
					</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">物品4</th>
      				<td>
      					<?=CHtml::activeTextField($model, 'awarditemtype4',array('placeholder'=>'物品ID', 'class'=>'input-medium itemtypeId'));?>
      					<span></span>
					</td>
      			</tr>
      			
      			<tr class="controller">
                    <th style="width:130px;line-height:30px;text-align:right">邮件类型</th>
                    <td>
						<?=CHtml::activeDropDownList($model, 'mailtype',array(0=>'任务邮件', 1=>'系统通知', 3=>'搭配评比赛'
    		, 4=>'VIP奖励', 5=>'借衣奖励', 6=>'集卡活动', 7=>'飞行活动'), array('class'=>'input-small')); ?>
					</td>
                </tr>
                <tr>
      				<th style="width:130px;line-height:30px;text-align:right">邮件主题</th>
      				<td>
						<?=CHtml::activeTextArea($model, 'mail_theme', array('style'=>'width: 300px; height: 80px;', 'required'=>true)); ?>
      				</td>
      			</tr>
      			<tr>
      				<th style="width:130px;line-height:30px;text-align:right">邮件内容</th>
      				<td>
						<?=CHtml::activeTextArea($model, 'mail_describe', array('style'=>'width: 300px; height: 80px;', 'required'=>true)); ?>
      				</td>
      			</tr>
				<tr>
					<th style="width:130px;line-height:30px;text-align:right">备注</th>
					<td>
						<?=CHtml::activeTextArea($model, 'remark', array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
					</td>
				</tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton"  class="btn btn-primary input-small"><i class="icon-save"></i> 提交</button> 
                        <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button>
						<a href="<?=$this->createUrl('playergoodsLog/index');?>" title="取消" class="btn"><i class="icon-double-angle-left"></i> 取消</a>
					</td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">	
   		$(document).ready(function() {
			//验证物品ID
			var $itemtypeId = $(".itemtypeId");
			$itemtypeId.change(function () {
				var $this = $(this);
				var val = $this.val();
				$.ajax({
					type: 'POST',
					url: "<?=$this->createUrl('ajax/checkAddGodd')?>",
					data: {itemtypeId: val},
					dataType: 'json',
					success: function($data){
						if($data.status == 1){
							$this.val("").focus();
						}
						$this.next('span').text($data.msg);
					},
				});
			});

        });
    </script>
</body>
</html>