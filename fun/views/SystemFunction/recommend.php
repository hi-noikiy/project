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
                            <th> 战斗时间</th>
                            <th>玩家id</th>
                            <th>玩家名</th>
                            <th>服务器id</th>
                              <th>玩家段位</th>
                                <th>玩家vip等级</th>
                                
                                  <th>玩家等级</th>
                                    <th>精灵id</th>
                                      <th>精灵技能1</th>
                                        <th>精灵技能2</th>
                                          <th>精灵技能3</th>
                                            <th>精灵技能4</th>
                                            
                                             <th>精灵特性</th>
                                              <th>精灵树果</th>
                                               <th>精灵装备</th>
                                                <th>精灵性格</th>
                      
                         
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
        request_url:'<?php echo site_url('SystemFunction/recommend');?>',
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
                        '<td>'+result['data'][i]['endTime']+'</td>' +
                        '<td>'+result['data'][i]['userid']+'</td>' +
                        '<td>'+result['data'][i]['name']+'</td>' +
                        '<td>'+result['data'][i]['serverid']+'</td>' +
                        '<td>'+result['data'][i]['dan']+'</td>' +
                        '<td>'+result['data'][i]['viplevel']+'</td>' +
                        '<td>'+result['data'][i]['level']+'</td>' +
                        '<td>'+result['data'][i]['eudemon']+'</td>' +
                        '<td>'+result['data'][i]['skills1']+'</td>' +
                        '<td>'+result['data'][i]['skills2']+'</td>' +
                        '<td>'+result['data'][i]['skills3']+'</td>' +
                        '<td>'+result['data'][i]['skills4']+'</td>' +
                        '<td>'+result['data'][i]['abilities']+'</td>' +
                        '<td>'+result['data'][i]['fruit']+'</td>' +
                        '<td>'+result['data'][i]['equip']+'</td>' +
                        '<td>'+result['data'][i]['kidney']+'</td>' +
       
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
