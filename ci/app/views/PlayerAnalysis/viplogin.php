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
                            <th>vip等级</th>
                            <th>当天登录数量</th>
                            <th>次日登录数量</th>
                            <th>占比</th>
                            <th>3日登录数量</th>
                            <th>占比</th>
                            <th>7日登录数量</th>
                            <th>占比</th>
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
<script>
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('PlayerAnalysis/VipLogin');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+i+'</td>' +
                        '<td>'+result['data'][i]['day0']+'</td>' +
                        '<td>'+result['data'][i]['day1']+'</td>' +
                        '<td>'+result['data'][i]['rare1']+'</td>' +
                        '<td>'+result['data'][i]['day3']+'</td>' +
                        '<td>'+result['data'][i]['rare3']+'</td>' +
                        '<td>'+result['data'][i]['day7']+'</td>' +
                        '<td>'+result['data'][i]['rare7']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
