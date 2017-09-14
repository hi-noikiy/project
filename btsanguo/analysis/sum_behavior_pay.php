<?php
//$pageHeader = '消费行为';
include 'header.php';
//$db_sum  = db('analysis');
//echo  $bt,'--', $et;
$dis = new Display($db_sum, $game_id, $bt, $et);
$types = $dis->GetEmoneyTypes();
$exportFlag = true;
try{
    $data = $dis->ShowMarketPay(Analysis::ConsumptionBehavior, $serverids, $fenbaoids, $offset, $pageSize);
    $total_rows = $data['total'];
    $lists = $data['lists'];
    $total_emoney = $data['total_emoney'];
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <p><a href="sum_behavior_pay_vip.php">VIP数据统计</a></p>
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table  <?=$total_rows ? 'id="dataTable"':'' ?> class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
<!--                                <th>日期</th>-->
                                <th>消费行为</th>
                                <th>总消费</th>
                                <th>比率</th>
                                <td>查看</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $key=>$list):?>
                                    <tr>
<!--                                        <td>--><?php //echo date('Y-m-d', strtotime($list['sday']));?><!--</td>-->
                                        <td><?php echo $types[$list['stype']]?> [<?=$list['stype']?>] </td>
                                        <td><?php echo $list['sum_emoney']?></td>
                                        <td><?php echo round($list['sum_emoney']/$total_emoney, 6)*100?> %</td>
                                        <td>
                                            <a href="sum_behavior_pay_detail.php?stype=<?=$list['stype']?>&bt=<?=$bt?>&et=<?=$et?>" target="_blank">查看</a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="sum_behavior_pay_detail.php?time_group=1&stype=<?=$list['stype']?>&bt=<?=$bt?>&et=<?=$et?>" target="_blank">时间分列</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="7">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4"><?php page($total_rows,$currentPage,$pageSize);?></td>
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