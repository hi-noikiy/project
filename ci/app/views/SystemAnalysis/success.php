<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>成就进度查询<small>每个玩家等级，对应的成就进度分布</small></h2>
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
                            <th>成就类型</th>
                            <th>成就ID</th>
                            <th>成就名称</th>
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
    var success_type = [
        '',
        '强化武器',
        '强化防具',
        '添加好友',
        '竞技场次数',
        '招募小伙伴个数',
        '基因研究次数',
        '合成宝藏次数',
        '武器防具升阶',
        '宝藏合成',
        '镶嵌水晶',
        '通关副本',
        '获得战机和副官数量'
    ];
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemAnalysis/getSuccess');?>',
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
                        '<td>'+'['+result['data'][i]['success_type']+']'+success_type[result['data'][i]['success_type']]+'</td>' +
                        '<td>'+result['data'][i]['success_id']+'</td>' +
                        '<td>'+result['data'][i]['highest_success']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/system_analysis.js"></script>