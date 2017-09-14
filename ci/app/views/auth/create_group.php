<section id="content">
      <div class="container">
            <div class="block-header">
                  <h1><?php echo lang('create_group_heading');?></h1>
            </div>
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-header">
                              <h2><?php echo lang('create_group_subheading');?></h2>
                        </div>
                        <div class="card-body card-padding">
                              <div class="row">
                                    <div id="infoMessage"><?php echo $message;?></div>
                                    <div class="col-lg-10">
                                          <?php echo form_open("auth/create_group",array('role'=>'form'));?>
                                          <div class="form-group">
                                                <label><?php echo lang('create_group_name_label', 'group_name');?></label>
                                                <?php echo form_input($group_name, '', array('class'=>'form-control'));?>
                                          </div>
                                          <div class="form-group">
                                                <label><?php echo lang('create_group_desc_label', 'description');?></label>
                                                <?php echo form_input($description, '', array('class'=>'form-control'));?>
                                          </div>
                                          <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-effect">
                                                      <?php echo lang('create_group_submit_btn');?>
                                                </button>
                                                </div>

                                          <?php echo form_close();?>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>


