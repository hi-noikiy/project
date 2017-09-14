<div class="card-header">
    <h2>选择查询条件<small></small></h2>
    <!--<h2><small>输入玩家等级查询</small></h2>-->
</div>
<div class="card-body card-padding">
    <div class="row">
        <form id="search_form" method="get" action="">
            <div class="col-sm-2">
        <div class="form-group">
            <div class="fg-line">
                <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询开始时间">
            </div>
        </div>
    </div>
            <?php if(!isset($hide_end_time) || $hide_end_time!==true):?>
            <div class="col-sm-2">
                <div class="form-group">
                    <div class="fg-line">
                        <input  title="查询结束时间" type="text" name="date2" value="<?php echo $et?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询结束时间">
                    </div>
                </div>
            </div>
            <?php endif;?>            
            <?php if(!isset($hide_server_list) || $hide_server_list!==true):?>
            <div class="col-sm-3">
                <div class="form-group">
                    <select multiple='multiple' id="server_id_mul" data-name="server_id" class="form-control mul">
                        <option value="0">选择区服</option>
                        <?php foreach($server_list as $server_id=>$server_name):?>
                        <option value="<?php echo $server_id?>"> <?php echo $server_name;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <?php endif;?>
            <?php if(!isset($hide_channel_list) || $hide_channel_list!==true):?>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select multiple='multiple' id="channel_id_mul" data-name="channel_id" class="mul">
                            <option value="0">选择渠道</option>
                            <?php foreach($channel_list as $channel_id=>$channel_name):?>
                                <option value="<?php echo $channel_id?>"> <?php echo $channel_name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            <?php endif;?>
            <div class="col-sm-2">
                <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
            </div>
        </form>
</div>
</div>