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
                 <input title="注册开始时间" type="text" name="server_start" id="server_start" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none;">
                                       开服结束时间：：
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="注册结束时间" type="text" name="server_end" value="" id="server_end" onchange="serverendfunction()"  class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="开服结束时间">
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
$('input[name="server_start"]').blur(function(){
	 var server_start=  $("input[ name='server_start']").val();
	 var server_end=  $("input[ name='server_end']").val();
	 var dataString = 'flid=2&server_start='+ server_start+'&server_end='+server_end; 
	 $.ajax ({    
	     type: "POST",    
	     url: "<?php echo site_url('SystemFunctionNew/serverStart');?>",    
	     data: dataString,    
	     cache: false,    
	     success: function(data)  {   
		    	var myarray=data.split(',');	    		
	    		var ids = [];
	    	$("#server_id_mul").parent().find('input[name="server_id[]"]').each(function(i){
	    	
	    		for(j=0;j<myarray.length;j++){
	    		
	    			if($(this).attr('value')==myarray[j]){
	    				$(this).attr('checked',true);
	    				ids.push($(this).attr('value'));
	    			}else{
	    				$(this).attr('checked',false);
	    			}
	    		}

	    		});			
	    		$("select#server_id_mul").val(ids);
	    		$("select#server_id_mul").multiselect('refresh');

	        }     
	 });     

});

</script>

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
                var title = '<tr><th>vip等级</th><th>统计人数</th>';
                for(var i in result.title){
                	if(result['title'].hasOwnProperty(i)){
                		title += '<th>'+result['title'][i]+'</th>';
					}
                }
                title += '</tr>';
                $("#thead").html(title);
                var table_html = '';
                for(var j in result.data){
                	if (!result['data'].hasOwnProperty(j)) continue;
                	table_html += '<tr><td>'+j+'</td><td>'+result.data[j]['caccount']+'</td>';
                	for(var i in result.title){
                    	if(result['title'].hasOwnProperty(i)){
                    		table_html += '<td>'+result.data[j]['group'][i]+'</td>';
    					}
                    }
                	table_html += '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
