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
                            <th>vip</th>
                            <th> 活跃人数</th>                        
                            <th> 获得充值积分数量</th>
                            <th>消耗充值积分数量</th>
                            <th>剩余充值积分数量</th>
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
<script src="<?=base_url()?>public/ma/js/layer.js"></script>


<script>
var name;




function bonusDistribution(vip_level,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/bonusDistribution?vip_level='+vip_level+'&show='+where
		  });
}


	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('PayAnalysis/bonusPoint');?>',
        callback: function (result) {
            if (result) {
               /*  if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } */
        

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['viplev']+'</td>' +                          
                       '<td>'+result['data'][i]['c']+'</td>' +
                       '<td>'+result['data'][i]['get_point']+'</td>' +
                       '<td>'+result['data'][i]['consume_point']+'</td>' +
                       '<td>'+result['data'][i]['surplus_point']+'</td>' +
                       '<td>'+result['data'][i]['text']+'</td>' +
     
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>

   


<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
