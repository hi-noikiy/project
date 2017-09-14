<?php
//$pageHeader = '商城消费';
$initDbSource = 1;
include 'header.php';
//$db_sum  = db('analysis');
//$dis = new Display($db_sum, $game_id, $bt, $et);
$goods = Display::GetVipGoods($db_sum);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  $_SERVER['REQUEST_TIME']);
$table = isset($_GET['table']) ? trim($_GET['table']) : 'rmb';

$sql = "SELECT sum(emoney) as emoney,itemtype FROM $table WHERE type=103";
$bbt = !empty($_GET['bt']) ? date('ymd0000', strtotime($_GET['bt'])) : date('ymd0000', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['bt']) ? date('ymd2359', strtotime($_GET['et'])) : date('ymd2359', $_SERVER['REQUEST_TIME']);
$where = " AND daytime BETWEEN ? AND ?";
$sql .= $where . " GROUP BY itemtype ORDER BY emoney ASC";
$stmt = $db_source->prepare($sql);
$stmt->execute(array($bbt, $eet));
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
$noFenbaoFilter = true;
$total_emoney = 0;
$sql_sum = "SELECT sum(emoney) as emoney from $table WHERE type=103" . $where;
echo $sql_sum;
$stmt = $db_source->prepare($sql_sum);
$stmt->execute(array($bbt, $eet));
$sum_emoney= $stmt->fetchColumn(0);

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
<!--                                <th>时间</th>-->
<!--                                <th>玩家ID</th>-->
<!--                                <th>账号</th>-->
                                <th>道具名称[道具ID]</th>
                                <th>元宝消耗</th>
                                <th>比率</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $key=>$list):?>
                                    <?php $total_emoney +=$list['emoney']; ?>
                                    <tr>
<!--                                        <td>--><?//=date('Y-m-d H:i:s', strtotime('20'.$list['daytime']));?><!--</td>-->
<!--                                        <td>--><?//=$list['userid']?><!--</td>-->
<!--                                        <td>--><?//=$list['accountid']?><!--</td>-->
                                        <td><?=$goods[$list['itemtype']],'[',$list['itemtype'],']'?></td>
                                        <td><?=$list['emoney']?></td>
                                        <td><?=round($list['emoney'] / $sum_emoney, 6) * 100?> % </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="5">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>汇总：</td>
                                    <td><?=$total_emoney?></td>
                                    <td>100%</td>
                                </tr>
                            </tfoot>
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