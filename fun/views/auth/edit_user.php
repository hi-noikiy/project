<section id="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>编辑用户信息</h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <div id="infoMessage"><?php echo $message;?></div>
                        <div class="col-lg-10">
                        <?php echo form_open(uri_string(),array('role'=>'form'));?>
                        <div class="form-group">
                            <label><?php echo lang('edit_user_lname_label', 'last_name');?></label>
                            <?php echo form_input($last_name, '', array('class'=>'form-control'));?>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('edit_user_fname_label', 'first_name');?></label>
                            <?php echo form_input($first_name, '', array('class'=>'form-control'));?>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('edit_user_phone_label', 'phone');?></label>
                            <?php echo form_input($phone, '', array('class'=>'form-control'));?>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('edit_user_password_label', 'password_confirm');?></label>
                            <?php echo form_input($password, '', array('class'=>'form-control'));?>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('edit_user_password_confirm_label', 'password');?></label>
                            <?php echo form_input($password_confirm, '', array('class'=>'form-control'));?>
                        </div>
                        <?php if ($this->ion_auth->is_admin()): ?>
                            <div class="form-group">
                                <label><?php echo lang('edit_user_groups_heading');?></label>
                                <?php foreach ($groups as $group):?>
                                    <?php
                                    $gID=$group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach($currentGroups as $grp) {
                                        if ($gID == $grp->id) {
                                            $checked= ' checked="checked"';
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="radio" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                                            <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                                        </label>
                                    </div>

                                <?php endforeach?>
                            </div>

                        <?php endif ?>

                        <?php echo form_hidden('id', $user->id);?>
                        <?php echo form_hidden($csrf); ?>
                        <button type="submit" class="btn btn-primary waves-effect"> <?php echo lang('edit_user_submit_btn');?></button>
                        <?php echo form_close();?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
