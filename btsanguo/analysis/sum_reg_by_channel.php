<?php
$initDbSource = true;
$pageHeader = '实时在线统计';
include 'header.php';
$dis = new DisplayUser( $db_source );
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
try{
    $channel_day = array();
    //日活跃数即是对应渠道当日的活跃人数。
    $lists = $dis->ShowRegRealTime($bt);
    $day = date('Ymd', strtotime($bt));

    $sql_dau = <<<SQL
          SELECT fenbaoid,count(DISTINCT accountid) as cnt
          FROM palyerday WHERE day=$day GROUP BY fenbaoid
SQL;
//    echo $sql_dau;
    $stmt = $db_source->prepare($sql_dau);
    $stmt->execute();
    while ($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $channel_day[$tmp['fenbaoid']] = $tmp['cnt'];
    }
} catch(Exception $e) {
    echo $e->getMessage();
}
$noEndTimeFilter = true;
$noServerFilter  = true;
$noFenbaoFilter  = true;
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th><?=$lang['channel']?></th>
                                <th><?=$lang['s_reg_num']?></th>
                                <th>日活跃数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $list):?>
                                <tr>
                                    <td>[<?=$list['fenbaoid']?>]<?=$fenbaos[$list['fenbaoid']];?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$channel_day[$list['fenbaoid']]?></td>
                                </tr>
                            <?php endforeach;?>
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
<?php include 'footer.php';?>