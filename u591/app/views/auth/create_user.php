<section id="content">
    <div class="container">
        <!--<div class="block-header">-->
        <!--    <h2>--><?php //echo lang('create_user_subheading');?><!--</h2>-->
        <!--</div>-->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2><?php echo lang('create_user_subheading');?></h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <div id="infoMessage"><?php echo $message;?></div>
                        <div class="col-lg-10">
                            <?php echo form_open("auth/create_user",array('role'=>'form'));?>
                            <div class="form-group">
                                <label><?php echo lang('create_user_lname_label', 'last_name');?></label>
                                <?php echo form_input($last_name, '', array('class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('create_user_fname_label', 'first_name');?></label>
                                <?php echo form_input($first_name, '', array('class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('create_user_email_label', 'email');?></label>
                                <?php echo form_input($email, '', array('class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('create_user_phone_label', 'phone');?></label>
                                <?php echo form_input($phone, '', array('class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('create_user_password_label', 'password_confirm');?></label>
                                <?php echo form_input($password, '', array('class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('create_user_password_confirm_label', 'password');?></label>
                                <?php echo form_input($password_confirm, '', array('class'=>'form-control'));?>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect"> <?php echo lang('create_user_submit_btn');?></button>
                            <?php
                            if($identity_column!=='email') {
                                echo '<p>';
                                echo lang('create_user_identity_label', 'identity');
                                echo '<br />';
                                echo form_error('identity');
                                echo form_input($identity);
                                echo '</p>';
                            }
                            ?>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
