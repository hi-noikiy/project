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
                            <th></th>
                            <th>vip0</th>
                            <th>vip1</th>
                            <th>vip2</th>
                            <th>vip3</th>
                            <th>vip4</th>
                            <th>vip5</th>
                            <th>vip6</th>
                            <th>vip7</th>
                            <th>vip8</th>
                            <th>vip9</th>
                            <th>vip10</th>
                             <th>vip11</th>
                               <th>vip12</th>
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
function showdetail(act_id,param){
	layer.open({
		  type: 2,
		  title: '玩家详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/towerDetail?act_id='+act_id+'&param='+param
		  });
}



    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemFunctionNew/wowPet');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i)) {   

                
                	table_html += '<tr>' +
                	 '<td>'+result['data'][i]['name']+'</td>' +
                	 '<td>'+result['data'][i]['vip_level0']+'</td>' +
                     '<td>'+result['data'][i]['vip_level1']+'</td>' +
                     '<td>'+result['data'][i]['vip_level2']+'</td>' +
                     '<td>'+result['data'][i]['vip_level3']+'</td>' +
                     '<td>'+result['data'][i]['vip_level4']+'</td>' +
                     '<td>'+result['data'][i]['vip_level5']+'</td>' +
                     '<td>'+result['data'][i]['vip_level6']+'</td>' +
                     '<td>'+result['data'][i]['vip_level7']+'</td>' +
                     '<td>'+result['data'][i]['vip_level8']+'</td>' +
                     '<td>'+result['data'][i]['vip_level9']+'</td>' +
                     '<td>'+result['data'][i]['vip_level10']+'</td>' +
                     '<td>'+result['data'][i]['vip_level11']+'</td>' +
                     '<td>'+result['data'][i]['vip_level12']+'</td>' +
                    
                    '</tr>';
                    }}
                
                $("#dataTable").html(table_html);
             
           
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
