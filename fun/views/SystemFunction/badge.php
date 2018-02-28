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
                            <th> 段位</th>
                            <th>玩家数</th>
                            <th>平均专精</th>
                            <th>普通 </th>
                              <th>格斗 </th>
                                <th>飞行</th>
                                
                                  <th>毒系 </th>
                                    <th> 地上</th>
                                      <th>岩石</th>
                                        <th> 虫系</th>
                                          <th> 幽灵</th>
                                            <th>钢系</th>
                                            
                                             <th>炎系</th>
                                              <th>水系</th>
                                               <th>草系</th>
                                                <th>电系</th>
                      
                         
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


function dan(continuous,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/danDistribution?continuous='+continuous+'&show='+where
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
        request_url:'<?php echo site_url('SystemFunction/badge');?>',
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
                        '<td>'+result['data'][i]['dan']+'</td>' +
                        '<td>'+result['data'][i]['total']+'</td>' +
                        '<td>'+result['data'][i]['avg_mastery']+'</td>' +
                        '<td>'+result['data'][i]['num_1']+'</td>' +
                        '<td>'+result['data'][i]['num_2']+'</td>' +
                        '<td>'+result['data'][i]['num_3']+'</td>' +
                        '<td>'+result['data'][i]['num_4']+'</td>' +
                        '<td>'+result['data'][i]['num_5']+'</td>' +
                        '<td>'+result['data'][i]['num_6']+'</td>' +
                        '<td>'+result['data'][i]['num_7']+'</td>' +
                        '<td>'+result['data'][i]['num_8']+'</td>' +
                        '<td>'+result['data'][i]['num_9']+'</td>' +
                        '<td>'+result['data'][i]['num_10']+'</td>' +
                        '<td>'+result['data'][i]['num_11']+'</td>' +
                        '<td>'+result['data'][i]['num_12']+'</td>' +
                        '<td>'+result['data'][i]['num_13']+'</td>' +
       
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
