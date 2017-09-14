<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>副本记录查询<small></small></h2>
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
                                    <input  title="副本ID" type="text" name="item_type"
                                            class="form-control" placeholder="副本ID(开始)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="fg-line">
                                    <input  title="副本ID" type="text" name="item_type2"
                                            class="form-control" placeholder="副本ID(结束)">
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
                            <th>副本类型</th>
                            <th>副本ID</th>
                            <th>副本名称</th>
                            <th>被挑战次数</th>
                            <th>成功次数</th>
                            <th>失败次数</th>
                            <th>玩家失败/通关等级</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        <a href="" ></a>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var copy_type_title = {
        1:"主线",2:"精英",3:"噩梦",4:"无尽",
        5:"金币副本",6:"经验副本",7:"穿越",8:"团队",9:"",
        10:"穿越火线"
    };
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemAnalysis/getCopy');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['sday']+'</td>' +
                        '<td>'+'['+result['data'][i]['copy_type']+']'+copy_type_title[result['data'][i]['copy_type']]+'</td>' +
                        '<td>'+result['data'][i]['copy_id']+'</td>' +
                        '<td>'+result['data'][i]['copy_title']+'</td>' +
                        '<td>'+result['data'][i]['total_times']+'</td>' +
                        '<td>'+result['data'][i]['success_times']+'</td>' +
                        '<td>'+result['data'][i]['fail_times']+'</td>' +
                        '<td><a target="_blank" href="<?php echo site_url('SystemAnalysis/view_copy_lev/')?>/?is_success=0&type='+result['data'][i]['copy_type']+'&title='+result['data'][i]['copy_title']+'">失败等级</a>|' +
                        '<a target="_blank" href="<?php echo site_url('SystemAnalysis/view_copy_lev/')?>/?is_success=1&type='+result['data'][i]['copy_type']+'&title='+result['data'][i]['copy_title']+'">通关等级</a></td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/system_analysis.js"></script>