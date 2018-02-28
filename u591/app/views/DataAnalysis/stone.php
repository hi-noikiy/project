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
                                <input  title="查询结束时间" type="text" name="date2" value="<?php echo $et?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="查询结束时间">
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
                            
                            
                            
                <div class="col-sm-2">
                <div class="form-group">
                    <div class="fg-line">
                        <input  title="开服开始时间" type="text" name="date3" value="<?php echo $btserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服开始时间">
                    </div>
                </div>
            </div>            
           
            <div class="col-sm-2">
                <div class="form-group">
                    <div class="fg-line">
                        <input  title="开服结束时间" type="text" name="date4" value="<?php echo $etserver?>" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服结束时间">
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
                        <th>养成项目</th>
                        <th>VIP0</th>
                        <th>VIP1</th>
                        <th>VIP2</th>
                        <th>VIP3</th>
                        <th>VIP4</th>
                        <th>VIP5</th>
                        <th>VIP6</th>
                        <th>VIP7</th>
                        <th>VIP8</th>
                        <th>VIP9</th>
                        <th>VIP10</th>
                        <th>VIP11</th>
                        <th>VIP12</th>
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
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                
                var table_html = '';
                for(var j in result.data){
                	if (!result['data'].hasOwnProperty(j)) continue;
                	table_html += '<tr><td>'+result.types[j]+'-HP</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['hp'][i]+'</td>';
                    }
                	table_html += '</tr>';
                	table_html += '<tr><td>'+result.types[j]+'-攻击</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['attack_p'][i]+'</td>';
                    }
                	table_html += '</tr>';
                	table_html += '<tr><td>'+result.types[j]+'-防御</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['defense_p'][i]+'</td>';
                    }
                	table_html += '</tr>';
                	table_html += '<tr><td>'+result.types[j]+'-特攻</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['attack_s'][i]+'</td>';
                    }
                	table_html += '</tr>';
                	table_html += '<tr><td>'+result.types[j]+'-特防</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['defense_s'][i]+'</td>';
                    }
                	table_html += '</tr>';
                	table_html += '<tr><td>'+result.types[j]+'-速度</td>';
                	for(var i=0;i<=12;i++){
                		table_html += '<td>'+result.data[j]['speed'][i]+'</td>';
                    }
                	table_html += '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
