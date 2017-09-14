<?php
$pageHeader = '活跃玩家平均在线时长';
$initDbSource = true;
include 'header.php';

$bbt = !empty($_GET['bt']) ? date('1ymd', strtotime($_GET['bt'])) : date('1ymd', $_SERVER['REQUEST_TIME']);
// $eet = !empty($_GET['et']) ? date('1ymd', strtotime($_GET['et'])) : date('1ymd', $_SERVER['REQUEST_TIME']);

$sql_cnt = <<<SQL
SELECT COUNT(DISTINCT account_id) AS cnt,vip_lev FROM battle_time
 WHERE daytime=$bbt AND vip_lev>0 GROUP BY vip_lev
 ORDER BY vip_lev ASC
SQL;
$stmt = $db_source->prepare($sql_cnt);
$stmt->execute();
$vip_cnt_list = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $vip_cnt_list[$row['vip_lev']]        += $row['cnt'];
}
$lists = array();
$sql = <<<SQL
SELECT vip_lev,map_type,SUM(spend_time) AS spend_time FROM battle_time
WHERE daytime = $bbt AND vip_lev>0 GROUP BY vip_lev,map_type ORDER BY vip_lev ASC
SQL;

$stmt = $db_source->prepare($sql);

$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $lists[$row['vip_lev']][$row['map_type']] += $row['spend_time'];
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
                                <th>VIP等级段</th>
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
                                <td>VIP<?=$lev?></td>
                                <td><?=$vip_cnt_list[$lev]?></td>
                                <td><?=round($list[0]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[1]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[2]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[3]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[4]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[5]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[6]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[7]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[8]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[9]/$vip_cnt_list[$lev],4)?></td>
                                <td><?=round($list[10]/$vip_cnt_list[$lev],4)?></td>
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