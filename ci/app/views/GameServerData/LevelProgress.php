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
                                        <input  title="流失日期" type="text" name="lostday" class="form-control" placeholder="流失日期（天）">
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
                                <div class="form-group">
                                    <div class="fg-line">
                                        <input  title="最小章节范围" type="text" name="chapter_min" class="form-control" placeholder="章节区间(最小章节)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <div class="fg-line">
                                        <input  title="最大章节范围" type="text" name="chapter_max" class="form-control" placeholder="章节区间(最大章节)">
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
function showdetail(vip_status,title){
	layer.open({
		  type: 2,
		  title: '玩家详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/processDetail?vip_status='+vip_status+'&title='+title
		  });
}
    var dataOption = {
        autoload: false,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                /*if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }*/
                var title = "<tr><th>统计项</th><th>总人数</th>";
                for(var i in result.title){
                	if (!result['title'].hasOwnProperty(i)) continue;
                	title+="<th>"+result.title[i]['title']+"</th>";
                }
                title+="</tr>";
            	$('#thead').html(title);
                var table_html = '';
                for(var i in result.data){
                	if (!result['data'].hasOwnProperty(i)) continue;
                	table_html += '<tr><td>'+result.data[i]['titlename']+'</td><td>'+result.data[i]['all']+'</td>';
                	for(var j in result.title){
                		if (!result['title'].hasOwnProperty(j)) continue;
                		 table_html += '<td>';
                		 if(isNaN(result.data[i][result.title[j]['title']])){
                			 table_html += 0;
                		 }else{
                			 table_html += result.data[i][result.title[j]['title']];
                    		 }
                		 table_html +="<br/><a href=\"javascript:showdetail('"+i+"','"+result.title[j]['title']+"')\">玩家详细</a></td>";
                    }
                	table_html += '</tr>';
                 }
                $("#dataTable").html(table_html);
                //gettower(1);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_data.js"></script>
