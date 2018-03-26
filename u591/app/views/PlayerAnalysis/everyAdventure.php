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
                                 <th>活跃人数</th>
                                <th >统计人数</th>
                                
                                <th>参与次数</th>
                                <th >完美次数</th>
                                
                                <th >成功次数</th>
                                <th >失败</th>
                                
                                   <th>放弃</th>
                                     <th>冒险奖励的与人数</th>
                                         <th>冒险奖励的参与率</th>
                                          <th>冒险奖励的洛托姆币</th>
                     
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
        autoload: false,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                var len = result['data'].length;
                for (var i =0 ;i<len;i++) {
                  
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['vip_level']+'</td>'+
                    '<td>'+result['data'][i]['active']+'</td>'+
                        '<td>'+result['data'][i]['cnt']+'</td>'+
                        '<td>'+result['data'][i]['total']+'</td>'+

                        
                     
                        '<td>'+result['data'][i]['p3']+'</td>'+
                        '<td>'+result['data'][i]['p2']+'</td>'+

                        '<td>'+result['data'][i]['p1']+'</td>'+
                        '<td>'+result['data'][i]['p4']+'</td>'+

                        '<td>'+result['data'][i]['participation']+'</td>'+
                        '<td>'+result['data'][i]['rate']+'%</td>'+
                        '<td>'+result['data'][i]['money']+'</td>'+

           
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
