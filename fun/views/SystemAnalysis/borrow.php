<style>
.cur{
cursor: pointer;
}
.curs{
color: red
}
</style>
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
                        	<th>玩家ID</th>
                            <th>任务ID</th>
                            <th>开放时间</th>
                            <th>时尚圈ID</th>
                            <th>借衣时间</th>
                            <th>被借衣饰ID</th>
                            <th>价值类型</th>
                            <th>价值数额</th>
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
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemAnalysis/Borrow');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '';
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['accountid']+'</td>' +
                        '<td>'+result['data'][i]['taskid']+'</td>' +
                        '<td>'+result['data'][i]['logdate']+'</td>' +
                        '<td>'+result['data'][i]['fashionid']+'</td>' +
                        '<td>'+result['data'][i]['borrowTime']+'</td>' +
                        '<td>'+result['data'][i]['dressid']+'</td>' +
                        '<td>'+result['data'][i]['type']+'</td>' +
                        '<td>'+result['data'][i]['number']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
