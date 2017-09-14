<?php
include 'header.php';
$db_game = db('gamedata');
$bt = !isset($_GET['bt']) ? date('Y-m-d', strtotime('-1 day')) : $_GET['bt'];
$et = !isset($_GET['et']) ? date('Y-m-d') : $_GET['et'];
$dis = new DisplayEudemonMagiccard($db_game, $game_id, $bt, $et);
try{
	$data = $dis->showMagiccar($serverids);
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
                                <th>VIP</th>
								<th>人数</th>
                                <th>橙卡数量</th>
                                <th>平均橙卡数量</th>
                                
                                <?php foreach ($data['cardLevArr'] as $k => $v):?>
                                <th>升级<?=$v?>数量</th>
									<?php if($k == 0):?>
									<th>平均等级</th>
									<?php endif; ?>
								
                                 <?php endforeach;?>
                                 
                                <?php foreach ($data['qualLevArr'] as $k => $v):?>
                                <th>进阶<?=$v?>数量</th>								
                                <!--<th>平均等级</th> -->						
                                <?php endforeach;?>
                                <th>橙色无敌</th>
                                <th>金属性橙色无敌</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data['list'] as $k => $v):?>
                                <tr>
                                    <td><?=$k?></td>
									<td><?=$v['count'] ?></td>
                                    <td><?=intval($v['orange']) ?></td>
                                    <td><?= $v['count'] ? number_format($v['orange']/$v['count'], 2) : '0.00'?></td>
                                 
                                	<?php foreach ($data['cardLevArr'] as $key => $val):?>
                                	<td><?=intval($v[$val]['card'])?></td>
									<?php if($key == 0): ?>
                                	<td><?= $v[$val]['card'] ? number_format($v[$val]['card_lev']/$v[$val]['card'], 2) : '0.00'?></td>
                                	<?php endif; ?>
									<?php endforeach;?>
                                 
                                	<?php foreach ($data['qualLevArr'] as $key => $val):?>
                                	<td><?=intval($v[$val]['qual'])?></td>
									<!--
                                	<td><?//= $v[$val]['qual'] ? number_format($v[$val]['qual_lev']/$v[$val]['qual'], 2) : '0.00'?></td>
                                	-->
                                	<?php endforeach;?>
                                         
                                    <td><?=intval($v['orange_wd'])?></td>
                                	<td><?=intval($v['metal_orange_wd'])?></td>
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