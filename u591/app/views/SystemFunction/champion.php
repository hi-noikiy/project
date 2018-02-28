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
                
                            <th> 区服分布</th>
                            <th>预选赛参赛人数</th>
                            <th>十六强参赛人数</th>
                            <th>八强参赛人数 </th>
                              <th>半决赛参赛人数 </th>
                                <th>总决赛</th>
                                
                                  <th>详细 </th>
                                   
                      
                         
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


function detail(serverid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/sixteen?serverid='+serverid+'&show='+where
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
        request_url:'<?php echo site_url('SystemFunction/champion');?>',
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
                    	  if(typeof(result['data'][i]['total'])=='undefined'){result['data'][i]['total']=0;}
                    	  if(typeof(result['data'][i]['total_16'])=='undefined'){result['data'][i]['total_16']=0;}
                    	  if(typeof(result['data'][i]['total_8'])=='undefined'){result['data'][i]['total_8']=0;}
                    	  if(typeof(result['data'][i]['total_4'])=='undefined'){result['data'][i]['total_4']=0;}
                    	  if(typeof(result['data'][i]['total_1'])=='undefined'){result['data'][i]['total_1']=0;}                        
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['serverid']+'</td>' +
                        '<td>'+result['data'][i]['total']+'</td>' +
                        '<td>'+result['data'][i]['total_16']+'</td>' +
                        '<td>'+result['data'][i]['total_8']+'</td>' +
                        '<td>'+result['data'][i]['total_4']+'</td>' +
                        '<td>'+result['data'][i]['total_1']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
                     
                 
       
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
