<?php
/**
 * 装备精练和刻印
 */
$initDbSource = 1;
include 'header.php';
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  $_SERVER['REQUEST_TIME']);

$bbt = !empty($_GET['bt']) ? date('1ymd', strtotime($_GET['bt'])) : date('1ymd', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['et']) ? date('1ymd', strtotime($_GET['et'])) : date('1ymd', $_SERVER['REQUEST_TIME']);

$sql = <<<SQL
SELECT COUNT(*) AS cnt,viplev,SUM(mark1) AS mark1, SUM(mark2) AS mark2, SUM(mark3) AS mark3,
SUM(mark4) AS mark4, SUM(mark5) AS mark5, SUM(mark6) AS mark6 FROM player_info
WHERE (daytime between $bbt AND $eet)
GROUP BY viplev ORDER BY viplev ASC
SQL;

//$sql = "SELECT accountid,max(emoney) as emoney,serverid,sdate FROM rmb WHERE type=153 AND emoney IN(".implode(',', $cnt_arr).")";
//$where = " AND daytime BETWEEN $bbt AND $eet";
//$where .= !empty($_GET['serverids']) ? " AND serverid IN(".implode(',', $_GET['serverids']).")" : '';
//$sql .= $where . " GROUP BY accountid,serverid,sdate ORDER BY sdate ASC";
//. " GROUP BY emoney,serverid";
$stmt = $db_source->prepare($sql);
//echo $sql;
$stmt->execute();
$lists = array();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
//while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    $day = date('y-m-d',strtotime('20'.$row['daytime']));
//    echo $day . '---'. $row['serverid'].'---'.$row['emoney'] . '<br/>';
//    echo $day . '<br/>';
//    $lists[$row['viplev']] = $row;
//}
//print_r($lists);
$noFenbaoFilter = true;
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
                                <th>vip等级</th>
                                <th>人数</th>
                                <th>武器刻印</th>
                                <th>头盔刻印</th>
                                <th>护甲刻印</th>
                                <th>腰带刻印</th>
                                <th>战靴刻印</th>
                                <th>披风刻印</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <tr>
                                    <td><?=$list['viplev']?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=round($list['mark1']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['mark2']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['mark3']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['mark4']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['mark5']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['mark6']/ $list['cnt'], 4)?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="7">抱歉，没有数据。</td></tr>
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
<?php include 'footer.php'; ?>