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
                                <input title="dan" type="text" name="dan" class="form-control" placeholder="开始段位">
                            </div>
                        </div>
                    </div>
                    
                                <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="danend" type="text" name="danend" class="form-control" placeholder="结束段位">
                            </div>
                        </div>
                    </div>
                            <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                            <select  name="gametype" class="form-control">
                            	<option value="1">全球-练习</option>
                                <option value="2">全球-普通</option>
                                <option value="3">全球-精英</option>
                                
                                <option value="4">其他对战</option>
                            </select>
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
                            <th>匹配时间</th>
                            <th>匹配人次</th>
                            <th></th>
                            <th></th>
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
function showdetail(time,type){
	layer.open({
		  type: 2,
		  title: '玩家详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/matchDetail?time='+time+'&type='+type
		  });
}
    var dataOption = {
        autoload: false,
        request_url:"<?php echo site_url($_request_method);?>",
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                for(var i in result['data']){
                        if(!isNaN(i))
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['matchtime']+'</td>' +
                        '<td>'+result['data'][i]['c']+'</td>' +
                        "<td><a href='javascript:showdetail("+result['data'][i]['matchtime']+",1)'>段位分布</a></td>"+
                        "<td><a href='javascript:showdetail("+result['data'][i]['matchtime']+",2)'>区服分布</a></td>"+
                        '</tr>';
                        }
                	table_html += '</tr>';
                 }
                $("#dataTable").html(table_html);
                //gettower(1);
            }
    }
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
