<?php
/**
 * 装备精练和刻印
 */
$initDbSource = 1;
include 'header.php';
//$db_sum  = db('analysis');
//$dis = new Display($db_sum, $game_id, $bt, $et);

//$cnt_arr = array(188,588,888,1888,2888,5888,10888);
//$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  $_SERVER['REQUEST_TIME']);

$bbt = !empty($_GET['bt']) ? date('1ymd', strtotime($_GET['bt'])) : date('1ymd', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['et']) ? date('1ymd', strtotime($_GET['et'])) : date('1ymd', $_SERVER['REQUEST_TIME']);

$sql = <<<SQL
SELECT Count(*) AS cnt, Sum(wing_lev) as wing_lev, viplev, wing FROM player_info
 WHERE (daytime between $bbt AND $eet) AND wing>0 AND viplev>0 GROUP BY viplev,wing ORDER BY viplev ASC
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
//$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $lists[$row['viplev']][$row['wing']] = $row;
}
//print_r($lists);exit;
$noServerFilter  = true;
$noFenbaoFilter  = true;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
<!--                <div class="panel-heading">-->
                    <?php include 'inc/search_form.inc.php'; ?>
<!--                </div>-->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>vip等级</th>
                                <th>光华翅膀<br/>（1999000010）</th>
                                <th>平均<br/>追加等级</th>
                                <th>千羽翅膀<br/>（1999000011）</th>
                                <th>平均<br/>追加等级</th>
                                <th>圣灵翅膀<br/>（1999000012）</th>
                                <th>平均<br/>追加等级</th>
                                <th>魔陨翅膀<br/>（1999000013）</th>
                                <th>平均<br/>追加等级</th>
                                <th>神降翅膀<br/>（1999000014）</th>
                                <th>平均<br/>追加等级</th>
                                <th>神圣翅膀<br/>（1999000015）</th>
                                <th>平均<br/>追加等级</th>
                                <th>圣光羽翼<br/>（1999000016）</th>
                                <th>平均<br/>追加等级</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $viplev=>$list):?>
                                    <tr>
                                        <td><?=$viplev?></td>
                                        <td>
                                            <p><?=!isset($list[1999000010])?0:$list[1999000010]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000010])?0:round($list[1999000010]['wing_lev']/$list[1999000010]['cnt'], 4)?></td>


                                        <td>
                                            <p><?=!isset($list[1999000011])?0:$list[1999000011]['cnt']?></p>
                                        </td>
                                        <td>
                                            <?=!isset($list[1999000011])?0:round($list[1999000011]['wing_lev']/$list[1999000011]['cnt'], 4)?>
                                        </td>

                                        <td>
                                            <p><?=!isset($list[1999000012])?0:$list[1999000012]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000012])?0:round($list[1999000012]['wing_lev']/$list[1999000012]['cnt'], 4)?></td>

                                        <td>
                                            <p><?=!isset($list[1999000013])?0:$list[1999000013]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000013])?0:round($list[1999000013]['wing_lev']/$list[1999000013]['cnt'], 4)?></td>

                                        <td>
                                            <p><?=!isset($list[1999000014])?0:$list[1999000014]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000014])?0:round($list[1999000014]['wing_lev']/$list[1999000014]['cnt'], 4)?></td>

                                        <td>
                                            <p><?=!isset($list[1999000015])?0:$list[1999000015]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000015])?0:round($list[1999000015]['wing_lev']/$list[1999000015]['cnt'], 4)?></td>
                                        <td>
                                            <p><?=!isset($list[1999000016])?0:$list[1999000016]['cnt']?></p>
                                        </td>
                                        <td><?=!isset($list[1999000016])?0:round($list[1999000016]['wing_lev']/$list[1999000016]['cnt'], 4)?></td>
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