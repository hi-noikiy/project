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
                        <input type="text" name="userid" value=""   placeholder="请输入userid">                         
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
                        <thead>
                        <tr>
                        <th>用户名</th>
                             <th>userid</th>
                            <th>区服</th>
                            <th>ip</th>
                            <th>登陆时间</th>
                        
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
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>
    var dataOption = {
        title:'',
        autoload: false,
        request_url:"<?php echo site_url('SystemAnalysis/getUserInfo');?>",
        callback: function (result) {
            if (result) {
                //console.log(result.data);

                var table_html = '';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i))
                	table_html += '<tr>' +
                	 '<td>'+result['data'][i]['username']+'</td>' +
                	 '<td>'+result['data'][i]['userid']+'</td>' +
                    '<td>'+result['data'][i]['serverid']+'</td>' +
                    '<td>'+result['data'][i]['last_login_ip']+'</td>' +
                    '<td>'+result['data'][i]['last_login_time']+'</td>' +
                  
                     '</tr>';
                    }
               
                $("#dataTable").html(table_html);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
