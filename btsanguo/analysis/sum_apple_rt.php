<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:21
 * IOS数据统计(实时)
 */
include 'header.php';
$sdb   = db('gamedata_ios');

$bt = !isset($_GET['bt']) ? date('Y-m-d H:i:00', strtotime('-15 mins')) : $_GET['bt'];
$et = !isset($_GET['et']) ? date('Y-m-d H:i:00') : $_GET['et'];

$ios = new IOS($db_sum, $sdb, $bt, $et, $serverids, $fenbaoids);
//$data = $ios->Player();
$player = $ios->Player();
//print_r($player);
$pay    = $ios->Pay();
$data   = count($pay) > 0 ? array_merge_recursive($player, $pay) : $player;
//print_r($data);
$time_format = ' hh:mm:ss';
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
                                <th><?=$lang['channel']?></th>
                                <th><?=$lang['s_reg_num']?></th>
                                <th><?=$lang['s_cre_num']?></th>
                                <th><?=$lang['s_cre_rate']?></th>
                                <th><?=$lang['s_pay_money']?></th>
                                <th><?=$lang['s_pay_nop']?></th>
                                <th><?=$lang['s_pay_times']?></th>
                                <th><?=$lang['s_pay_nop_new']?></th>
                                <th><?=$lang['s_pay_nop_new_money']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data as $key=>$val):?>
                                <tr>
                                    <td><?=$key;?></td>
                                    <td><?=$val['reg']?></td>
                                    <td><?=$val['role']?></td>
                                    <td><?=$val['reg']>0 ? round($val['role']/$val['reg'], 4) : 0;?></td>
                                    <td><?=$val['all_rmb']?></td>
                                    <td><?=$val['all_cnt']?></td>
                                    <td><?=$val['all_times']?></td>
                                    <td><?=$val['first_cnt']?></td>
                                    <td><?=$val['first_rmb']?></td>

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
<?php
include 'footer.php';
?>