<?php
$pageHeader = '活跃玩家平均在线时长';
$initDbSource = true;
include 'header.php';
if (!empty($_GET)) {
    $timestamp = !empty($_GET['bt']) ? strtotime($_GET['bt']) : $_SERVER['REQUEST_TIME'];
    $timestamp_2 = !empty($_GET['et']) ? strtotime($_GET['et']) : $_SERVER['REQUEST_TIME'];
    $s_date = !empty($_GET['bt']) ? date('1ymd',$timestamp) : date('1ymd', $timestamp);
    $e_date = !empty($_GET['et']) ? date('1ymd',$timestamp_2) : date('1ymd', $timestamp_2);
    $month  = date('m', $timestamp);
    $year   = date('Y', $timestamp);
    $current_month = date('m', $_SERVER['REQUEST_TIME']);
    $month_table = '';
    $back_month = array(3,6,9,12);
    $mm = '06';
    if ($year==2015 && $month!=$current_month ) {
        $month_table = "_{$month}";
    }
    elseif (!in_array($month,$back_month) || $month!=$current_month) {
        foreach ($back_month as $k => $m) {
            if ($m==$month) {
                $mm = $back_month[$k+1];
                break;
            }
            if ($month > $back_month[$k-1] && $month < $m) {
                $mm = $m;
                break;
            }
        }
        $mm = str_pad($mm, 2, '0', STR_PAD_LEFT);
        $month_table = "_{$year}{$mm}01";
    }
    $s_date_1 = !empty($_GET['bt']) ? date('ymd0000',$timestamp) : date('ymd0000',$timestamp);
    $s_date_2 = !empty($_GET['et']) ? date('ymd2359',$timestamp) : date('ymd2359',$timestamp);
    $where = "daytime>=$s_date and daytime<=$e_date";
    $bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
    $where .= !empty($_GET['server_id']) && $_GET['server_id']>0 ? " AND serverid=".intval($_GET['server_id']) : '';
    $where .= !empty($_GET['vip_lev']) && is_numeric($_GET['vip_lev']) ? " AND viplev={$_GET['vip_lev']}":" AND viplev>0";
    $sql = "select count(*) as cnt,viplev from dayonline{$month_table} where {$where} group by viplev ORDER BY viplev ASC";
    //echo $sql;exit;
    $stmt = $db_source->prepare($sql);
    $stmt->execute();
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        $lists[$row['viplev']]['cnt'] = $row['cnt'];
    }
    if (!count($lists)) {
        echo '查询条件:'.$sql;
        exit('查找不到数据，请更换查询条件');
    }
