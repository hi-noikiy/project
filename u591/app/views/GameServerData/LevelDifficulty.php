<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>选择查询条件<small></small></h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <form id="search_form" method="get" action="">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="fg-line">
                                            <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询开始时间">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="fg-line">
                                            <input  title="查询结束时间" type="text" name="date2" value="<?php echo $et?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询结束时间">
                                        </div>
                                    </div>
                                </div>
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
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" name="copy_type">
                                            <option value="0">副本类型</option>
                                            <option value="1" selected>普通副本</option>
                                            <option value="2">精英副本</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="fg-line">
                                            <input  title="VIP等级" type="text" name="viplev_min" class="form-control" placeholder="VIP等级区间(最小等级)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="fg-line">
                                            <input  title="VIP等级(最大vip等级)" type="text" name="viplev_max" class="form-control" placeholder="VIP等级区间(最大等级)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead id="thead">
                            <tr>
                                <th>普通关卡</th>
                                <th>参与人数</th>
                                <th>3星通关人数
                                <th>首次3星通关</th>
                                <th>首次2星通关</th>
                                <th>首次1星通关</th>
                                <th>首次挑战失败</th>
                                <th>平均失败次数</th>
                                <th>挑战至3星次数</th>
                                <th>非扫荡挑战玩家等级</th>
                                <th>非扫荡挑战玩家战力</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script>
    var dataOption = {
        autoload: false,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                for (var level_id in result['data']) {
                    if (!result['data'].hasOwnProperty(level_id)) continue;
                    table_html += '<tr>' +
                        '<td>'+level_id+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('total_user') ? result['data'][level_id]['total_user'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('3star_pass') ? result['data'][level_id]['3star_pass'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('3star_first_pass') ? result['data'][level_id]['3star_first_pass'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('2star_first_pass') ? result['data'][level_id]['2star_first_pass'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('1star_first_pass') ? result['data'][level_id]['1star_first_pass'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('star_first_fail') ? result['data'][level_id]['star_first_fail'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('avg_fail_times') ? result['data'][level_id]['avg_fail_times'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('3star_pass_times') ? result['data'][level_id]['3star_pass_times'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('level') ? result['data'][level_id]['level'] : 0)+'</td>'+
                        '<td>'+(result['data'][level_id].hasOwnProperty('fighting') ? result['data'][level_id]['fighting'] : 0)+'</td>'+
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
