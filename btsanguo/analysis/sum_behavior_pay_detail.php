<?php
//$pageHeader = '消费行为';
include 'header.php';
//$db_sum  = db('analysis');
//echo  $bt,'--', $et;
$dis = new Display($db_sum, $game_id, $bt, $et);
$types = $dis->GetEmoneyTypes();
$grp_by_time = false;
$col_title = '区服ID';
$col_key   = 'serverid';

if ($_GET['time_group'] && $_GET['time_group']==1) {
    $grp_by_time = true;
    $col_title = '日期';
    $col_key   = 'sday';
}
//$exportFlag = true;
try{
    $stype = $_GET['stype'];
    $data = $dis->ShowMarketPayDetail($stype, $grp_by_time);
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4><?php echo '消费行为:',$types[$stype],';查询时段:',$bt, '至' , $et;?></h4>
                    <div class="table-responsive">
                        <table  <?=$total_rows ? 'id="dataTable"':'' ?> class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><?=$col_title?></th>
                                <th>消费元宝</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($data)):?>
                                <?php foreach($data as $key=>$list):?>
                                    <tr>
                                        <td><?php echo $list[$col_key];?></td>
                                        <td><?php echo $list['sum_emoney'];?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="7">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php include 'footer.php'; ?>