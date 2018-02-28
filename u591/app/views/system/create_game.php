<div class="row">
    <div id="infoMessage"><?php echo validation_errors(); ?></div>
    <div class="col-lg-6">
        <?php echo form_open("system/create_game",array('role'=>'form'));?>
        <div class="form-group">
            <label>游戏名称</label>
            <?php echo form_input($game_name, '', array('class'=>'form-control'));?>
        </div>
        <?php echo form_submit('submit', '保存', array('class'=>'btn btn-default'));?>
        <?php echo form_close();?>
    </div>
</div>