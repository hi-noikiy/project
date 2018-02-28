<section id="content">
	<div class="container">
		<div class="block-header">
			<h2><?php echo $page_title;?></h2>
		</div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h2>
						选择查询条件<small></small>
					</h2>
				</div>
				<div class="card-body card-padding">
					<div class="row">
						<form id="search_form" method="get" action="">
							<div class="col-sm-2">
								<div class="form-group">
									<div class="fg-line">
										<input title="查询开始时间" type="text" name="date1"
											value="<?php echo $bt?>"
											class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
											placeholder="查询开始时间">
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<div class="fg-line">
										<select id="servertype" class="form-control">
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
									<select name="server_id" multiple='multiple' id="server_id_mul"
										data-name="server_id" class="form-control mul">
										<option value="0">选择区服</option>
                                        <?php foreach($server_list as $server_id=>$server_name):?>
                                            <option
											value="<?php echo $server_id?>"> <?php echo $server_name;?></option>
                                        <?php endforeach;?>
                                    </select>
								</div>
							</div>



							<div class="col-sm-4" style="margin-left: 20px;">
								<div class="form-group">
									<select name="vip" id="vip" data-name="vip"
										class="form-control mul">
										<option value="">选择vip</option>

										<option value="0">vip0</option>
										<option value="1">vip1</option>
										<option value="2">vip2</option>
										<option value="3">vip3</option>
										<option value="4">vip4</option>
										<option value="5">vip5</option>
										<option value="6">vip6</option>
										<option value="7">vip7</option>
										<option value="8">vip8</option>
										<option value="9">vip9</option>
										<option value="10">vip10</option>
										<option value="11">vip11</option>
										<option value="12">vip12</option>

									</select>
								</div>
							</div>



							<div class="col-sm-2">
								<button type="button" id="submit"
									class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
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
								<th>项目</th>
								<th>数量</th>

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
           
                	table_html += '<tr>'+
                	 '<td>每天完成1~19次挑战的玩家人数:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['a']+'</td>'+        	
                	
                	 '</tr>'+
                	' <tr>'+
                	   '<td>每天完成20~29次挑战的玩家人数:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['b']+'</td>'+                	
                	 '</tr>'+                	 
                	   	 '</tr>'+
                	' <tr>'+
                	 '<td>每天完成30~40次挑战的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['c']+'</td>'+	
                	 '</tr>'+                	 

                   	 '</tr>'+
                 	' <tr>'+
                 	 '<td>每天完成40次以上挑战的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['d']+'</td>'+	
                 	 '</tr>'+
                 	'</tr><td></td></tr>'+
                 	 
                   	 '</tr>'+
                 	' <tr>'+
                 	 '<td>每天完成霸主精灵击杀的总次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_total']+'</td>'+	
                 	 '</tr>'+     	 
                 	 '</tr>'+
                  	' <tr>'+
                  	 '<td>每天火焰鸟被挑战成功次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_10001']+'</td>'+	
                  	 '</tr>'+
                   	 '</tr>'+
                   	' <tr>'+
                   	 '<td>每天急冻鸟被挑战成功次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_10002']+'</td>'+	
                   	 '</tr>'+
                   	 
                 	 '</tr>'+
                    	' <tr>'+
                    	 '<td>每天雷吉洛克被挑战成功次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_10003']+'</td>'+	
                    	 '</tr>'+

                       	 '</tr>'+
                     	' <tr>'+
                     	 '<td>每天雷吉艾斯被挑战成功次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_10004']+'</td>'+	
                     	 '</tr>'+

                   
                     	 '</tr>'+
                      	' <tr>'+
                    	 '<td>每天雷吉斯奇鲁被挑战成功次数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['param_10005']+'</td>'+	
                      	 '</tr>'+

       	 
                     	'</tr><td></td></tr>'+                     	 
                     	 '</tr>'+
                      	' <tr>'+
                      	 '<td>每天使用扫荡的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num_total']+'</td>'+	
                      	 '</tr>'+

                     	 '</tr>'+
                      	' <tr>'+
                      	 '<td>每天使用扫荡20次以上的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num20']+'</td>'+	
                      	 '</tr>'+

                     	 '</tr>'+
                      	' <tr>'+
                      	 '<td>	每天使用扫荡40次以上的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num40']+'</td>'+	
                      	 '</tr>'+

                    	 '</tr>'+
                       	' <tr>'+
                       	 '<td>每天使用扫荡60次以上的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num60']+'</td>'+	
                       	 '</tr>'+

                     	 '</tr>'+
                        	' <tr>'+
                        	 '<td>每天使用扫荡80次以上的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num80']+'</td>'+	
                        	 '</tr>'+

                         	 '</tr>'+
                         	' <tr>'+
                         	 '<td>每天使用扫荡100次以上的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['num100']+'</td>'+	
                         	 '</tr>'+
                         	'</tr><td></td></tr>'+              	 

                         	 '</tr>'+
                          	' <tr>'+
                          	 '<td>每天购买了玩法次数的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count_total']+'</td>'+	
                          	 '</tr>'+
                         	 '</tr>'+
                          	' <tr>'+
                          	 '<td>每天购买了玩法次数达到20次的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count20']+'</td>'+	
                          	 '</tr>'+
                         	 '</tr>'+
                          	' <tr>'+
                          	 '<td>每天购买了玩法次数达到40~59次的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count40']+'</td>'+	
                          	 '</tr>'+
                         	 '</tr>'+
                          	' <tr>'+
                          	 '<td>每天购买了玩法次数达到60~79次的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count60']+'</td>'+	
                          	 '</tr>'+
                         	 '</tr>'+
                          	' <tr>'+
                          	 '<td>每天购买了玩法次数达到80~99次的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count80']+'</td>'+	
                          	 '</tr>'+
                         	' <tr>'+
                         	 '<td>每天购买了玩法次数达到100~130次的玩家人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'+result.data['count100']+'</td>'+	
                         
                         	 '</tr>';              	 
                	 



                	
              
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
