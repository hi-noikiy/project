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
                            <th>洛托姆平均等级 </th>
                            <th>洛托姆当前最高级</th>
                            <th>最高级人数</th>                            
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
        request_url:'<?php echo site_url('DataAnalysis/rotomCultivate');?>',
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
                       '<td>'+result['data'][i]['avg_grade']+'</td>' +
                       '<td>'+result['data'][i]['max_grade']+'</td>' +
                       '<td>'+result['data'][i]['max_num']+'</td>' +                       
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
