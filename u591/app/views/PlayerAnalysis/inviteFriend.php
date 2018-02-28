<section id="content">
    <div class="container">
        <div class="block-header">
            <input type="hidden" name='time1' id="time1" value="">
            <input type="hidden" name='time2' id="time2" value="">
            <input type="hidden" name='id' id="id" value="">
            <input type="hidden" name='type' id="type" value="">
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
                            <th>人数</th>
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
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('PlayerAnalysis/inviteFriend');?>',
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
                    var deng = '('+result['data'][i]['begin']+','+result['data'][i]['begin']+')';
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['viplev']+'</td>' +
                        '<td>'+result['data'][i]['sum']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };

    function showIframe(begin, end, vipid, type){
        layer.open({
            type: 2,
            title: 'iframe父子操作',
            maxmin: true,
            shadeClose: true, //点击遮罩关闭层
            area : ['800px' , '520px'],
            content: '../frame/ActionInviteFriend?begin='+begin+'&end='+end+'&vipid='+vipid+'&type='+type
        });
        $('#time1').val(begin);
        $('#time2').val(end);
        $('#id').val(vipid);
        $('#type').val(type);
    }

</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
