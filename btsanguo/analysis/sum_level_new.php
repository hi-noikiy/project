<?php
$initDbSource = 1;
include 'header.php';
$bt = !isset($_GET['bt']) ? date('Y-m-d', strtotime('-1 day')) : $_GET['bt'];
$dis = new PlayerLevel($db_source, $db_sum, $bt);

$noEndTimeFilter = true;
$noFenbaoFilter = true;
//$et = !isset($_GET['et']) ? date('Y-m-d') : $_GET['et'];
try{
    $data = $dis->getData($serverids);
//    var_dump($data);
//    print_r($data);
    $output = array();
    foreach ($data as $_d) {
        $output[$_d['serverid']][$_d['lev']] += $_d['nop'];
    }
//    print_r($output);
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>

                            </thead>
                            <tbody>
                            <?php foreach($output as $sid=>$lists):?>
                                <?php $total = array_sum($lists);?>
                                <?php ksort($lists);?>
                                <tr>
                                    <th colspan="3">区服ID:<?=$sid;?></th>
                                </tr>
                                <tr>
                                    <th><?=$lang['lev_t']?></th>
                                    <th><?=$lang['lev_p']?></th>
                                    <th><?=$lang['lev_pr']?></th>
                                </tr>
                                <?php foreach($lists as $lev=>$nop):?>
                                <tr>
                                    <td><?php echo $lev;?></td>
                                    <td><?php echo $nop;?></td>
                                    <td><?php echo round($nop/$total, 4)*100;?>%</td>
                                </tr>
                                <?php endforeach;?>
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