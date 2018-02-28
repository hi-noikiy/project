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
                
                            <th> </th>
                            <th>出场次数</th>
                            <th>出场率</th>
                            <th>胜利次数 </th>
                              <th>失败次数 </th>
                                <th>胜率</th>
                                    <th></th>
                                
                               
                                   
                      
                         
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


function detail(skillid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/skillList?skillid='+skillid+'&show='+where
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
        request_url:'<?php echo site_url('PlayerAnalysisNew/pvpCombat');?>',
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
                    	  if(typeof(result['data'][i]['elf'])=='undefined'){result['data'][i]['elf']=0;}
                    	  if(typeof(result['data'][i]['appear_total'])=='undefined'){result['data'][i]['appear_total']=0;}
                    	  if(typeof(result['data'][i]['appear_rate'])=='undefined'){result['data'][i]['appear_rate']=0;}
                    	  if(typeof(result['data'][i]['win_total'])=='undefined'){result['data'][i]['win_total']=0;}
                    	  if(typeof(result['data'][i]['defeated'])=='undefined'){result['data'][i]['defeated']=0;}
                    	  if(typeof(result['data'][i]['win_rate'])=='undefined'){result['data'][i]['win_rate']=0;}                        
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['elf']+'</td>' +
                        '<td>'+result['data'][i]['appear_total']+'</td>' +
                        '<td>'+result['data'][i]['appear_rate']+'</td>' +
                        '<td>'+result['data'][i]['win_total']+'</td>' +
                        '<td>'+result['data'][i]['defeated']+'</td>' +
                        '<td>'+result['data'][i]['win_rate']+'</td>' +
                   
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
