<?php
$pageHeader = '活跃玩家平均在线时长';
$initDbSource = true;
include 'header.php';

$lev_diff = array(0, 10, 20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170 );
//时间等级
$lvl_list = array(
    '1-10', '11-20', '21-30',
    '31-40',  '41-50', '51-60',
    '61-70', '71-80','81-90',
    '91-100','101-110','111-120',
    '121-130','131-140','141-150',
    '151-160','161-170',
);
$lists = array();
//exit;
//$lev_diff = array(0, 10);
////时间等级
//$lvl_list = array(
//    '1-10'
//);
if (!empty($_GET)) {
    $timestamp = !empty($_GET['bt']) ? strtotime($_GET['bt']) : $_SERVER['REQUEST_TIME'];
    $s_date = !empty($_GET['bt']) ? date('1ymd',$timestamp) : date('1ymd', $timestamp);
    $month  = date('m', $timestamp);
    $current_month = date('m', $_SERVER['REQUEST_TIME']);

    $month_table = '';
    if ($current_month==9 && $month!=$current_month) {
        $month_table = "_{$month}";
    }
//    $s_date = !empty($_GET['bt']) ? date('1ymd',strtotime($_GET['bt'])) : date('1ymd',$_SERVER['REQUEST_TIME']);
    $where = "daytime=$s_date";
    $bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
    $where .= !empty($_GET['server_id']) && $_GET['server_id']>0 ? " AND serverid=".intval($_GET['server_id']) : '';


    if ($_GET['min_lvl']>0 && is_numeric($_GET['min_lvl']) && $_GET['max_lvl']>0 && is_numeric($_GET['max_lvl'])) {
        $flag = true;
        $max_lvl = $_GET['max_lvl'];
        $min_lvl = $_GET['min_lvl'];
        $where .= " AND lev>={$min_lvl} AND lev<=$max_lvl";
        $sql = "select count(*) as cnt,lev from dayonline{$month_table} where {$where} group by lev ORDER BY lev ASC";
//        echo $sql;
        $stmt = $db_source->prepare($sql);
        $stmt->execute();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $lists[$row['lev']]['cnt'] += $row['cnt'];
        }
//    if (isset($max_lvl) && isset($min_lvl)) {
//        $use_lvl_list = array("{$min_lvl}-{$max_lvl}");
//    } else{
//        $use_lvl_list = $lvl_list;
//    }
//exit;
        foreach ($lists as $key => $lvl_seg) {
        list($lvl_start, $lvl_end) = explode('-', $lvl_seg);
            $sql = "SELECT accountid FROM dayonline{$month_table} WHERE daytime=$s_date AND lev=$key";
            $stmt = $db_source->prepare($sql);
            $stmt->execute();
            $accountIdList = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!$accountIdList) continue;
            $accountIdStr  = implode(',', $accountIdList);
            $cnt           = count($accountIdList);
            //pay
            $sql_sum_pay = "SELECT SUM(price) AS sum_emoney FROM pay WHERE accountid IN($accountIdStr)";
            $stmt = $db_source->prepare($sql_sum_pay);
            $stmt->execute();
            $lists[$key]['sum_pay'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_pay'] = $lists[$key]['sum_pay'] / $lists[$lvl]['cnt'];
            //give_emoney
            $sql_give_emoney = <<<SQL
SELECT SUM(emoney) FROM give_emoney g LEFT JOIN dayonline d
ON d.userid=g.idUser and d.serverid=g.serverid WHERE d.accountid IN($accountIdStr)
SQL;
            $stmt = $db_source->prepare($sql_give_emoney);
            $stmt->execute();
            $lists[$key]['sum_give'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_give'] = $lists[$key]['sum_give'] / $lists[$lvl]['cnt'];
            //rmb
            $sql_rmb = "SELECT SUM(emoney) FROM rmb WHERE accountid IN($accountIdStr)";
            $stmt = $db_source->prepare($sql_rmb);
            $stmt->execute();
            $lists[$key]['sum_rmb'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_rmb'] = $lists[$key]['sum_rmb'] / $lists[$lvl]['cnt'];

            $sql_give_emoney = <<<SQL
SELECT SUM(emoney) FROM `give_emoney{$month_table}` g LEFT JOIN dayonline{$month_table} d
ON d.userid=g.idUser and d.serverid=g.serverid WHERE (d.lev>={$min_lvl} AND d.lev<=$max_lvl) AND d.daytime=$s_date
 AND g.daytime=$s_date AND g.type=6
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
        }
    }
    else {
        $sql = "select count(*) as cnt,lev from dayonline{$month_table} where {$where} group by lev ORDER BY lev ASC";
		if ($_GET['debug']) echo $sql;
        $stmt = $db_source->prepare($sql);
        $stmt->execute();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['lev']==0) {
                $lvl = 0;
            } else {
                $lvl = halfSearch($lev_diff, $row['lev']);
            }
            $lists[$lvl]['cnt'] += $row['cnt'];
        }
		if ($_GET['debug'] && $_GET['exit']) {print_r($lists);exit;}
        foreach ($lvl_list as $key => $lvl_seg) {
            list($lvl_start, $lvl_end) = explode('-', $lvl_seg);
            $sql = "SELECT accountid FROM dayonline{$month_table} WHERE daytime=$s_date AND lev>=$lvl_start AND lev<=$lvl_end";
            $stmt = $db_source->prepare($sql);
            $stmt->execute();
            $accountIdList = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!$accountIdList) continue;
            $accountIdStr  = implode(',', $accountIdList);
            $cnt           = count($accountIdList);

            //emoney_in
            $sql = "SELECT SUM(emoney_in) AS sum_emoney FROM player_currency_status WHERE  accountid IN($accountIdStr)";
            $stmt = $db_source->prepare($sql);
            $stmt->execute();
            $lists[$key]['emoney_in'] = $stmt->fetchColumn(0);
            //pay
            $sql_sum_pay = "SELECT SUM(price) AS sum_emoney FROM pay WHERE accountid IN($accountIdStr)";
            $stmt = $db_source->prepare($sql_sum_pay);
            $stmt->execute();
            $lists[$key]['sum_pay'] = $stmt->fetchColumn(0);

            $lists[$key]['sum_give'] =  $lists[$key]['emoney_in'] - $lists[$key]['sum_pay'];

            $sql_give_emoney = <<<SQL
SELECT SUM(emoney) FROM `give_emoney{$month_table}` g LEFT JOIN dayonline{$month_table} d
ON d.userid=g.idUser and d.serverid=g.serverid WHERE (d.lev between $lvl_start AND $lvl_end) AND d.daytime=$s_date
 AND g.daytime=$s_date AND g.type=6
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
//    $lists[$key]['avg_pay'] = $lists[$key]['sum_pay'] / $lists[$lvl]['cnt'];
            //give_emoney
//            $sql_give_emoney = <<<SQL
//SELECT SUM(emoney) FROM give_emoney g LEFT JOIN dayonline d
//ON d.userid=g.idUser and d.serverid=g.serverid WHERE d.accountid IN($accountIdStr)
//SQL;
//            $stmt = $db_source->prepare($sql_give_emoney);
//            $stmt->execute();
//            $lists[$key]['sum_give'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_give'] = $lists[$key]['sum_give'] / $lists[$lvl]['cnt'];
            //rmb
            $sql_rmb = "SELECT SUM(emoney) FROM rmb WHERE accountid IN($accountIdStr)";
            $stmt = $db_source->prepare($sql_rmb);
            $stmt->execute();
            $lists[$key]['sum_rmb'] = $stmt->fetchColumn(0);
//    $lists[$key]['avg_rmb'] = $lists[$key]['sum_rmb'] / $lists[$lvl]['cnt'];
        }
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
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>等级</label>
                            <input name="min_lvl" style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['min_lvl']?>" >
                            至
                            <input name="max_lvl" style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['max_lvl']?>">
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
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $lev=>$list):?>
                                <tr>
                                    <td><?=$flag===true ? $lev :$lvl_list[$lev]?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['sum_pay']?></td>
                                    <td><?=$list['cnt']? ceil($list['sum_pay']/$list['cnt']) : 0?></td>
                                    <td><?=$list['sum_give']?></td>
                                    <td><?=$list['cnt']? ceil($list['sum_give']/$list['cnt']) : 0?></td>
                                    <td><?=$list['sum_rmb']?></td>
                                    <td><?=$list['cnt']? ceil($list['sum_rmb']/$list['cnt']) : 0?></td>
                                    <td>0</td>
                                    <td>0</td>
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
<?php include 'footer.php';?>