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
                                        <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询开始时间">
                                    </div>
                                </div>
                            </div>
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
                            <select  name="processtype" class="form-control">
                                <option value="1">普通副本</option>
                                <option value="2">精英副本</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select  name="developtype" class="form-control">
                                <option value="1">图鉴养成</option>
                                <option value="2">亲密度养成</option>
                                <option value="3">努力值养成</option>
                                <option value="4">个体值养成</option>
                            </select>
                            </div>
                        </div>
                    </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <div class="fg-line">
                                        <input  title="赛季" type="text" name="season" class="form-control" placeholder="赛季">
                                    </div>
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
                        </form>
                    </div>
                </div>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead id="thead">
                            <tr>
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
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>
    var dataOption = {
        autoload: false,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                var title = "<tr><th>段位</th><th>精灵数量</th><th>保底养成</th><th>平均等级</th>";
                for(var i in result.title){
                	if (!result['title'].hasOwnProperty(i)) continue;
                	title+="<th>"+result.title[i]+"级</th>";
                }
                title+="</tr>";
            	$('#thead').html(title);
                var table_html = '';
                for(var i in result.three){
                	if (!result['three'].hasOwnProperty(i)) continue;
                	table_html += '<tr><td>'+result.three[i]['ranklev']+'</td><td>'+result.three[i]['sum']+'</td><td>'+result.three[i]['avg_intilv']+'</td><td>'+result.three[i]['ave']+'</td>';
                	for(var j in result.title){
                		if (!result['title'].hasOwnProperty(j)) continue;
                		table_html += '<td>'+result.data[i][result.title[j]]+'</td>';
                    }
                	table_html += "</tr>";
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_data.js"></script>
