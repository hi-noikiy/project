<?php
include 'header.php';
$db = db('gamedata_ios');
//$db = db('gamedata');
$dis = new DisplayUser($db, $game_id);
$bt = !isset($_GET['bt']) ? date('Y-m-d H:i:00', strtotime('-15 mins')) : $_GET['bt'];
$et = !isset($_GET['et']) ? date('Y-m-d H:i:00') : $_GET['et'];
try{
    $data = $dis->ShowUserLevel($bt, $et, $serverids, $fenbaoids);
} catch(Exception $e) {
    echo $e->getMessage();
}
$time_format = ' hh:mm:ss';
$warnMsg = '由于玩家数量巨大，请尽量选择具体区服、渠道进行查询以减少您的等待时间。';
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                            <thead>
                            <tr>
                                <th><?=$lang['lev_t']?></th>
                                <th><?=$lang['lev_p']?></th>
                                <th><?=$lang['lev_pr']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data['list'] as $list):?>
                                <tr>
                                    <td><?php echo $list['lev'];?></td>
                                    <td><?php echo $list['cnt'];?></td>
                                    <td><?php echo round($list['cnt']/$data['totalPlayer'], 4)*100;?>%</td>
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