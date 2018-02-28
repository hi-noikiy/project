<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" name="t1" class="form-control date-picker" placeholder="查询开始时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" name="t2" class="form-control date-picker" placeholder="查询结束时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                    </div>
                </div>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="id" >日期</th>
                                <th data-column-id="sender" data-type="numeric">新增玩家</th>
                                <th data-column-id="received" data-type="numeric">DAU</th>
                                <th data-column-id="received" data-type="numeric">WAU</th>
                                <th data-column-id="received" data-type="numeric">MAU</th>
                                <th data-column-id="received" data-type="numeric">付费玩家</th>
                                <th data-column-id="received" data-type="numeric">非付费玩家</th>
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
        title:'有效玩家',
        request_url:'<?php echo site_url('PlayerAnalysis/getEffectivePlayerData');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_analysis_data.js"></script>