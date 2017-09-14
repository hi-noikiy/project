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
SELECT COUNT(*) AS cnt,viplev,SUM(star1) AS star1, SUM(star2) AS star2, SUM(star3) AS star3,
SUM(star4) AS star4, SUM(star5) AS star5, SUM(star6) AS star6 FROM player_info
WHERE (daytime between $bbt AND $eet) AND level>=92
GROUP BY viplev ORDER BY viplev ASC
SQL;
$stmt = $db_source->prepare($sql);
//echo $sql;
$stmt->execute();
$lists = array();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                <th>人数(≥92级)</th>
                                <th>武器精练</th>
                                <th>头盔精练</th>
                                <th>护甲精练</th>
                                <th>腰带精练</th>
                                <th>战靴精练</th>
                                <th>披风精练</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <tr>
                                    <td><?=$list['viplev']?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=round($list['star1']/$list['cnt'], 4)?></td>
                                    <td><?=round($list['star2']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['star3']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['star4']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['star5']/ $list['cnt'], 4)?></td>
                                    <td><?=round($list['star6']/ $list['cnt'], 4)?></td>
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