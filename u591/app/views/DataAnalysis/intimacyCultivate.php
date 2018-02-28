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
                            <th>生命属性平均加值</th>
                            <th>物攻属性平均加值</th>
                            <th>物防属性平均加值</th>
                            <th>特攻属性平均加值</th>
                            <th>特防属性平均加值</th>
                            <th>速度属性平均加值</th>
                            <th>重置次数 </th>
                            <th>平均重置花费 </th>    
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
        request_url:'<?php echo site_url('DataAnalysis/intimacyCultivate');?>',
        callback: function (result) {
            if (result) {
               /*  if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } */
               console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['viplev']+'</td>' +                          
                       '<td>'+result['data'][i]['c']+'</td>' +
                       '<td>'+result['data'][i]['life_avg']+'</td>' +
                       '<td>'+result['data'][i]['attack_avg']+'</td>' +
                       '<td>'+result['data'][i]['defend_avg']+'</td>' +
                       '<td>'+result['data'][i]['special_attack_avg']+'</td>' +
                       '<td>'+result['data'][i]['special_defend_avg']+'</td>' +
                       '<td>'+result['data'][i]['speed_avg']+'</td>' +
                       '<td>'+result['data'][i]['reset_num']+'</td>' +
                       '<td>'+result['data'][i]['reset_cost']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
