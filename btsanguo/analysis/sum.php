<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:21
 */
$pageHeader = '总数据';
include 'header.php';
if (date('d')=='01') {
	$et = date('Y-m-d');
}
$bt = !empty($_GET['bt']) ? $_GET['bt'] : (date('d')=='01' ? date('Y-m-d', strtotime('-1 months')):date('Y-m-01'));
//$db_sum  = db('analysis');
$sum = new DisplaySummary($db_sum, $game_id, $bt, $et);
//var_dump($serverids);
$data = $sum->Show($serverids, $fenbaoids, $offset, $pageSize );
$where = '';
$sql_month_pay = <<<SQL
SELECT sday,sum(total_pay) as total_pay, sum(total_money) as total_money,
 sum(first_pay) as first_pay,sum(first_money) as first_money FROM sum_month_pay
  WHERE (sday BETWEEN ? AND ?) %where% GROUP BY sday
SQL;
var_dump($serverids);
if (!empty($serverids) ) {
    $lenServer = count($serverids);
    if ($lenServer==1) {
        $where = " AND server_id=" . array_shift($serverids);
    }
    elseif ($lenServer>1) {
        $where = " AND server_id IN(" . implode(',', $serverids).')';
    }
//    echo $where;
    $sql_month_pay .= ",server_id";
}
$sql_month_pay = str_replace('%where%', $where, $sql_month_pay);

//echo $sql_month_pay;
$stmt = $db_sum->prepare($sql_month_pay);
$stmt->execute(array(date('Ymd', strtotime($bt)), date('Ymd', strtotime($et))));
while($tmp_row =  $stmt->fetch(PDO::FETCH_ASSOC)) {
    $month_pay[$tmp_row['sday']] = $tmp_row;
}
//extract($month_pay);
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
                                <th><?=$lang['server']?></th>
                                <th><?=$lang['channel']?></th>
                                <th><?=$lang['s_new_login']?>/<?=$lang['s_reg_num']?></th>
                                <th>月付费人数</th>
                                <th>累计金额</th>
                                <th>月新付费人数</th>
                                <th>新付费累计金额</th>
                                <th><?=$lang['s_cre_num']?></th>
                                <th><?=$lang['s_cre_rate']?></th>
                                <th>DAU</th>
                                <th>WAU</th>
                                <th>MAU</th>
                                <th><?=$lang['s_online_max']?></th>
                                <th><?=$lang['s_online_avg']?></th>
                                <th><?=$lang['s_pay_nop']?></th>
                                <th><?=$lang['s_pay_money']?></th>
                                <th><?=$lang['s_pay_nop_new']?></th>
                                <th><?=$lang['s_pay_nop_new_money']?></th>
                                <th><?=$lang['s_remain_day1']?></th>
                                <th><?=$lang['s_remain_day3']?></th>
                                <th><?=$lang['s_remain_day7']?></th>
                                <th><?=$lang['s_remain_day15']?></th>
                                <th><?=$lang['s_remain_day30']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($data['list'])):?>
                                <?php foreach($data['list'] as $l):?>
                                    <tr>
                                        <td><?=date('Y-m-d', strtotime($l['sday']));?></td>
                                        <td><?=$l['serverid']?></td>
                                        <td><?=$l['fenbaoid']?></td>
                                        <td><?=$l['nl_cnt']?></td>
                                        <td><?=$month_pay[$l['sday']]['total_pay']?></td>
                                        <td><?=$month_pay[$l['sday']]['total_money']?></td>
                                        <td><?=$month_pay[$l['sday']]['first_pay']?></td>
                                        <td><?=$month_pay[$l['sday']]['first_money']?></td>
                                        <td><?=$l['role_cnt']?></td>
                                        <td><?=@round($l['role_cnt']/$l['nl_cnt'], 4) * 100?>%</td>
                                        <td><?=$l['dau']?></td>
                                        <td><?=$l['wau']?></td>
                                        <td><?=$l['mau']?></td>
                                        <td><?=$data['ol'][$l['sday']]['maxol']?></td>
                                        <td><?=$data['ol'][$l['sday']]['avgol']?></td>
                                        <td><?=$l['pay_nop']?></td>
                                        <td><?=$l['income_cnt']?></td>
                                        <td><?=$l['pay_nop_n']?></td>
                                        <td><?=$l['pay_nop_nm']?></td>
                                <?php if($data['type']=='all'):?>
                                        <td>
                                            <?=$data['um'][$l['sday']]['day1']?>
                                            |<?=@round($data['um'][$l['sday']]['day1']/$l['nl_cnt'],4)*100?>%
                                        </td>
                                        <td>
                                            <?=$data['um'][$l['sday']]['day3']?>
                                            |<?=@round($data['um'][$l['sday']]['day3']/$l['nl_cnt'],4)*100?>%
                                        </td>
                                        <td>
                                            <?=$data['um'][$l['sday']]['day7']?>
                                            |<?=@round($data['um'][$l['sday']]['day7']/$l['nl_cnt'],4)*100?>%
                                        </td>
                                        <td>
                                            <?=$data['um'][$l['sday']]['day15']?>
                                            | <?=@round($data['um'][$l['sday']]['day15']/$l['nl_cnt'],4)*100?>%
                                        </td>
                                        <td>
                                            <?=$data['um'][$l['sday']]['day30']?>
                                            | <?=@round($data['um'][$l['sday']]['day30']/$l['nl_cnt'],4)*100?>%
                                        </td>
                                <?php else:?>
                                    <td>
                                        <?=$data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day1']?>
                                        |<?=@round($data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day1']/$l['nl_cnt'],4)*100?>%
                                    </td>
                                    <td>
                                        <?=$data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day3']?>
                                        |<?=@round($data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day3']/$l['nl_cnt'],4)*100?>%
                                    </td>
                                    <td>
                                        <?=$data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day7']?>
                                        |<?=@round($data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day7']/$l['nl_cnt'],4)*100?>%
                                    </td>
                                    <td>
                                        <?=$data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day15']?>
                                        | <?=@round($data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day15']/$l['nl_cnt'],4)*100?>%
                                    </td>
                                    <td>
                                        <?=$data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day30']?>
                                        | <?=@round($data['um'][$l['sday'].'_'.$l['serverid'].'_'.$l['fenbaoid']]['day30']/$l['nl_cnt'],4)*100?>%
                                    </td>
                                <?php endif;?>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr>
                                    <td colspan="21">没有数据</td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="21"><?php page($data['total'],$currentPage,$pageSize);?></td>
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