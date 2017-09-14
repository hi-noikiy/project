<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>关卡进度查询<small>每个玩家等级，对应的关卡进度分布</small></h2>
                    <!--<h2><small>输入玩家等级查询</small></h2>-->
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="fg-line">
                                    <input title="玩家等级" type="number" name="item_type"
                                           class="form-control" placeholder="玩家等级,默认0">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="fg-line">
                                    <input title="玩家等级" type="number" name="item_type2"
                                           class="form-control" placeholder="玩家等级,默认10">
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
                            <!--<th data-column-id="id" data-type="numeric">日期</th>-->
                            <th>会员等级</th>
                            <!--<th>VIP等级</th>-->
                            <th>关卡类型</th>
                            <th>关卡ID</th>
                            <th>关卡名称</th>
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
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemAnalysis/getLevel');?>',
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
                        '<td>'+result['data'][i]['lev']+'</td>' +
                        '<td>'+result['data'][i]['level_type']+'</td>' +
                        '<td>'+result['data'][i]['level_id']+'</td>' +
                        '<td>'+result['data'][i]['highest_level']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/system_analysis.js"></script>