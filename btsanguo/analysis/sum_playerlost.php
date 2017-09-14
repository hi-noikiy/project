<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户留存
 */
$pageHeader = '流失';
include 'header.php';
$total_rows = 0;
$lists = array();
$pageSize = 100;
//数据库连接
//$db_sum  = db('analysis');
$dis = new DisplayUser($db_sum);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-3 days'));
if ( count($serverids) ) {
    $detail = true;
    $data = $dis->ShowUserLostQuery( $bt, $serverids, $fenbaoids, $offset, $pageSize);
    $total_rows = $data['total'];
}
else {
    $data = $dis->ShowUserLostAll( $bt);
    $total_rows = count($data['list']);
}
if ($_GET['debug']) {
    echo '<pre>';
    //print_r($serverids);
    print_r($data);
    echo '</pre>';
}
$lists      = $data['list'];
$nl         = $data['nl'];
$col = 8;
$yestoday   = date('Ymd', strtotime('-1 days'));
$today      = date('Ymd');
$noEndTimeFilter = true;
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
                        <table <?=$total_rows ? 'id="dataTable"':'' ?> class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><?=$lang['lev_t']?></th>
                                <th><?=$lang['lev_p']?></th>
                                <th><?=$lang['lev_pr']?></th>
                                <th><?=$lang['lost_unlogin_1_day']?></th>
                                <th>次日流失率</th>
                                <th><?=$lang['lost_unlogin_3_day']?></th>
                                <th>3日流失率</th>
                                <?php if(count($serverids)>0):?>
                                    <?php $col=11;?>
                                    <th><?=$lang['server']?></th>
                                    <th><?=$lang['channel']?></th>
                                <?php endif;?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($total_rows):?>
                                <?php foreach($lists as $list):?>
                                    <?php $after_3day = date('Ymd', strtotime('+3 days', strtotime($list['sday'])));?>
                                    <tr>
                                        <td><?php echo $list['lev']?></td>
                                        <td><?php echo $list['nop']?></td>
                                        <td><?php echo round($list['nop']/$nl[$list['sday']],4) * 100?> %</td>
                                        <?php if($yestoday==$list['sday']):?>
                                            <td> -未统计- </td>
                                            <td> -未统计- </td>
                                        <?php else:?>
                                            <td><?php echo $list['lost_day1']== 0 ? 0 : ($lostday1 = $list['nop']-$list['lost_day1']);?> </td>
                                            <td><?php echo $list['lost_day1']==0 ? 0 :@round($lostday1/$list['nop'], 4)*100;?>%</td>
                                        <?php endif;?>
                                        <?php if($today<=$after_3day):?>
                                            <td> -未统计- </td>
                                            <td> -未统计- </td>
                                        <?php else:?>
                                            <td><?php echo $list['lost_day3']==0 ? 0 :($lostday3=$list['nop']-$list['lost_day3'])?></td>
                                            <td><?php echo $list['lost_day3']==0 ? 0 : @round($lostday3/$list['nop'], 4)*100;?>%</td>
                                        <?php endif;?>
                                        <?php if(count($serverids)>0):?>
                                            <td><?php echo $serverList[$list['serverid']];?> - <?php echo $list['serverid'];?></td>
                                            <td><?php echo $result_fenbao[$list['fenbaoid']];?> - <?php echo $list['fenbaoid'];?></td>
                                        <?php endif;?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:?>
                                <tr>
                                    <td colspan="<?php echo $col;?>">没有相关数据。。。</td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                            <?php if($detail):?>
                                <tfoot>
                                    <tr>
                                        <td colspan="<?php echo $col;?>"><?php page($total_rows,$currentPage,$pageSize);?></td>
                                    </tr>
                                </tfoot>
                            <?php endif;?>
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