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
                        

                        
                           <th>签到天数</th>
                            <th> 每日平均   充值（RMB）</th>
                            <th>每日平均    消耗钻石</th>
                            <th>人物平均等级	</th>
                            <th>平均战力</th>
                            <th>技能专精平均</th>
                            
                           <th>创世石板等级</th>
                            <th> 创世能量平均等级</th>
                            <th> 创世元神平均等级</th>
                            <th>上阵精灵平均等级</th>
                            <th>图鉴</th>
                            <th>亲密度</th>
                              <th>努力值</th>
                                <th>个体值</th>
                                  <th>平均宝芬</th>
                                      <th>战力 </th>
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
        autoload: false,
        request_url:'<?php echo site_url('PlayerAnalysis/tipical');?>',
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
                      
                        '<td>'+result['data'][i]['total_days']+'</td>' +
                        '<td>'+result['data'][i]['pay_avg']+'</td>' +
                        '<td>'+result['data'][i]['consume']+'</td>' +
                        '<td>'+result['data'][i]['user_level']+'</td>' +
                        '<td>'+result['data'][i]['prestige']+'</td>' +
                        '<td>'+result['data'][i]['synscience_avg']+'</td>' +

                        '<td>'+result['data'][i]['stonestep_avg']+'</td>' +
                        '<td>'+result['data'][i]['stonelevel_avg']+'</td>' +
                        '<td>'+result['data'][i]['godstep']+'</td>' +
                        
                        '<td>'+result['data'][i]['level_avg']+'</td>' +
                        '<td>'+result['data'][i]['handbook_avg']+'</td>' +
                        '<td>'+result['data'][i]['intimacy_avg']+'</td>' +

                        '<td>'+result['data'][i]['effort_avg']+'</td>' +
                        '<td>'+result['data'][i]['individual_avg']+'</td>' +
                        '<td>'+result['data'][i]['baofen_avg']+'</td>' +
                        '<td>'+result['data'][i]['prestige_avg']+'</td>' +
                        '</tr>';
    
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
