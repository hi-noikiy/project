<?php
include 'header.php';
$db_game = db('gamedata');
$bt = !isset($_GET['bt']) ? date('Y-m-d', strtotime('-1 day')) : $_GET['bt'];
$et = !isset($_GET['et']) ? date('Y-m-d') : $_GET['et'];
$dis = new DisplayCopyProgress($db_game, $game_id, $bt, $et);
try{
	$data = $dis->showCopyProgressLevel($serverids);
} catch(Exception $e) {
    echo $e->getMessage();
}
$warnMsg = '';
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form_copy.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div><a href="sum_level_new.php">试用新版</a></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                            <thead>
                            <tr>
                                <th>等级段</th>
								<th>人数</th>
                                <th>平均战力</th>
                                
                                <th>平均普通副本进度</th>
                                <th>平均精英副本进度</th>
                                <th>平均魔王副本进度</th>
                                <th>平均普通过关斩战进度</th>
                                <th>平均精英过关斩战进度</th>
                                <th>平均一骑当千最高关卡</th>
                                <th>一骑当千人数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data['list'] as $k => $v):?>
                                <tr>
                                    <td><?=$k ?></td>
									<td><?=count($v['account'])?></td>
                                    <td><?=$v['combat_count'] ? number_format($v['combat']/$v['combat_count'], 2) : '0.00'?></td>
                                    <td><?=$v['copy_normal_count'] ? number_format($v['copy_normal']/$v['copy_normal_count'], 2) : '0.00'?></td>
                                    <td><?=$v['copy_smart_count'] ? number_format($v['copy_smart']/$v['copy_smart_count'], 2) : '0.00'?></td>
                                    <td><?=$v['copy_evil_count'] ? number_format($v['copy_evil']/$v['copy_evil_count'], 2) : '0.00'?></td>
                                    <td><?=$v['throuh_normal_count'] ? number_format($v['throuh_normal']/$v['throuh_normal_count'], 2) : '0.00'?></td>
                                    <td><?=$v['through_smart_count'] ? number_format($v['through_smart']/$v['through_smart_count'], 2) : '0.00'?></td>
                                    <td><?=$v['maxikk_count'] ? number_format($v['maxikk']/$v['maxikk_count'], 2) : '0.00'?></td>
									<td><?=count($v['account2'])?></td>
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