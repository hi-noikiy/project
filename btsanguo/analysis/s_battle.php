<?php
$initDbSource = true;
include 'header.php';

$lev_diff = array(0, 10, 20,30,40,50,60,70,80,90,100,110,120,130,140,150, );
//时间等级
$lvl_list = array(
    '1-10', '11-20', '21-30',
    '31-40',  '41-50', '51-60',
    '61-70', '71-80','81-90',
    '91-100','101-110','111-120',
    '121-130','131-140','141-150',
    '151-160',
);
$lists = array();
$bbt = !empty($_GET['bt']) ? date('1ymd', strtotime($_GET['bt'])) : date('1ymd', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['et']) ? date('1ymd', strtotime($_GET['et'])) : date('1ymd', $_SERVER['REQUEST_TIME']);
$lists = array();
$sql_total = "SELECT COUNT(DISTINCT account_id) AS cnt,user_lev FROM battle_time WHERE daytime=$bbt GROUP BY user_lev";
$stmt = $db_source->prepare($sql_total);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    foreach($lvl_list as $key=>$lvl_seg) {
        list($lvl_start, $lvl_end) = explode('-', $lvl_seg);
        if ($row['user_lev']>=$lvl_start && $row['user_lev']<=$lvl_end) {
            $_key = $key;
            break;
        }
    }
    $lists[$_key]['cnt']     += $row['cnt'];
}

$sql = <<<SQL
SELECT user_lev,map_type,SUM(spend_time) AS spend_time FROM battle_time
WHERE daytime = $bbt GROUP BY user_lev,map_type ORDER BY user_lev ASC
SQL;
// echo $sql;
$stmt = $db_source->prepare($sql);
//echo $sql;
$stmt->execute();
//$lists = array(0,1,2,4,5);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    foreach($lvl_list as $key=>$lvl_seg) {
        list($lvl_start, $lvl_end) = explode('-', $lvl_seg);
        if ($row['user_lev']>=$lvl_start && $row['user_lev']<=$lvl_end) {
            $_key = $key;
            break;
        }
    }
    // $lists[$_key]['cnt']        += $row['cnt'];
    $lists[$_key]['map_type'][$row['map_type']] += $row['spend_time'];
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
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>等级段</th>
                                <th>人数</th>
                                <th>过关斩将</th>
                                <th>决斗神殿</th>
                                <th>一骑当千</th>
                                <th>演武场</th>
                                <th>竞技挑战</th>
                                <th>魔王副本</th>
                                <th>精英副本</th>
                                <th>黄金守卫</th>
                                <th>军团征战</th>
                                <th>魔将入侵</th>
                                <th>群雄争霸</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $lev=>$list):?>
                                <tr>
                                <td><?=$lvl_list[$lev]?></td>
                                <td><?=$list['cnt']?></td>
                                <td><?=round($list['map_type'][0]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][1]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][2]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][3]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][4]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][5]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][6]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][7]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][8]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][9]/$list['cnt'],4)?></td>
                                <td><?=round($list['map_type'][10]/$list['cnt'],4)?></td>
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