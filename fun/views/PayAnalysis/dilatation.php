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
                                <th >统计人数</th>
                                
                                <th>扩容4</th>
                                <th >扩容5</th>
                                
                                <th >扩容6</th>
                                <th >扩容7</th>
                                
                                   <th >扩容8</th>
                              
                                
                                 
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
        autoload: true,
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
                        '<td>'+result['data'][i]['cnt']+'</td>'+
                     
                        '<td>'+result['data'][i]['m4']+'</td>'+
                        '<td>'+result['data'][i]['m5']+'</td>'+

                        '<td>'+result['data'][i]['m6']+'</td>'+
                        '<td>'+result['data'][i]['m7']+'</td>'+

                        '<td>'+result['data'][i]['m8']+'</td>'+
                       
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>