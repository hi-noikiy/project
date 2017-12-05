<div class="pageContent">

		<?php echo CHtml::beginForm('','post',array(
				'class'=>'pageForm required-validate',
				'onsubmit'=>'return validateCallback(this, dialogAjaxDone)'
				));
        ?>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<?php echo CHtml::activeLabel($model, 'password'); ?>
				<?php echo CHtml::activePasswordField($model, 'password',array('class'=>'required')); ?>
			</div>
			
			<div class="unit">
                <?php echo CHtml::activeLabel($model, 'repassword'); ?>
                <?php echo CHtml::activePasswordField($model, 'repassword',array('class'=>'required')); ?> 
			</div>
		</div>
		
		
		<div class="formBar">
			<ul>
				<li>
				  <div class="buttonActive">
					 <div class="buttonContent">
					  <?php echo CHtml::htmlButton('保存',array('type'=>'submit')); ?>
					 </div>
				  </div>
				</li>
				<li>
				  <div class="button">
				    <div class="buttonContent">
				      <?php echo CHtml::htmlButton('取消',array('class'=>'close')); ?>
				    </div>
				  </div>
				</li>
			</ul>
		</div>
   <?php echo CHtml::endForm(); ?>
</div>