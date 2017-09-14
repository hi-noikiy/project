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
                        <th>vip等级</th>
                        <th>活跃人数</th>
                        <th>参与购买人数</th>
                        <th>购买一次喂食只数</th>
                        <th>购买两次喂食只数</th>
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
                	if(isNaN(result.data[j]['caccount'])){
                		result.data[j]['caccount'] = 0;
                    }
                	if(isNaN(result.data[j]['baccount'])){
                		result.data[j]['baccount'] = 0;
                    }
                	if(isNaN(result.data[j]['c1'])){
                		result.data[j]['c1'] = 0;
                    }
                	if(isNaN(result.data[j]['c2'])){
                		result.data[j]['c2'] = 0;
                    }
                	table_html += '<tr><td>'+j+'</td>'+
                	 '<td>'+result.data[j]['caccount']+'</td>'+
                	 '<td>'+result.data[j]['baccount']+'</td>'+
                	 '<td>'+result.data[j]['c1']+'</td>'+
                	 '<td>'+result.data[j]['c2']+'</td>'+
                	 '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
