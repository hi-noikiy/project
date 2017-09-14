<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-3
 * Time: 上午10:14
 */
$pageHeader = '在线统计';
include 'header.php';

//数据库连接
//$db_sum  = db('analysis');
$dis = new Display($db_sum);
$total_rows = 0;
$lists = array();
try{
    $data = $dis->ShowOnlineBefore( $bt, $et, $serverids, $offset, $pageSize);
    $lists = $data['list'];
    $total_rows = $data['total'];
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter = true;
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th><?=$lang['date']?></th>
                                <th><?=$lang['online_cnt']?></th>
                                <th><?=$lang['online_avg']?></th>
                                <th><?=$lang['s_online_max']?></th>
                                <th><?=$lang['server']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <tr>
                                        <td><?php echo date('Y-m-d', strtotime($list['sday']));?></td>
                                        <td><?php echo $list['sum_maxOnline']?></td>
                                        <td><?php echo $list['avg_online']?></td>
                                        <td><?php echo $list['sum_worldMaxOnline']?></td>
                                        <td><?php echo $serversList[$list['serverid']]?>[<?=$list['serverid']?>]</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:?>
                                <tr>
                                    <td colspan="6"><?=$lang['no_data']?></td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6"><?php page($total_rows,$currentPage,$pageSize);?></td>
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
<?php include 'footer.php';?>