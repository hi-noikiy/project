<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>虚拟币统计<small></small></h2>
                    <!--<h2><small>输入玩家等级查询</small></h2>-->
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="查询开始时间" type="text" name="t1" value="<?php echo $bt?>" class="form-control date-picker" placeholder="查询开始时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="查询结束时间" type="text" name="t2" value="<?php echo $et?>" class="form-control date-picker" placeholder="查询结束时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="物品ID" type="text" name="item_type"
                                        value=""
                                        class="form-control" placeholder="物品ID">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="select">
                                <select name="server_id" class="form-control">
                                    <option value="0">选择区服</option>
                                    <?php foreach($server_list as $server_id=>$server_name):?>
                                        <option value="<?php echo $server_id?>"> <?php echo $server_name;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric">日期</th>
                            <th>物品ID</th>
                            <th>获取总数|消耗总数</th>
                            <th data-order="desc">剩余总数</th>
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
<script>
    var dataOption = {
        title:'虚拟币统计',
        autoload: true,
        request_url:'<?php echo site_url('SystemAnalysis/getEmoney');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '',
                    len = result.data.length;
                for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['sday']+'</td>' +
                        '<td>'+result['data'][i]['item_type']+'</td>' +
                        '<td>'+result['data'][i]['emoney_get']+'|' +
                        result['data'][i]['emoney_use']+'</td>' +
                        '<td>'+result['data'][i]['emoney_left']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/system_analysis.js"></script>