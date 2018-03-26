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


                <?php if(isset($show_start_time_month) || $show_start_time_month==true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker-month'?>" placeholder="查询开始时间">
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
                    
           
                    
                    
                          <div class="col-sm-2">                   
                        <div class="form-group">
                            <div class="fg-line">
                 <input title="注册开始时间" type="text" name="server_start" id="server_start" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none;">
                                       开服结束时间：：
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="注册结束时间" type="text" name="server_end" value="" id="server_end" onchange="serverendfunction()"  class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服结束时间">
                            </div>
                        </div>
                    </div>
                    
                    
                <?php endif;?>
                
                
                
          


                <?php if(isset($show_server_date) || $show_server_date==true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="开服开始时间" type="text" name="date3" value="<?php echo $btserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服开始时间">
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="开服结束时间" type="text" name="date4" value="<?php echo $etserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服结束时间">
                            </div>
                        </div>
                    </div>
                <?php endif;?>





                <?php if(!isset($hide_channel_list) || $hide_channel_list!==true):?>
                    <div class="col-sm-3" <?php echo count($channel_list)==1 ? 'style="display:none"' : ''?>>
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
                <?php if(isset($click_type) && $click_type===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select name="click_type"  id="click_type" class="form-control"  data-name="click_type_one_list" >

                                    <?php foreach($click_type_one_list as $channel_id=>$channel_name):?>
                                        <option value="<?php echo $channel_id?>" <?php echo count($channel_list)==1 ? 'selected' : ''?>>
                                            <?php echo $channel_name;?>
                                        </option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>


                <?php if(isset($click_type_two) && $click_type_two===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select name="click_type_two"  id="click_type_two" class="form-control"  data-name="click_type_two" >
                                    <option value="">游戏类型二级菜单</option>
                                    <?php foreach($click_type_list as $channel_id=>$channel_name):?>
                                        <option value="<?php echo $channel_id?>" <?php echo count($channel_list)==1 ? 'selected' : ''?>>
                                            <?php echo $channel_name;?>
                                        </option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <?php if(isset($click_type_lev) && $click_type_lev===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select name="lev_type_list"  id="lev_type_list" class="form-control"  data-name="lev_type_list" >
                                    <option value="">等级菜单</option>
                                    <?php foreach($lev_type_list as $channel_id=>$channel_name):?>
                                        <option value="<?php echo $channel_id?>" <?php echo count($channel_list)==1 ? 'selected' : ''?>>
                                            <?php echo $channel_name;?>
                                        </option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>





                <?php if(isset($play_pay) && $play_pay===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select  id="play_pay" class="form-control">
                                    <option value="0">类型筛选</option>
                                    <option value="1">玩法</option>
                                    <option value="2" >付费行为</option>
                                </select>
                            </div>
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
                <?php if(isset($lev_filter) && $lev_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="等级" type="text" name="lev_min" class="form-control" placeholder="等级:0">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="等级" type="text" name="lev_max" class="form-control" placeholder="等级:0">
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
                <?php if(isset($source_filter) && $source_filter===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="source_client" type="text" name="source_client" class="form-control" placeholder="代码版本">
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


                <?php if(isset($show_combat_type) && $show_combat_type===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select  name="combattype" class="form-control" id='combattype'>
                                    <option value="">请选择比赛类型</option>
                                    <option value="0">全球对战-练习</option>
                                    <option value="1">全球对战-普通</option>
                                    <option value="2" selected="selected">全球对战-精英</option>
                                    <option value="3">冠军之夜</option>
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





                <?php if(isset($show_dan_list) || $show_dan_list==true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div >
                                <input  title="开始段位" type="text" name="dan_s" value="1"  placeholder="开始段位">
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-2">
                        <div class="form-group">
                            <div >
                                <input  title="结束段位" type="text" name="dan_e" value="10"  placeholder="结束段位">
                            </div>
                        </div>
                    </div>
                <?php endif;?>



                <?php if(isset($show_register_start_date) || $show_register_start_date==true):?>

                    <div class="col-sm-2">

                        <div class="form-group">
                            <div class="fg-line">
                                <input title="注册开始时间" type="text" name="register_start" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="注册开始时间">
                            </div>
                        </div>
                    </div>


                <?php endif;?>

                <?php if(isset($show_user_level) || $show_user_level==true):?>

                    <div class="col-sm-2">

                        <div class="form-group">
                            <div class="fg-line">
                                <input title="等级段位" type="text" name="user_level" value=""    placeholder="等级段位">
                            </div>
                        </div>
                    </div>


                <?php endif;?>


                <?php if(isset($show_days) || $show_days==true):?>

                    <div class="col-sm-2">

                        <div class="form-group">
                            <div class="fg-line">签到天数
                                <input title="签到天数" type="text" name="days" value="10"    placeholder="签到天数">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">

                        <div class="form-group">
                            <div class="fg-line">签到天数
                                <input title="签到天数" type="text" name="days2" value="15"    placeholder="签到天数">
                            </div>
                        </div>
                    </div>
                <?php endif;?>



                <?php if(isset($show_vip_level) || $show_vip_level==true):?>

                    <div class="col-sm-2">

                        <div class="form-group">
                            <div class="fg-line">
                                <input title="vip等级" type="text" name="vip_level" value=""    placeholder="vip等级">
                            </div>
                        </div>
                    </div>
                <?php endif;?>



                <?php if(isset($show_group) && $show_group===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select  name="group" class="form-control" id='combattype'>
                                    <option value="1" selected="selected">1组</option>
                                    <option value="2">2组</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>




                <?php if(isset($show_eudemonr) && $show_eudemonr===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="eudemon" type="text" name="eudemon" class="form-control" placeholder="精灵id(必填)">
                            </div>
                        </div>
                    </div>
                <?php endif;?>


                <?php if(isset($show_syn_id) && $show_syn_id===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="dan" type="text" name="pk_th" class="form-control" placeholder="赛事编号">
                            </div>
                        </div>
                    </div>
                <?php endif;?>


                <?php if(isset($show_mac) && $show_mac===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="mac" type="text" name="mac" class="form-control" placeholder="mac">
                            </div>
                        </div>
                    </div>
                <?php endif;?>


                <?php if(isset($behavior_type) && $behavior_type==true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="behavior_type" type="text" name="behavior_type" class="form-control" placeholder="类型   默认1">
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                
                
                
                
                              <?php if(isset($level_vip) && $level_vip===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select   name="level_vip" class="form-control" id='btype'>
                                    <option value="1" selected="selected">vip</option>
                                    <option value="2" >玩家等级</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                
                
                        
                   <?php if(isset($merge_server) && $merge_server===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select   name="merge_server" class="form-control" id='btype'>
                                    <option value="0" selected="selected">未合并server</option>
                                    <option value="1" >合并server</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                
                
                
                    <?php if($server_start_time):?>
  
                <?php endif;?>
                
                
                 <?php if(isset($statistics_type) && $statistics_type===true):?>
                 
                               <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select  name="statistics_type" class="form-control" id='statistics_type'>
                                  
                                    <option value="1" selected="selected">宠物升星</option>
                                    <option value="2">宠物强化</option>
                                    <option value="3">宠物觉醒</option>
                                   
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

<script>
$('input[name="server_start"]').blur(function(){
	 var server_start=  $("input[ name='server_start']").val();
	 var server_end=  $("input[ name='server_end']").val();
	 var dataString = 'flid=2&server_start='+ server_start+'&server_end='+server_end; 
	 $.ajax ({    
	     type: "POST",    
	     url: "<?php echo site_url('SystemFunctionNew/serverStart');?>",    
	     data: dataString,    
	     cache: false,    
	     success: function(data)  {   
		    	var myarray=data.split(',');	    		
	    		var ids = [];
	    	$("#server_id_mul").parent().find('input[name="server_id[]"]').each(function(i){
	    	
	    		for(j=0;j<myarray.length;j++){
	    		
	    			if($(this).attr('value')==myarray[j]){
	    				$(this).attr('checked',true);
	    				ids.push($(this).attr('value'));
	    			}else{
	    				$(this).attr('checked',false);
	    			}
	    		}

	    		});			
	    		$("select#server_id_mul").val(ids);
	    		$("select#server_id_mul").multiselect('refresh');

	        }     
	 });     

});

</script>