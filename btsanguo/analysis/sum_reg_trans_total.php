<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-3
 * Time: 上午10:14
 * 注册转化统计
 */
include 'header.php';

//$db_sum  = db('analysis');
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-6 days'));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d');
$dis = new Display($db_sum, $game_id, $bt, $et);


try{
//    $total_rows = $dis->SumRegTransTotal( $bt, $et, $serversid, $fenBaoID, 0,0 , true);
    $data = $dis->SumRegTransTotal( $serverids, $fenbaoids, $offset, $pageSize);
    $lists = $data['list'];
    $total_rows = $data['total'];
} catch(Exception $e) {
    echo $e->getMessage();
}
//军师、 箭神、 剑舞、龙将、狂刀
$profArr = array(1=>'龙将', 2=>'剑舞',3=>'箭神',4=>'军师','狂刀');
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
                                <th><?=$lang['date']?></th>
                                <th><?=$lang['prof']?></th>
                                <th><?=$lang['s_reg_num']?></th>
                                <th><?=$lang['s_cre_num']?></th>
                                <th><?=$lang['s_cre_rate']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <?php if(!$list['sum_new']) continue;?>
                                    <tr>
                                        <td><?php echo date('Y-m-d', strtotime($list['sday']));?></td>
                                        <td><?php echo $profArr[$list['prof']]?>[<?=$list['prof']?>]</td>
                                        <td><?php echo $list['sum_new']?></td>
                                        <td><?php echo $list['sum_cre']?></td>
                                        <td><?php echo @round($list['sum_cre']/$list['sum_new'], 4) * 100?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:?>
                                <tr>
                                    <td colspan="5">没有相关数据。。。</td>
                                </tr>
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
<?php include 'footer.php';?>