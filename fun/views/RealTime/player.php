<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
    </div>
</section>
<script>
    var dataOption = {
        title:'活跃玩家',
        request_url:'<?php echo site_url('RealTime/getActivePlayerData');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/realtime_data.js"></script>