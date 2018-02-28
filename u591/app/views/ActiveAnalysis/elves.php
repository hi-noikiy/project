<section id="content">
    <div class="container">
        <div class="block-header">
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>选择查询条件<small></small></h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <form id="search_form" method="get" action="">
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
                                		<input title="limit" type="text" name="levmin" class="form-control" placeholder="最小玩家等级">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="levmax" class="form-control" placeholder="最大玩家等级">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="vipmin" class="form-control" placeholder="最小vip等级">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="vipmax" class="form-control" placeholder="最大vip等级">
                            		</div>
                        		</div>
                    		</div>
                            <div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="template_id" class="form-control" placeholder="精灵id">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="ex2min" class="form-control" placeholder="最小努力总值">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="ex2max" class="form-control" placeholder="最大努力总值">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="intimacy_levelmin" class="form-control" placeholder="最小亲密等级">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="intimacy_levelmax" class="form-control" placeholder="最大亲密等级">
                            		</div>
                        		</div>
                    		</div>
                    		<div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                            		<select  name="type" class="form-control">
                                		<option value="user_lev">玩家等级显示</option>
                                		<option value="vip_level" >vip等级显示</option>      
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
        title:'',
        autoload: false,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    notify(result.info);
                    return false;
                }
                var title = "<tr><th rowspan='2'>精灵ID</th>";
                for(var j in result.data.title){
                	if (!result['data']['title'].hasOwnProperty(j)) continue;
                	title+="<th colspan='2'>"+result['data']['title'][j]+"</th>";
                }
                title+="</tr><tr>";
                for(var j in result.data.title){
                	if (!result['data']['title'].hasOwnProperty(j)) continue;
                	title+="<th>玩家总数</th><th>持有数</th>";
                }
                title+="</tr>";
            	$('#thead').html(title);
            	 var table_html = '';
                 for(var i in result.data.data){
                 	if (!result['data']['data'].hasOwnProperty(i)) continue;
                 	table_html += '<tr><td>'+i+'</td>';
                 	for(var j in result.data.title){
                 		if (!result['data']['title'].hasOwnProperty(j)) continue;
                 		table_html += '<td>';
                 		 if(!result['data']['data'][i].hasOwnProperty('v_'+j)){
                			 table_html += 0;
                		 }else{
                			 table_html += result['data']['data'][i]['v_'+j]['cuser'];
                    	}
                 		table_html += '</td><td>';
                 		if(!result['data']['data'][i].hasOwnProperty('v_'+j)){
                			 table_html += 0;
                		 }else{
                			 table_html += result['data']['data'][i]['v_'+j]['stem'];
                    	}
                 		table_html += '</td>';
                     }
                 	table_html += '</tr>';
                  }
                 $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
