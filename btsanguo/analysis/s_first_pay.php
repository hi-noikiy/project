<?php
//$pageHeader = '商城消费';
$initDbSource = 1;
include 'header.php';
//$db_sum  = db('analysis');
//$dis = new Display($db_sum, $game_id, $bt, $et);

$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  $_SERVER['REQUEST_TIME']);

$bbt = !empty($_GET['bt']) ? date('ymd0000', strtotime($_GET['bt'])) : date('ymd0000', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['bt']) ? date('ymd2359', strtotime($_GET['et'])) : date('ymd2359', $_SERVER['REQUEST_TIME']);

$sql = "SELECT payemoney,daytime,lev FROM first_rmb WHERE 1=1";
$where = " AND daytime BETWEEN $bbt AND $eet";
$where .= count($serverids)>0 ? " AND serverid IN(".implode(',', $serverids).")" : '';
$sql .= $where . " ORDER BY daytime ASC";
//. " GROUP BY emoney,serverid";
//echo $sql;
$stmt = $db_source->prepare($sql);
//echo $sql;
$stmt->execute();
$lists = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $day = date('y-m-d',strtotime('20'.$row['daytime']));
    $lists[$day]['total']   += 1;
    $lists[$day]['lev']     += $row['lev'];
    $lists[$day]['money']   += $row['payemoney'] / 10;
}
//print_r($lists);
$noFenbaoFilter = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>日期</th>
                                <th>首充人数</th>
                                <th>平均等级</th>
                                <th>平均首充金额</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $day=>$list):?>
                                    <tr>
                                        <td><?=$day?></td>
                                        <td><?=$list['total']?></td>
                                        <td><?=ceil($list['lev'] / $list['total'])?></td>
                                        <td><?=ceil($list['money'] / $list['total'])?></td>
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