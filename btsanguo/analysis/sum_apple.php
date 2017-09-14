<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:21
 */
include 'header.php';
$db_ios = db('analysis_ios');
$sum = new DisplaySummary($db_sum, $game_id, $bt, $et);
$data = $sum->Show($serverids, $fenbaoids, $offset, $pageSize, true );
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
                                <th><?=$lang['s_datetime']?></th>
                                <th><?=$lang['s_reg_num']?></th>
                                <th><?=$lang['s_cre_num']?></th>
                                <th><?=$lang['s_cre_rate']?></th>
                                <th>DAU</th>
                                <th>WAU</th>
                                <th>MAU</th>
                                <th><?=$lang['s_new_login']?></th>
                                <th><?=$lang['s_pay_money']?></th>
                                <th><?=$lang['s_pay_nop']?></th>
                                <th><?=$lang['s_pay_nop_new']?></th>
                                <th><?=$lang['s_pay_times']?></th>
                                <th><?=$lang['s_pay_rate']?></th>
                                <th><?=$lang['s_pay_arpu']?></th>
                                <th><?=$lang['s_reg_arpu']?></th>
                                <th><?=$lang['s_remain_day1']?></th>
                                <th><?=$lang['s_remain_day3']?></th>
                                <th><?=$lang['s_remain_day7']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($data['list'])):?>
                                <?php foreach($data['list'] as $l):?>
                                <tr>
                                    <td><?=date('Y-m-d', strtotime($l['sday']));?></td>
                                    <td><?=$l['reg_cnt']?></td>
                                    <td><?=$l['nl_cnt']?></td>
                                    <td><?=$l['role_cnt']?></td>
                                    <td><?=$l['role_cnt']/$l['reg_cnt']?></td>
                                    <td><?=$l['dau']?></td>
                                    <td><?=$l['wau']?></td>
                                    <td><?=$l['mau']?></td>
                                    <td><?=$l['income_cnt']?></td>
                                    <td><?=$l['pay_nop']?></td>
                                    <td><?=$l['pay_nop_n']?></td>
                                    <td><?=$l['pay_cnt']?></td>
                                    <td><?=$l['pay_rate']?></td>
                                    <td><?=$l['arpu']?></td>
                                    <td><?=$l['reg_arpu']?></td>
                                    <td><?=$um[$l['sday']]['day1']?></td>
                                    <td><?=$um[$l['sday']]['day3']?></td>
                                    <td><?=$um[$l['sday']]['day7']?></td>
                                </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="18"><?php page($data['total'],$currentPage,$pageSize);?></td>
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
<?php
include 'footer.php';
?>