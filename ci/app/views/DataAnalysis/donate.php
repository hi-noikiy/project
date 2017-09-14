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
                            <th> 参与人数</th>                                              
                            <th>金币捐献平均次数 </th>
                            <th>钻石捐献平均次数</th>
                            <th>慷慨捐献平均次数</th>      
                            <th>平均获得社团贡献</th>   
                            <th>平均花费钻石</th>                         
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
        request_url:'<?php echo site_url('SystemFunction/donate');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } 
             //  console.log(result.data);
   
                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['viplev']+'</td>' +                          
                       '<td>'+result['data'][i]['c']+'</td>' +
                       '<td>'+result['data'][i]['participation']+'</td>' +
                       '<td>'+result['data'][i]['gold_avg']+'</td>' +
                       '<td>'+result['data'][i]['diamond_avg']+'</td>' +
                       '<td>'+result['data'][i]['bounty_avg']+'</td>' +      
                       '<td>'+result['data'][i]['donate_avg']+'</td>' +   
                       '<td>'+result['data'][i]['avg_expenditure']+'</td>' +                    
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
