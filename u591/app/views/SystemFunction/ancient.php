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
                            <th> 社团统计类型 </th>
                            <th>获得物质0</th>
                            <th>获得物质1-100</th>
                            <th>获得物质101-500</th>
                           <th>获得物质501-1000</th>
                              <th>获得物质1001-2000</th>
                                <th>获得物质2001-3000</th>
                                 <th>获得物质3001-5000</th>
                                   <th>获得物质5001-10000</th>
                                     <th>获得物质10000以上</th>
                      
             
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
                <div >
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th> 社团统计类型 </th>
                            <th>远古宝藏挑战0</th>
                            <th>远古宝藏挑战1-5</th>
                            <th>远古宝藏挑战6-10</th>
                           <th>远古宝藏挑战11-20</th>
                              <th>获得物质21-30</th>
                                <th>远古宝藏挑战31-40</th>
                                 <th>获得物质41-50</th>
                                   <th>远古宝藏挑战51-100</th>
                                      <th>远古宝藏挑战100以上</th>
                                
                             
              
                      
             
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
<script>


function showdetail(parameter,classify){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/community?parameter='+parameter+'&classify='+classify
		  });
}


$('#btype').change(function(){
	if($(this).val()==4){
		$('#gametype').hide();
	}else{
		$('#gametype').show();
	}
});

    var dataOption = {
            title:'',
            autoload: false,
            request_url:'<?php echo site_url('SystemFunction/ancient');?>',
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
                        	 '<td>'+result['data'][i]['logdate']+'</td>' +
                            '<td>'+result['data'][i]['sum0']+'</td>' +
                            '<td>'+result['data'][i]['sum1']+'</td>' +        
                            '<td>'+result['data'][i]['sum2']+'</td>' +            
                            '<td>'+result['data'][i]['sum3']+'</td>' +
                            '<td>'+result['data'][i]['sum4']+'</td>' +
                            '<td>'+result['data'][i]['sum5']+'</td>' +
                            '<td>'+result['data'][i]['sum6']+'</td>' +
                            '<td>'+result['data'][i]['sum7']+'</td>' +
                            '<td>'+result['data'][i]['sum8']+'</td>' +
                            '<td>'+result['data'][i]['text']+'</td>' +
        
                             '</tr>';
                            }                    	
                        }                    
                    $("#dataTable").html(table_html);


                    var table_html2='';
                    if(result['challenge']!='')
                    for(var i in result['challenge']){
                        if(!isNaN(i)){
                        	table_html2 += '<tr>' +
                        	 '<td>'+result['challenge'][i]['logdate']+'</td>' +
                             '<td>'+result['challenge'][i]['sum0']+'</td>' +
                             '<td>'+result['challenge'][i]['sum1']+'</td>' +        
                             '<td>'+result['challenge'][i]['sum2']+'</td>' +            
                             '<td>'+result['challenge'][i]['sum3']+'</td>' +
                             '<td>'+result['challenge'][i]['sum4']+'</td>' +
                             '<td>'+result['challenge'][i]['sum5']+'</td>' +
                             '<td>'+result['challenge'][i]['sum6']+'</td>' +
                             '<td>'+result['challenge'][i]['sum7']+'</td>' +
                             '<td>'+result['challenge'][i]['sum8']+'</td>' +
                             '<td>'+result['challenge'][i]['text']+'</td>' +
                
                            '</tr>';
                            }                    	
                        }                    
                    $("#dataTable2").html(table_html2);
                  
                }
            }
        };


</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
