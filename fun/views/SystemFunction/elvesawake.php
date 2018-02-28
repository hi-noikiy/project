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
                            <th>每日获得神秘能量的人数</th>
                            <th>每日获得神秘能量12个及以上的人数</th>
                            <th>购买过神秘能量的人数</th>
                            <th>购买神秘能量1~5次的人数</th>
                            <th>购买神秘能量6~20次的人数</th>
                            <th>购买神秘能量21~51次的人数</th>
                            <th>购买神秘能量51次以上的人数</th>
                            <th>神秘的蛋拥有人数</th>
                            <th>超梦幼年体拥有人数</th>
                            <th>超梦成长体拥有人数</th>
                            <th>超梦拥有人数</th>
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
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>

    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemFunction/elvesAwake');?>',
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
                table_html += '<tr>' +
                '<td>'+result['data']['date']+'</td>' +
                '<td>'+result['data']['getNum']+'</td>' +
                '<td>'+result['data']['getAll']+'</td>' +
                '<td>'+result['data']['getBuyAll']+'</td>' +
                '<td>'+result['data']['get1All']+'</td>' +
                '<td>'+result['data']['get2All']+'</td>' +
                '<td>'+result['data']['get3All']+'</td>' +
                '<td>'+result['data']['get4All']+'</td>' +
                '<td>'+result['data']['100521']+'</td>' +
                '<td>'+result['data']['100522']+'</td>' +
                '<td>'+result['data']['100523']+'</td>' +
                '<td>'+result['data']['100524']+'</td>' +
                '</tr>';
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
