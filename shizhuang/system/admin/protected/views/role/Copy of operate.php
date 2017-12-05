<?php 
  if(!empty($_GET['appId']) && !empty($_GET['moduleId'])){
?>
<?php foreach ($operate as $k => $v){ ?>
<div class="unit">
	<input type="checkbox" name="groupActionId[]" <?php echo $operate[$k]['checked']; ?> value="<?php echo $operate[$k]['id']; ?>"/><?php echo $operate[$k]['title']; ?>
</div>
<?php } ?>
<?php }elseif(!empty($_GET['appId'])){ ?>

	<div class="unit">
		<label>Module:</label>
		<select name="moduleId" onchange="selectModule_action('#setActionAction')">
			<option value="">Choose</option>
			<?php foreach ($model as $k => $v) { ?>
			<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
			<?php } ?>
			</select>
	</div>

    <div id="actionSelectBox">	
		<div class="unit"></div>
    </div>

<?php }else { ?>
	<form id="setActionAction" method="post" action="<?php echo $this->createUrl('role/setOperate'); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
		<input type="hidden" name="groupId" VALUE="<?php echo $_GET['id']; ?>" />
		<div class="pageFormContent" layoutH="100" layoutType="dialog">
			<div class="unit">
				<label>Applicaion:</label>
				<select name="appId" onchange="selectApp_action('#setActionAction')">
					<option value="">Choose</option>
					<?php foreach ($app as $k => $v) { ?>
					<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
					<?php } ?>
				</select>
			</div>
			<div id="moduleSelect">
				<!-- *** -->
				   <div class="unit">
						<label>Module:</label>
						<select name="moduleId" onchange="selectModule_action('#setActionAction')">
							<option value="0">Choose</option>
							<?php 
						     foreach ($model as $k => $v) { 
                            ?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php }?>
						</select>
				   </div>

				   <div id="actionSelectBox">
						<div class="unit"></div>
				   </div>
				<!-- *** -->
			</div>
		</div>
		<div class="formBar">
			<label style="float:left"><input type="checkbox" class="checkboxCtrl" group="groupActionId[]" />全选</label>
			<ul>
				<li><div class="button"><div class="buttonContent"><button type="button" class="checkboxCtrl" group="groupActionId[]" selectType="invert">反选</button></div></div></li>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" onclick="$.pdialog.closeCurrent()">取消</button></div></div></li>
			</ul>
		</div>
	</form>
<?php } ?>
