<?php
//$pageHeader = '商城消费';
$initDbSource = 1;
include 'header.php';
//$db_sum  = db('analysis');
//$dis = new Display($db_sum, $game_id, $bt, $et);

$cnt_arr = array(188,588,888,1888,2888,5888,10888);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d',  $_SERVER['REQUEST_TIME']);

$bbt = !empty($_GET['bt']) ? date('ymd0000', strtotime($_GET['bt'])) : date('ymd0000', $_SERVER['REQUEST_TIME']);
$eet = !empty($_GET['bt']) ? date('ymd2359', strtotime($_GET['et'])) : date('ymd2359', $_SERVER['REQUEST_TIME']);
//更新时间
$sql_chk = "SELECT id FROM rmb WHERE type=153 AND (daytime BETWEEN $bbt AND $eet) AND sdate=0";
$stmt =  $db_source->prepare($sql_chk);
$stmt->execute();
$id_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
if ($id_list) {
    $sql_update = "UPDATE rmb set sdate=SUBSTRING(daytime,1,6) WHERE id IN(".implode(',', $id_list).")";
    $db_source->exec($sql_update);
}

$sql = "SELECT accountid,max(emoney) as emoney,serverid,sdate FROM rmb WHERE type=153 AND emoney IN(".implode(',', $cnt_arr).")";
$where = " AND daytime BETWEEN $bbt AND $eet";
$where .= !empty($_GET['serverids']) ? " AND serverid IN(".implode(',', $_GET['serverids']).")" : '';
$sql .= $where . " GROUP BY accountid,serverid,sdate ORDER BY sdate ASC";
//. " GROUP BY emoney,serverid";
$stmt = $db_source->prepare($sql);
//echo $sql;
$stmt->execute();
$lists = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $day = date('y-m-d',strtotime('20'.$row['daytime']));
//    echo $day . '---'. $row['serverid'].'---'.$row['emoney'] . '<br/>';
//    echo $day . '<br/>';
    $lists[$row['sdate']][$row['serverid']][$row['emoney']] += 1;
}
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
                                <th>日期</th>
                                <th>区服</th>
                                <th>188</th>
                                <th>588</th>
                                <th>888</th>
                                <th>1888</th>
                                <th>2888</th>
                                <th>5888</th>
                                <th>10888</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $day=>$list):?>
                                    <?php foreach($list as $server_id=>$data):?>
                                        <tr>
                                            <td><?=$day?></td>
                                            <td><?=$server_id?></td>
                                            <td>
                                            <?=isset($data['188']) ? $data['188'] : 0?>
                                            </td>
                                            <td>
                                            <?=isset($data['588']) ? $data['588'] : 0?>
                                            </td>
                                            <td>
                                            <?=isset($data['888']) ? $data['888'] : 0?>
                                            </td> 
                                            <td>
                                            <?=isset($data['1888']) ? $data['1888'] : 0?>
                                            </td>
                                            <td>
                                            <?=isset($data['2888']) ? $data['2888']: 0?>
                                            </td>
                                            <td><?=isset($data['5888']) ? $data['5888'] : 0?></td>
                                            <td><?=isset($data['10888']) ? $data['10888'] : 0?></td>
                                        </tr>
                                    <?php endforeach;?>
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