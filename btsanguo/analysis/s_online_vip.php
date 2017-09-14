<?php
$pageHeader = 'VIP平均在线时长';
$initDbSource = true;
include 'header.php';
$s_date = !empty($_GET['bt']) ? date('1ymd',strtotime($_GET['bt'])) : date('1ymd',$_SERVER['REQUEST_TIME']);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
$where = "where daytime=$s_date AND viplev>0 ";
if (count($serverids)>0) {
    $where .= " AND serverid IN(".implode(',', $serverids).")";
}
$sql = "select count(*) as cnt,AVG(`online`) as online,viplev from dayonline $where group by viplev ORDER BY viplev ASC";
if ($_GET['test']) {
	echo $sql;
}
$stmt = $db_source->prepare($sql);
$stmt->execute();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
$noEndTimeFilter = true;
$noServerFilter  = false;
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
                                <th>vip 等级</th>
                                <th>人数</th>
                                <th>平均在线时长（分钟）</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $list):?>
                                <tr>
                                    <td>VIP <?=$list['viplev']?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['online'] / 60?></td>
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