<?php
$pageHeader = '星图养成';
$initDbSource = true;
include 'header.php';
$s_date = !empty($_GET['bt']) ? date('1ymd',strtotime($_GET['bt'])) : date('1ymd',$_SERVER['REQUEST_TIME']);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
$where = "where p.viplev>0 ";

if (count($serverids)) {
    $where .= " AND a.server_id IN(".implode(',', $serverids).")";
}
if ($_GET['min_lvl']>0) {
	$where .= " AND a.attray_lev=" . intval($_GET['min_lvl']);
}

$sql = <<<SQL
SELECT a.*,p.`viplev` from player_attaychart a left join player_info p
on a.server_id=p.serverid AND
p.accountid=a.account_id
$where
SQL;
if ($_GET['debug']) {
	echo '<pre>',$sql,'</pre>';
}
$stmt = $db_source->prepare($sql);
$stmt->execute();
while( $tmp = $stmt->fetch(PDO::FETCH_ASSOC) ){
    $cnt = 0;
    for($i=1;$i<=10;$i++) {
        $cnt += $tmp['typedata_'.$i];
    }
	if ($cnt/10==12) {
		$lists[$tmp['viplev']]['full'] += 1;
	}
    $lists[$tmp['viplev']]['cnt'] += 1;
    $lists[$tmp['viplev']]['sum'] += $cnt;
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
							<label>区服ID</label>
							<input type="number" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="12"/>至
							<input type="number" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="12"/>
						</div>
						<div class="form-group">
							<label>星图层级</label>
							<input type="number" name="min_lvl" placeholder="层级（数字）" value="<?=$_GET['min_lvl']?>" class="form-control" size="2"/>
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
                                <th>vip 等级</th>
                                <th>人数</th>
                                <th>星图等级</th>
                                <th>星图满级人数</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php if (count($lists)>0):?>
							<?php ksort($lists);?>
                            <?php foreach($lists as $viplev=>$list):?>
                                <tr>
                                    <td>VIP <?=$viplev?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['sum'] / 10 / $list['cnt']?></td>
                                    <td><?=isset($list['full'])? $list['full'] : 0;?></td>
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