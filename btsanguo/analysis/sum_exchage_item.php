<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 兑换行为
* ==============================================
* @date: 2016-4-1
* @author: luoxue
* @version:
*/
include 'header.php';
$db_game = db('gamedata');

$bt = !isset($_GET['bt']) ? date('Y-m-d', strtotime('-1 day')) : $_GET['bt'];
$et = !isset($_GET['et']) ? date('Y-m-d') : $_GET['et'];
$dis = new DisplayExchangeItem($db_game, $game_id, $bt, $et);
try{
	$data = $dis->show($serverids, $offset, $pageSize);
} catch(Exception $e) {
    echo $e->getMessage();
}
$warnMsg = '';
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form_copy.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>时间</th>
                                <th>账号ID</th>
                                <th>服务器ID</th>     
                                <th>VIP等级</th>
                                <th>兑换道具ID</th>
                                <th>消耗物品ID</th>
                                <th>数量</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($data['list'])):?>
                                <?php foreach($data['list'] as $v):?>
                                    <tr>
                                        <td>
											<?=date('Y-m-d H:i:s', $v['exchange_time']) ?>
										</td>
                                        <td><?=$v['account_id']?></td>
                                        <td><?=$v['server_id']?></td>
                                        <td><?=$v['viplev']?></td> 
                                        <td><?=$v['awarditemtype']?></td>              
                                        <td><?=$v['costtype']?></td>
                                        <td><?=$v['costnum']?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr>
                                    <td colspan="21">没有数据</td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="21"><?php page($data['total'],$currentPage,$pageSize);?></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php
include 'footer.php';
?>