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
                                <th>日期</th>
                                <th>新增角色</th>
                                <th>次日留存</th>
                                <th>3日留存</th>
                                <th>4日留存</th>
                                <th>5日留存</th>
                                <th>6日留存</th>
                                <th>7日留存</th>
                                <th>15日留存</th>
                                <th>30日留存</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable"></tbody>
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
        request_url:'<?php echo site_url('GameAnalysis/Remain');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var date in result.data) {
                    //console.log(date);
                    //console.log(result.data[date]['total']);
                    table_html += '<tr>' +
                                '<td>'+date+'</td>'
                                +'<td>'+result.data[date]['total']+'</td>'
                               + '<td>'+result.data[date][0]+'</td>'
                               + '<td>'+result.data[date][1]+'</td>'
                               + '<td>'+result.data[date][2]+'</td>'
                               + '<td>'+result.data[date][3]+'</td>'
                               + '<td>'+result.data[date][4]+'</td>'
                               + '<td>'+result.data[date][5]+'</td>'
                               + '<td>'+result.data[date][13]+'</td>'
                               + '<td>'+result.data[date][28]+'</td></tr>';
                }
                //console.log(result.data);
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
