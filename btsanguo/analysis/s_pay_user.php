<?php
/**
 * 新老付费用户
 */
$initDbSource = true;
include 'header.php';
$total_money = $total_user = '亲，请选择时间段';
if ($_GET['bt']) {
    $bbt = !empty($_GET['bt']) ? date('ymd0000', strtotime($_GET['bt'])) : date('ymd0000', $_SERVER['REQUEST_TIME']);
    $eet = !empty($_GET['et']) ? date('ymd2359', strtotime($_GET['et'])) : date('ymd2359', $_SERVER['REQUEST_TIME']);
    $lists = array();
    $sql = "SELECT accountid,SUM(price) as total_price FROM pay WHERE (daytime BETWEEN $bbt AND $eet) GROUP BY accountid";
    if ($_GET['debug']) echo $sql;
    $stmt = $db_source->prepare($sql);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lists[$row['accountid']] = $row['total_price'];
    }
    if (count($lists)>0) {
        $sql_chk = "SELECT accountid,COUNT(*) AS cnt FROM player WHERE accountid IN(".implode(',', array_keys($lists)).") GROUP BY accountid";
        $stmt = $db_source->prepare($sql_chk);
        $stmt->execute();
        $data = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['cnt']>1) {
                $total_money += $lists[$row['accountid']];
                $total_user  += 1;
            }
        }
    }
}
$lists = array();


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
                                <th>老玩家人数</th>
                                <th>老玩家充值总金额（单位：元）</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$total_user?></td>
                                    <td><?=$total_money / 10?></td>
                                </tr>
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