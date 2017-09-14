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
                            <th>日期</th>
                            <th>付费人数(率)</th>
                            <th>(7天)付费人数(率)</th>
                            <th>(30天)付费人数(率)</th>
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
        title:'付费排行',
        request_url:'<?=site_url('PayAnalysis/getActionPayLogRank');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/pay_analysis_rank.js"></script>
