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
                     
                            <th>Vip</th>
                            <th>活跃人数</th>
                            <th>活动入口点击率</th>
                              <th>参与1场人数</th>
                              
                               <th>参与2场人数</th>
                                <th>参与3场人数</th>
                                 <th>参与4场人数</th>
                                  <th>参与5场人数</th>
                                  
                                   <th>购买五战宝箱</th>
                                    <th>3人组队匹配</th>
                                     <th>2人组队匹配</th>
                                      <th>单人匹配</th>
                      
                         
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
        request_url:'<?php echo site_url('SystemFunction/elite');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }          
                 var table_html='';
                if(result['data']!='')
                for(var i in result['data']){
                    if(!isNaN(i)){
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['vip_level']+'</td>' +
                        '<td>'+result['data'][i]['c']+'</td>' +
                        '<td>'+result['data'][i]['click']+'</td>' +
                        '<td>'+result['data'][i]['part_1']+'</td>' +
                        '<td>'+result['data'][i]['part_2']+'</td>' +
                        '<td>'+result['data'][i]['part_3']+'</td>' +
                        '<td>'+result['data'][i]['part_4']+'</td>' +
                        '<td>'+result['data'][i]['part_5']+'</td>' +
                        '<td>'+result['data'][i]['purchase_treasure']+'</td>' +
                        '<td>'+result['data'][i]['team_1']+'</td>' +
                        '<td>'+result['data'][i]['team_2']+'</td>' +
                        '<td>'+result['data'][i]['team_3']+'</td>' +
       
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
