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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <div class="fg-line">
                                        <input title="查询时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询时间">
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
                                        <input  title="VIP等级(最大等级)" type="text" name="viplev_max" class="form-control" placeholder="VIP等级区间(最大等级)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
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
                                <th>项目</th>
                                <th>0-10</th>
                                <th>11-20</th>
                                <th>21-30</th>
                                <th>31-40</th>
                                <th>41-50</th>
                                <th>51-60</th>
                                <th>61-70</th>
                                <th>71-80</th>
                                <th>81-90</th>
                                <th>91-100</th>
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
        autoload: true,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                table_html += '<tr>' +
                    '<td>指定等级段人数</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(1) ? result['data']['summary'][1] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(2) ? result['data']['summary'][2] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(3) ? result['data']['summary'][3] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(4) ? result['data']['summary'][4] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(5) ? result['data']['summary'][5] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(6) ? result['data']['summary'][6] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(7) ? result['data']['summary'][7] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(8) ? result['data']['summary'][8] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(9) ? result['data']['summary'][9] : 0)+'</td>'+
                    '<td>'+(result['data']['summary'].hasOwnProperty(10) ? result['data']['summary'][10] : 0)+'</td>'+
                    '</tr>';
                var times_list = result['data']['times'];
                var time_list = result['data']['time'];
                var methods_list = result['data']['methods_list'];
                for (var method in times_list) {
                    if (!times_list.hasOwnProperty(method)) continue;
                    table_html += '<tr>' +
                        '<td>['+method+']'+methods_list[method]+'次数</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(1) ? times_list[method][1] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(2) ? times_list[method][2] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(3) ? times_list[method][3] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(4) ? times_list[method][4] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(5) ? times_list[method][5] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(6) ? times_list[method][6] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(7) ? times_list[method][7] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(8) ? times_list[method][8] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(9) ? times_list[method][9] : 0)+'</td>'+
                        '<td>'+(times_list[method].hasOwnProperty(10) ? times_list[method][10] : 0)+'</td>'+
                        '</tr>';
                }

                table_html += '<tr><td colspan="11"></td></tr>';
                for (var method in time_list) {
                    if (!times_list.hasOwnProperty(method)) continue;
                    table_html += '<tr>' +
                        '<td>['+method+']'+methods_list[method]+'回合数</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(1) ? time_list[method][1] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(2) ? time_list[method][2] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(3) ? time_list[method][3] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(4) ? time_list[method][4] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(5) ? time_list[method][5] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(6) ? time_list[method][6] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(7) ? time_list[method][7] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(8) ? time_list[method][8] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(9) ? time_list[method][9] : 0)+'</td>'+
                        '<td>'+(time_list[method].hasOwnProperty(10) ? time_list[method][10] : 0)+'</td>'+
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
