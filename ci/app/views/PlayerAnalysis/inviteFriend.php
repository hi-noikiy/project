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
                            <th> 帐号id</th>
                            <th> 角色id</th>
                            <th>服务器id</th>
                            <th>渠道</th>
                            <th>等级</th>
                            
                           <th>被邀请人vip等级</th>
                            <th> 被邀请人帐号id</th>
                            <th> 被邀请人角色id</th>
                            <th>被邀请人服务器id</th>
                            <th>被邀请人渠道</th>
                            <th>被邀请人等级</th>
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
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+i+'</td>' +
                        '<td>'+result['data'][i]['viplev']+'</td>' +
                        '<td>'+result['data'][i]['accountid']+'</td>' +
                        '<td>'+result['data'][i]['userid']+'</td>' +
                        '<td>'+result['data'][i]['serverid']+'</td>' +
                        '<td>'+result['data'][i]['channel']+'</td>' +
                        '<td>'+result['data'][i]['lev']+'</td>' +

                        '<td>'+result['data'][i]['p_viplev']+'</td>' +
                        '<td>'+result['data'][i]['p_accountid']+'</td>' +
                        '<td>'+result['data'][i]['p_userid']+'</td>' +
                        '<td>'+result['data'][i]['p_serverid']+'</td>' +
                        '<td>'+result['data'][i]['p_channel']+'</td>' +
                        '<td>'+result['data'][i]['p_lev']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
