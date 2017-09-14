<?php
$initDbSource = true;
include 'header.php';

$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-2 days'));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d');

$s_date = !empty($bt) ? date('1ymd',strtotime($bt)) : date('1ymd',$_SERVER['REQUEST_TIME']);
$e_date = !empty($et) ? date('1ymd',strtotime($et)) : date('1ymd',$_SERVER['REQUEST_TIME']);

$where = "where daytime BETWEEN $s_date AND $e_date AND level>24 and viplev>0";
if (count($serverids)) {
    $where .= " AND serverid IN(".implode(',', $serverids).")";
}

$sql = <<<SQL
SELECT `viplev`,sum(horse) as horse,count(*) AS cnt from player_info $where
GROUP BY viplev
SQL;
if ($_GET['debug']) {
    echo '<pre>',$sql,'</pre>';
}
$stmt = $db_source->prepare($sql);
$stmt->execute();
while( $tmp = $stmt->fetch(PDO::FETCH_ASSOC) ){
    $lists[$tmp['viplev']]['horse'] = $tmp['horse'];
    $lists[$tmp['viplev']]['cnt']   = $tmp['cnt'];
}
//print_r($lists);


?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline" role="form" action="<?=$action;?>">
                        <div class="form-group">
                            <label>时间</label>
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            --
                            <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>区服ID</label>
                            <input type="number" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="12"/>至
                            <input type="number" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="12"/>
                        </div>
                         <button type="submit" class="btn btn-primary">查 询</button>
                    </form>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>vip 等级(>=25)</th>
                                <th>人数</th>
                                <th>坐骑平均等级</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)>0):?>
                            <?php ksort($lists);?>
                            <?php foreach($lists as $viplev=>$list):?>
                                <tr>
                                    <td>VIP <?=$viplev?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['horse'] / $list['cnt']?></td>
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