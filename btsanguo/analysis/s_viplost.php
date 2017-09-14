<?php
//$pageHeader = '商城消费';
$initDbSource = 1;
include 'header.php';
//$db_sum  = db('analysis');
//$dis = new Display($db_sum, $game_id, $bt, $et);
$timestamp = !empty($_GET['bt']) ? strtotime($_GET['bt']) : strtotime('-1 days');
//$month  = date('m', $timestamp);
$year   = date('Y', $timestamp);
$month  = date('m', $timestamp);
$current_month = date('m', $_SERVER['REQUEST_TIME']);
$month_table = '';
$back_month = array(3,6,9,12);
//if (isset($_GET['month']) && $_GET['month']>0 && $month_table != $current_month) {
//    $month = str_pad(intval($_GET['month']),'2', '0');
//    $month_table = '_'. $year. $month . '01';
//}
//else
//if (isset($_GET['bt']) && !in_array($month,$back_month) || $month!=$current_month) {
//    foreach ($back_month as $k => $m) {
//        if ($m==$month) {
//            $mm = $back_month[$k+1];
//            break;
//        }
//        if ($month > $back_month[$k-1] && $month < $m) {
//            $mm = $m;
//            break;
//        }
//    }
//    $mm = str_pad($mm, 2, '0', STR_PAD_LEFT);
//    $month_table = "_{$year}{$mm}01";
//}
$show_create = '';
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  strtotime("-10 days"));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d');

$pay_bt = !empty($_GET['pay_bt']) ? $_GET['pay_bt'] : date('Y-m-d',  strtotime("-10 days"));
$pay_et = !empty($_GET['pay_et']) ? $_GET['pay_et'] : date('Y-m-d');

$bbt = !empty($_GET['bt']) ? date('ymd0000', strtotime($_GET['bt'])) : date('ymd0000', strtotime('-10 days'));
$eet = !empty($_GET['et']) ? date('ymd2359', strtotime($_GET['et'])) : date('ymd2359', strtotime('-10 days'));

$pay_bbt = !empty($_GET['pay_bt']) ? date('ymd0000', strtotime($_GET['pay_bt'])) : date('ymd0000', strtotime('-10 days'));
$pay_eet = !empty($_GET['pay_et']) ? date('ymd2359', strtotime($_GET['pay_et'])) : date('ymd2359', strtotime('-10 days'));

$sql = "SELECT accountid FROM first_rmb{$month_table} WHERE 1=1";
//$sql = "SELECT accountid FROM first_rmb WHERE 1=1";
$where = " AND createtime BETWEEN $bbt AND $eet";
$where .= count($serverids)> 0  ? " AND serverid IN(".implode(',', $serverids).")" : '';
$sql .= $where . " ORDER BY daytime ASC";
$stmt = $db_source->prepare($sql);
if ($_GET['debug']) {
    echo 'mm:',$mm;
    echo '<pre>';
    echo $sql;
    echo '</pre>';
}
$stmt->execute();
$lists = array();
$account_id = $stmt->fetchAll(PDO::FETCH_COLUMN);

//print_r($account_id);exit;

if (count($account_id)>0) {
    $account_id_str = implode(',', $account_id);
    $sql_day_online = <<<SQL
SELECT MAX(d.daytime) AS daytime, MIN(d.daytime) as min_date, MAX(d.viplev) AS viplev,MAX(d.lev) AS lev,
d.accountid, MAX(d.total_rmb) AS total_rmb FROM dayonline{$month_table} d
LEFT JOIN  first_rmb{$month_table} f
ON d.userid=f.userid and d.serverid=f.serverid
WHERE d.accountid IN($account_id_str) AND f.createtime BETWEEN $bbt AND $eet
GROUP BY d.accountid
SQL;
    if ($_GET['debug']) {
        echo '<pre>';
        echo $sql_day_online;
        echo '</pre>';
    }


    $stmt = $db_source->prepare($sql_day_online);
    $stmt->execute();
    $total_lost = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    if ($_GET['debug']) {
//        echo '<pre>';
//        1150820 $row['daytime']-1000000 .'---'.
//        echo $row['daytime'],'-----',$row['daytime']-1000000+20000000 ,'----',strtotime($row['daytime']-1000000),'-----',date('Y-m-d', strtotime($row['daytime']-1000000+20000000));
//        echo '</pre>';
//    }
        $max_date = strtotime($row['daytime']-1000000+20000000);
        $min_date = strtotime($row['min_date']-1000000+20000000);
        if ( ($_SERVER['REQUEST_TIME'] - $max_date) / 86400 > 10) {
            $lists[$row['viplev']]['total']     += 1;
            $lists[$row['viplev']]['diff_date'] += ($max_date - $min_date) / 86400;
            $total_lost += 1;
            $lists[$row['viplev']]['total_pay'] += $row['total_rmb'] / 10;//是元宝要除以10
            $lists[$row['viplev']]['total_lev'] += $row['lev'];
            if ($row['viplev']==11) {
                $vip_account[] = $row['accountid'];
            }
        }
//    $day = date('y-m-d',strtotime('20'.$row['daytime']));
//    $lists[$day]['total']   += 1;
//    $lists[$day]['lev']     += $row['lev'];
//    $lists[$day]['money']   += $row['payemoney'] / 10;
    }
    $prefixTime = '创建';
    ksort($lists);
    if ($_GET['debug']) {
        echo '<pre>';
        print_r($lists);
        echo '</pre>';
    }
}
//echo count($account_id);
//echo PHP_EOL;


//exit;
//print_r($lists);
$noFenbaoFilter = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <p>“查询月份”，表示从哪个月份里获取登录的数据,
                        如果选择11月份，那么就是用户11月份的数据了。</p>
                    <form class="form-inline" role="form" action="<?=$action;?>">
                        <div class="form-group">
                            <label>查询月份</label>
                            <input style="width: 80px;" name="month" type="number" class="form-control"
                                   size="2" value="<?=$month?>">
                        </div>
                        <div class="form-group">
                            <label><?=$prefixTime?>时间</label>
                            <input name="bt" style="widty:160px;"  type="text" class="form-control"
                                   size="18" value="<?=$bt?>"
                                   onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            <?php if(!isset($noEndTimeFilter)):?>
                                --
                                <input name="et" style="widty:160px;" type="text"
                                       class="form-control" size="18" value="<?=$et?>"
                                       onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            <?php endif;?>
                        </div>
                        <div class="form-group">
                            <label>区服ID</label>
                            <input type="number" style="width:120px;" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="12"/>至
                            <input type="number" style="width:120px;" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="12"/>
                        </div>
                        <button type="submit" class="btn btn-primary">查 询</button>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div>充值人数：<?=$total_pay = count($account_id)?>; 流失人数：<?=$total_lost?>
                            流失率：<?=round($total_lost/$total_pay, 2)?>(流失人数/充值人数)
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>VIP 等级</th>
                                <th>人数</th>
                                <th>平均付费</th>
                                <th>平均等级</th>
                                <th>平均生命周期(天)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $vip=>$list):?>
                                    <tr>
                                        <td>VIP <?=$vip?></td>
                                        <td><?=$list['total']?></td>
                                        <td><?=ceil($list['total_pay'] / $list['total'])?></td>
                                        <td><?=ceil($list['total_lev'] / $list['total'])?></td>
                                        <td><?=ceil($list['diff_date'] / $list['total']) + 1?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="7">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <pre>
                        VIP11 account id:<?php print_r($vip_account);?>
                    </pre>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php include 'footer.php'; ?>