//exit;
    $sql = "SELECT accountid,viplev FROM dayonline{$month_table} WHERE daytime>=$s_date and daytime<=$e_date AND viplev IN (".implode(',', array_keys($lists)).")";
    $stmt = $db_source->prepare($sql);
    $stmt->execute();
    while ($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        if (isset($test[$tmp['accountid']])) {
//            $esist[] = $tmp['accountid'];
//        } else {
//            $test[$tmp['accountid']] = 0;
//        }
        $account_vip_list[$tmp['viplev']][] = $tmp['accountid'];
    }
    if ($_GET['debug']) {
        echo '<pre>';
        print_r($esist);

        echo '</pre>';
        exit;
    }
//    ksort($account_vip_list);
//    print_r(array_keys($account_vip_list));exit;
    // print_r($account_vip_list);exit;
    // $accountIdList = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($account_vip_list as $key => $accountIdList) {
        // $sql = "SELECT accountid FROM dayonline WHERE daytime=$s_date AND viplev=$key ";
        // $stmt = $db_source->prepare($sql);
        // $stmt->execute();
        // $accountIdList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!$accountIdList) continue;
        $accountIdStr  = implode(',', $accountIdList);
        // $cnt           = count($accountIdList);

        //emoney_in
        $sql = <<< SQL
            SELECT SUM(emoney_in) AS sum_emoney,sum(`emoney_hold`) as emoney_hold,
            SUM(emoney_out) as emoney_out FROM player_currency_status 
            WHERE  accountid IN($accountIdStr) AND daytime>=$s_date and daytime<=$e_date
SQL;
        $stmt = $db_source->prepare($sql);
        $stmt->execute();
        $emoney_row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lists[$key]['emoney_in'] = $emoney_row['sum_emoney'];
        $lists[$key]['emoney_hold'] = $emoney_row['emoney_hold'];//总持有元宝
        $lists[$key]['emoney_out'] = $emoney_row['emoney_out'];//总消费元宝

        //pay
        $sql_sum_pay = "SELECT SUM(price) AS sum_emoney FROM pay WHERE accountid IN($accountIdStr) AND (daytime BETWEEN $s_date_1 AND $s_date_2)";
        $stmt = $db_source->prepare($sql_sum_pay);
        $stmt->execute();
        $lists[$key]['sum_pay'] = $stmt->fetchColumn(0);

        $lists[$key]['sum_give'] =  $lists[$key]['emoney_in'] - $lists[$key]['sum_pay'];
//    $lists[$key]['avg_pay'] = $lists[$key]['sum_pay'] / $lists[$lvl]['cnt'];
        //give_emoney
       $sql_give_emoney = <<<SQL
SELECT SUM(emoney) FROM `give_emoney{$month_table}` g LEFT JOIN dayonline{$month_table} d
ON d.userid=g.idUser and d.serverid=g.serverid WHERE d.viplev=$key AND d.daytime>=$s_date and d.daytime<=$e_date
 AND g.daytime>=$s_date and g.daytime<=$e_date AND g.type=6
SQL;
        if ($_GET['debug']) {
            echo '<pre>';
            echo $key,'------','<br/>';
            echo $sql_give_emoney;
            echo '</pre>';
        }
//        exit;
       $stmt = $db_source->prepare($sql_give_emoney);
       $stmt->execute();
       $lists[$key]['sum_yunyin_give'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_give'] = $lists[$key]['sum_give'] / $lists[$lvl]['cnt'];
        //rmb
        // $sql_rmb = "SELECT SUM(emoney) FROM rmb WHERE accountid IN($accountIdStr) AND (daytime BETWEEN $s_date_1 AND $s_date_2)";

        // $stmt = $db_source->prepare($sql_rmb);
        // $stmt->execute();
        // $lists[$key]['sum_rmb'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_rmb'] = $lists[$key]['sum_rmb'] / $lists[$lvl]['cnt'];
    }
}

$noEndTimeFilter = true;
$noServerFilter  = true;
$noFenbaoFilter  = true;
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form id="frm" class="form-inline" role="form" method="get">
                        <div class="form-group">
                            <label><?=$prefixTime?>时间</label>
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">至<input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>VIP 等级</label>
                            <input name="vip_lev"  style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['vip_lev']?>">
                        </div>
                        <div class="form-group">
                            <label><?=$lang['server']?></label>
                            <?php echo htmlSelect($serversList, 'server_id', $_GET['server_id']);?>
                        </div>
                        <button type="submit" id="BtnSearch" class="btn btn-primary">SEARCH</button>
                    </form>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>等级段</th>
                                <th>活跃人数</th>
                                <th>总充值元宝</th>
                                <th>人均充值元宝</th>
                                <th>游戏总获取元宝</th>
                                <th>游戏人均获取元宝</th>
                                <th>总消费元宝</th>
                                <th>人均消费元宝</th>
                                <th>剩余持有总元宝</th>
                                <th>人均剩余持有元宝</th>
                                <th>运营活动获取元宝</th>
                                <th>人均运营活动获取元宝</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                            <?php foreach($lists as $lev=>$list):?>
                                <tr>
                                    <td>VIP <?=$lev?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['sum_pay']?></td>
                                    <td><?=ceil($list['sum_pay']/$list['cnt'])?></td>
                                    <td><?=$list['sum_give']?></td>
                                    <td><?=ceil($list['sum_give']/$list['cnt'])?></td>
                                    <td><?=$list['emoney_out']?></td>
                                    <td><?=ceil($list['emoney_out']/$list['cnt'])?></td>
                                    <td><?=$list['emoney_hold']?></td>
                                    <td><?=ceil($list['emoney_hold']/$list['cnt'])?></td> 
                                    <td><?=$list['sum_yunyin_give']?></td>
                                    <td><?=$list['cnt']> 0 ? ceil($list['sum_yunyin_give']/$list['cnt']):0?></td>                                
                                </tr>
                            <?php endforeach;?>
                            <?php endif;?>
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
<?php include 'footer.php';?>