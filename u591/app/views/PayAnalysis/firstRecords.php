<section id="content">
    <input type="hidden" name='time' id="time" value="">
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
                            <th>日期</th>
                            <th> 首冲次数</th>
                            <th> 首冲金额</th>
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



$(document).on('click', '.xi', function(){
    var begin = $(this).attr('time');
    layer.open({
        type: 2,
        title: 'iframe父子操作',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['800px' , '520px'],
        content: '../frame/firstDistribution?time='+begin
    });
    $('#time').val(begin);
})



	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('PayAnalysis/FirstRecord');?>',
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
                    '<td>'+result['data'][i]['date']+'</td>' +
                       '<td>'+result['data'][i]['total']+'</td>' +
                       '<td>'+result['data'][i]['sum']+'</td>' +
                       '<td>'+result['data'][i]['deng']+'</td>' +

                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>

   


<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
