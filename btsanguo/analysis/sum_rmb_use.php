<?php
//$pageHeader = '元宝消耗';
include("header.php");
$exportFlag = true;
//$db_sum  = db('analysis');
$dis = new Display($db_sum, $game_id, $bt, $et);
try{
    $data = $dis->ShowSumRmbUse($serverids, $fenbaoids, $offset, $pageSize);
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter =true;
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
                                <th>元宝总数</th>
                                <th>充值产出数</th>
                                <th>系统产出数</th>
                                <th>消耗元宝数</th>
                                <th>消耗人数</th>
                                <th>元宝剩余</th>
                                <th>日期</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($data['list'])):?>
                                <?php foreach($data['list'] as $key=>$list):?>
                                    <tr>
                                        <td><?php echo $list['rmb_sum']?></td>
                                        <td><?php echo $list['rmb_pay']?></td>
                                        <td><?php echo $list['rmb_sys']?></td>
                                        <td><?php echo $list['rmb_used']?></td>
                                        <td><?php echo $list['cnt']?></td>
                                        <td><?php echo $list['rmb_left']?></td>
                                        <td><?php echo date('Y-m-d',strtotime($list['sday']));?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="6">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr><td colspan="6"><?php page($data['total'],$currentPage,$pageSize);?></td></tr>
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