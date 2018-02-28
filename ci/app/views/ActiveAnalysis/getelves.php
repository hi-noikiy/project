<section id="content">
    <div class="container">
        <div class="block-header">
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>选择查询条件<small></small></h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                        <form id="search_form" method="get" action="">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <div class="fg-line">
                                        <input title="玩家登录时间" type="text" name="date" value="" class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>" placeholder="玩家登录时间">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                        		<div class="form-group">
                            		<div class="fg-line">
                                		<input title="limit" type="text" name="temid" class="form-control" placeholder="输入精灵id">
                            		</div>
                        		</div>
                    		</div>
                            <div class="col-sm-2">
                                <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">获取</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>

    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('GameRunDay/getelves');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    notify(result.info);
                    return false;
                }
                notify('获取成功');
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
