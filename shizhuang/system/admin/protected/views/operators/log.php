<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
        <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
				 <tr>
                        <td>
                        	<?=CHtml::dropDownList('logName', $logName, $logList, array('required'=>'required'))?>                      
                          	<input type="text" placeholder="生成时间" name="date" required="required" value="<?=isset($_POST['date']) ?$_POST['date'] : '';?>" class="form_datetime input-medium" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})">               
                            <button type="submit" class="btn btn-primary input-small" title="查询会员"><i class="icon-search"></i> 搜索</button>
                       	</td>
                    </tr>
            </table>
        	<?=CHtml::endForm(); ?>
        	<?php if(empty($info)){ ?>
			<div class="alert alert-block">
			    <button type="button" class="close" data-dismiss="alert">&times;</button>
			    <h4>Warning!</h4>
			    暂时没有相关数据
			</div>
			<?php }else { ?>	
			<p><?=$info?></p>
			<?php } ?>
    </div>
</body>
</html>