<?php
//$pageHeader = '商城消费';
include 'header.php';
//$db_sum  = db('analysis');
$dis = new Display($db_sum, $game_id, $bt, $et);
$goods = Display::GetVipGoods($db_sum);
$exportFlag = true;
try{
    $data = $dis->ShowMarketPay(Analysis::ConsumptionMarket, $serverids, $fenbaoids, $offset, $pageSize);
    $total_rows = $data['total'];
    $lists      = $data['lists'];
    $total_emoney = $data['total_emoney'];
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <p><a href="sum_market_pay_vip.php">VIP数据统计</a></p>
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 5%;">序号</th>
                                <th>日期</th>
                                <th>道具名称[道具ID]</th>
                                <th>总消费(元宝数)</th>
                                <th>比率</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $key=>$list):?>
                                    <tr>
                                        <td><?=$key+1;?></td>
                                        <td><?=date('Y-m-d', strtotime($list['sday']));?></td>
                                        <td><?=$goods[$list['itemtype']],'[',$list['itemtype'],']'?></td>
                                        <td><?=$list['sum_emoney']?></td>
                                        <td><?=round($list['sum_emoney']/$total_emoney, 6)*100?> %</td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="5">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5"><?php page($total_rows,$currentPage,$pageSize);?></td>
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