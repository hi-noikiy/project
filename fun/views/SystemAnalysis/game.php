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
                            <th>开始时间</th>
                            <th>衣柜等级</th>
                            <th>耗时</th>
                            <th>被投票次数</th>
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
        request_url:'<?php echo site_url('SystemAnalysis/Game');?>',
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
                        '<td>'+result['data'][i]['tasktime']+'</td>' +
                        '<td>'+result['data'][i]['starttime']+'</td>' +
                        '<td>'+result['data'][i]['wardrobeLevel']+'</td>' +
                        '<td>'+result['data'][i]['consumetime']+'</td>' +
                        '<td>'+result['data'][i]['c']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>