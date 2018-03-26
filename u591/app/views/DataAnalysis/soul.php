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
                            <div class="col-sm-2" style="display:none;">
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
                            
                            
                 <div class="col-sm-3" style="display:none;">
                        <div class="form-group">
                            <select name="type_id"  id="type_id" data-name="type_id" class="form-control">
                                <option value="0">选择类型</option>
                                <?php foreach($type_list as $type_id=>$type_name):?>
                                    <option value="<?php echo $type_id?>"> <?php echo $type_name;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                            
                            
                            
                                          <?php if(isset($level_vip) && $level_vip===true):?>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <select   name="level_vip" class="form-control" id='btype'>
                                    <option value="1" selected="selected">vip</option>
                                    <option value="2" >玩家等级</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                            
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
                           
                           <!--  <th class='cur'><span id='3'>vip等级</span></th> -->
                            <!--  <th>活跃人数 </th> -->
                            <th> 等级</th>
                            <th>已装备符文精灵数</th>
                            <th>平均符文等级</th>
                            <th>橙色符文数</th>
                            <th>紫色符文数</th>
                            <th>蓝色符文数</th>
                            <th>绿色符文数</th>
                            <th>激活2件套数</th>
                            <th>激活4件套数</th>                            
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


	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('DataAnalysis/soul');?>',
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
                  //  '<td>'+result['data'][i]['viplev']+'</td>' +
                    //   '<td>'+i+'</td>' +
                    //   '<td>'+result['data'][i]['c']+'</td>' +
                       '<td>'+result['data'][i]['vip_level']+'</td>' +
                       '<td>'+result['data'][i]['s1']+'</td>' +
                       '<td>'+result['data'][i]['s2']+'</td>' +
                       '<td>'+result['data'][i]['s3']+'</td>' +
                       '<td>'+result['data'][i]['s4']+'</td>' +
                       '<td>'+result['data'][i]['s5']+'</td>' +
                       '<td>'+result['data'][i]['s6']+'</td>' +
                       '<td>'+result['data'][i]['s7']+'</td>' +
                       '<td>'+result['data'][i]['s8']+'</td>' +
                      
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
