<section id="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card">
            	<?=$search_form_web?>
                <div id="echart" style="width: 100%;height:400px; display: none"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>付费金额区间</th>
                            <th>每日付费玩家</th>
                            <th>每周(7天)付费玩家</th>                       
                            <th>每月付费玩家</th>
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
        title:'付费行为',
        request_url:'<?=site_url('PayAnalysis/getActionPayLogBehavior');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/pay_analysis_behavior.js"></script>
