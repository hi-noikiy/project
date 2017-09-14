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
                        	<th>账号</th>
                            <th>区服</th>
                            <th>最近登陆时间</th>
                            <th>最近登陆ip</th>
                            <th>充值总金额</th>
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
        autoload: false,
        request_url:"<?php echo site_url('VipUser/getUserInfo');?>",
        callback: function (result) {
            if (result) {
                //console.log(result.data);

                var table_html = '';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i))
                	table_html += '<tr>' +
                	 '<td>'+result['data'][i]['username']+'</td>' +
                	 '<td>'+result['data'][i]['userid']+'</td>' +
                    '<td>'+result['data'][i]['serverid']+'</td>' +
                    '<td>'+result['data'][i]['last_login_ip']+'</td>' +
                    '<td>'+result['data'][i]['last_login_time']+'</td>' +
                  
                     '</tr>';
                    }
               
                $("#dataTable").html(table_html);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
