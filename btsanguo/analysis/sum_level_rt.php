<?php
include 'header.php';
$dis = new DisplayUser($db_sum, $game_id);
$bt = !isset($_GET['bt']) ? date('Y-m-d', strtotime('-1 day')) : $_GET['bt'];
$noEndTimeFilter = true;
//$et = !isset($_GET['et']) ? date('Y-m-d') : $_GET['et'];
try{
    $data = $dis->ShowUserLevel($bt,  $serverids, $fenbaoids);
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
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div><a href="sum_level_new.php">试用新版</a></div>
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
                            <tfoot>
                            <td>总人数：</td>
                            <td colspan="2"><?=$data['totalPlayer']?></td>
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
<?php include 'footer.php';?>