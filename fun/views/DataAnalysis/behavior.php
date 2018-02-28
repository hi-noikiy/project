<style>
.cur{
cursor: pointer;
}
.curs{
color: red
}
</style>
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
                            
                            
                                         <div class="col-sm-3">
                        <div class="form-group">
                            <select name="type_id"  id="type_id" data-name="type_id" class="form-control">
                                <option value="0">选择类型</option>
                                <?php foreach($type_list as $type_id=>$type_name):?>
                                    <option value="<?php echo $type_id?>"> <?php echo $type_name;?></option>
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
                        <thead>
                        <tr>
                    
                            <th class='cur'><span id='3'>日期</span></th>
                             <th>统计类型 </th>
                            <th>参与玩家数量</th>
                            <th>消耗金钱总数</th>
                            <th>消耗钻石总数</th>
                            <th>消耗体力总数</th>
                            <th>获得金钱总数</th>
                            <th>获得钻石总数</th>
                            <th>获得体力总数</th>
                            <th> </th>
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
var name;
function vipdetail(type){
	if(type==1){
		name = '消耗';
		}else{
			name = '获取';
			}
	layer.open({
		  type: 2,
		  title: '钻石'+name+'详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/vipDetail?type='+type
		  });
}
function showdetail(logdate,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/ActionDetailMore?logdate='+logdate+'&show='+where
		  });
}
function actiondetail(actid){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/niuDetail?actid='+actid
		  });
}
	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('DataAnalysis/behavior');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                   
                        '<td>'+result['data'][i]['logdate']+'</td>' +
                        '<td>商店购买</td>' +
                        '<td>'+result['data'][i]['caccountid']+'</td>' +
                        '<td>'+result['data'][i]['scmoney']+'</td>' +
                        '<td>'+result['data'][i]['scemoney']+'</td>' +
                        '<td>'+result['data'][i]['sctired']+'</td>' +
                        '<td>'+result['data'][i]['sgmoney']+'</td>' +
                        '<td>'+result['data'][i]['sgemoney']+'</td>' +
                        '<td>'+result['data'][i]['sgtired']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
