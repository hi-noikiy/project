<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>  
                            <th>serverid</th>
                                              
                            <th>VIP0登陆人数 </th>
                         
                            <th>VIP1登陆人数</th>  
          
                         
                            <th>VIP2登陆人数</th>   
                            
                           
                            <th>VIP3登陆人数</th>   
                            
                           
                            <th>VIP4登陆人数</th>    
                                         
                         
                            <th>VIP5登陆人数</th>                 
                            
                            
                            <th>VIP6登陆人数</th>                 
                            
                         
                            <th>VIP7登陆人数</th>                 
                            
                         
                            <th>VIP8登陆人数</th>                 
                            
                           
                            <th>VIP9登陆人数</th>                 
                            
                         
                            <th>VIP10登陆人数</th>   
                                          
                           
                            <th>VIP11登陆人数</th>                
                            
                           
                            <th>VIP12登陆人数</th>                                
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
        request_url:'<?php echo site_url('DataAnalysis/activeVip');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } 
             //  console.log(result.data);
      
                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['serverid']+'</td>' +                          
                       '<td>'+result['data'][i]['v0']+'</td>' +
                     
                       '<td>'+result['data'][i]['v1']+'</td>' +
                    
                       '<td>'+result['data'][i]['v2']+'</td>' +
                              
                       '<td>'+result['data'][i]['v3']+'</td>' +
                                
                       '<td>'+result['data'][i]['v4']+'</td>' +
                               
                       '<td>'+result['data'][i]['v5']+'</td>' +
                                 
                       '<td>'+result['data'][i]['v6']+'</td>' +
                                     
                       '<td>'+result['data'][i]['v7']+'</td>' +
                                    
                       '<td>'+result['data'][i]['v8']+'</td>' +
                                  
                       '<td>'+result['data'][i]['v9']+'</td>' +
                                     
                       '<td>'+result['data'][i]['v10']+'</td>' +
                                      
                       '<td>'+result['data'][i]['v11']+'</td>' +
                    
                       '<td>'+result['data'][i]['v12']+'</td>' +
                               
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
