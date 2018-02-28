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
                            <th> 新增用户数量</th>
                            <th> 付费用户数量</th>
                            <th> 付费率</th>
                            <th>付费金额</th>
                            <th>arpu</th>
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
        request_url:'<?php echo site_url('PayAnalysis/PayNewAccounts');?>',
        callback: function (result) {
            if (result) {

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;

                    var bai = ((result['data'][i]['ctotal']/result['data'][i]['total'])*100).toFixed(2);
                    var arpu = ((result['data'][i]['money']/result['data'][i]['total'])*100/100).toFixed(2);
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['date']+'</td>' +
                        '<td>'+result['data'][i]['total']+'</td>' +
                        '<td>'+result['data'][i]['ctotal']+'</td>' +
                        '<td>'+bai+'%'+'</td>' +
                        '<td>'+result['data'][i]['money']+'</td>' +
                        '<td>'+arpu+'</td>' +

                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
