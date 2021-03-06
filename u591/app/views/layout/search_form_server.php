<div class="card-header">
    <h2>选择查询条件<small></small></h2>
    <!--<h2><small>输入玩家等级查询</small></h2>-->
</div>
<div class="card-body card-padding">
    <div class="row">
        <form id="search_form" method="get" action="">
        <input type="hidden" name='searchtype' value="1" id='searchtype'>
            <div class="row">
            <?php if($register_time):?>
                <div class="col-sm-2">
                注册开始时间：
                    <div class="form-group">
                        <div class="fg-line">
                           <input title="注册开始时间" type="text" name="reg1" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="注册开始时间">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                注册结束时间：
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="注册结束时间" type="text" name="reg2" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="注册结束时间">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            <?php if(!isset($hide_start_time) || $hide_start_time!==true):?>
                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="fg-line">
                            <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询开始时间">
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <?php if(!isset($hide_end_time) || $hide_end_time!==true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="查询结束时间" type="text" name="date2" value="<?php echo $et?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询结束时间">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                
               <?php if(!isset($hide_server_date) || $hide_server_date!==true):?>
            <div class="col-sm-2">
                <div class="form-group">
                    <div class="fg-line">
                        <input  title="开服开始时间" type="text" name="date3" value="<?php echo $btserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服开始时间">
                    </div>
                </div>
            </div>
            <?php endif;?>
            
            
             <?php if(!isset($hide_server_date) || $hide_server_date!==true):?>
            <div class="col-sm-2">
                <div class="form-group">
                    <div class="fg-line">
                        <input  title="开服结束时间" type="text" name="date4" value="<?php echo $etserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服结束时间">
                    </div>
                </div>
            </div>
            <?php endif;?>
                
                <?php if(!isset($hide_server_list) || $hide_server_list!==true):?>
                <?php if(!empty($big_server_list)):?>
                <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select  id="servertype" class="form-control">
                                <option value="0">未选择</option>
                                <?php foreach($big_server_list as $k=>$v){ ?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
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
                                    <option value="<?php echo $channel_id?>" <?php echo count($channel_list)==1 ? 'selected' : ''?>>
                                        <?php echo $channel_name;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                <?php endif;?>
                <?php if($hide_type_list):?>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select multiple='multiple' id="type_id_mul" data-name="type_id" class="form-control mul">
                                <option value="0">选择类型</option>
                                <?php foreach($type_list as $type_id=>$type_name):?>
                                    <option value="<?php echo $type_id?>"> <?php echo $type_name;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($limit_filter) && $limit_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="limit" type="text" name="limit" class="form-control" placeholder="显示个数">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($btype_filter) && $btype_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select   name="btype" class="form-control" id='btype'>
                                <option value="1" selected="selected">全球对战</option>
                                <option value="4" >冠军之夜</option>
                            </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>
            <div class="row">
                <!--输出其它查下条件-->
                <?php echo (isset($other_search_param) ? $other_search_param : '')?>
                <?php if(isset($viplev_filter) && $viplev_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="VIP等级" type="text" name="viplev_min" class="form-control" placeholder="VIP等级:0">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="VIP等级" type="text" name="viplev_max" class="form-control" placeholder="VIP等级:0">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($account_id_filter) && $account_id_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="accountid" type="text" name="accountid" class="form-control" placeholder="账号ID">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($item_id_filter) && $item_id_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="itemid" type="text" name="itemid" class="form-control" placeholder="道具ID">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($user_id_filter) && $user_id_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="userid" type="text" name="userid" class="form-control" placeholder="UserID">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($user_name_filter) && $user_name_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="userid" type="text" name="username" class="form-control" placeholder="角色名称">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($mac_filter) && $mac_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="userid" type="text" name="mac" class="form-control" placeholder="mac地址">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($eudemon_filter) && $eudemon_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="eudemon" type="text" name="eudemon" class="form-control" placeholder="精灵编号">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($dan_filter) && $dan_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="dan" type="text" name="dan" class="form-control" placeholder="段位">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($tem_filter) && $tem_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="template_id" type="text" name="template_id" class="form-control" placeholder="层数">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($client_filter) && $client_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="client_version" type="text" name="client_version" class="form-control" placeholder="客户端版本">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                
                <?php if(isset($game_filter) && $game_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select  name="gametype" class="form-control" id='gametype'>
                            <option value="-1">请选择比赛类型</option>
                                <option value="0">普通</option>
                                <option value="1">练习</option>
                                <option value="2">天梯普通</option>
                                <option value="3">天梯神兽</option>
                                <option value="4">排位</option>
                            </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if(isset($estatus_filter) && $estatus_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select multiple='multiple'   name="estatus[]" class="form-control mul" >
                                <option value="0" selected="selected">死亡</option>
                                <option value="1" selected="selected">存活</option>
                                <option value="2" selected="selected">未出场</option>
                            </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <div class="col-sm-2">
                    <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                </div>
            </div>

        </form>
</div>
</div>