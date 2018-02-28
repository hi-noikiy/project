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
                            <th> 服务器ID</th>
                            <th>参赛人数</th>
                            <th>VIP0参赛人数</th>
                            <th>VIP1参赛人数</th>
                             <th>VIP2参赛人数</th>
                              <th>VIP3参赛人数</th>
                               <th>VIP4参赛人数</th>
                                <th>VIP5参赛人数</th>
                                 <th>VIP6参赛人数</th>
                                  <th>VIP7参赛人数</th>
                                   <th>VIP8参赛人数</th>
                                    <th>VIP9参赛人数</th>
                                     <th>VIP10参赛人数</th>
                                      <th>VIP11参赛人数</th>
                                       <th>VIP12参赛人数</th>
                         </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
             <div class="col-md-12">
            <div class="card">
                <div class="table-responsive2">
                    <table id="data-table-basic2" class="table table-striped">
                        <thead>
                        <tr>
                            <th> 服务器ID</th>
                            <th>社团ID</th>
                            <th>参赛人数</th>
                      
                         </tr>
                        </thead>
                        <tbody id="dataTable2">
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
<script src="<?=base_url()?>public/ma/js/functions_v2.js"></script>
<script>
    var dataOption = {
            title:'',
            autoload: false,
            request_url:'<?php echo site_url('SystemFunction/hegemony');?>',
            callback: function (result) {
                if (result) {
                    if (result.status!='ok') {
                        $("#dataTable").html('');
                        notify(result.info);
                        return false;
                    }
                    //console.log(result.data);

                     var table_html='';
                    if(result['data']!='')
                    for(var i in result['data']){
                        if(!isNaN(i)){
                        	table_html += '<tr>' +
                            '<td>'+result['data'][i]['serverid']+'</td>' +
                            '<td>'+result['data'][i]['total']+'</td>' +        
                            '<td>'+result['data'][i]['vip0']+'</td>' +            
                            '<td>'+result['data'][i]['vip1']+'</td>' +
                            '<td>'+result['data'][i]['vip2']+'</td>' +
                            '<td>'+result['data'][i]['vip3']+'</td>' +
                            '<td>'+result['data'][i]['vip4']+'</td>' +
                            '<td>'+result['data'][i]['vip5']+'</td>' +
                            '<td>'+result['data'][i]['vip6']+'</td>' +
                            '<td>'+result['data'][i]['vip7']+'</td>' +
                            '<td>'+result['data'][i]['vip8']+'</td>' +
                            '<td>'+result['data'][i]['vip9']+'</td>' +
                            '<td>'+result['data'][i]['vip10']+'</td>' +
                            '<td>'+result['data'][i]['vip11']+'</td>' +
                            '<td>'+result['data'][i]['vip12']+'</td>' +
                             '</tr>';
                            }                    	
                        }                    
                    $("#dataTable").html(table_html);
                    var table_html2='';
                    if(result['data2']!='')
                    for(var i in result['data2']){
                        if(!isNaN(i)){
                        	table_html2 += '<tr>' +
                            '<td>'+result['data2'][i]['serverid']+'</td>' +
                            '<td>'+result['data2'][i]['syn_id']+'</td>' +        
                            '<td>'+result['data2'][i]['total']+'</td>' +      
                
                            '</tr>';
                            }                    	
                        }                    
                    $("#dataTable2").html(table_html2);
                }
            }
        };


</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
