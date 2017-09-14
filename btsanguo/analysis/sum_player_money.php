<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户金币、元宝
 */
include 'header.php';
$total_rows = 0;
$lists = array();
//数据库连接
//$db_sum  = db('analysis');
$dis = new DisplayUser($db_sum);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-1 days'));

$lists = $dis->ShowPlayerMoney($bt, $serverids, $fenbaoids);
//$total_rows = count($data['list']);
//
//
//$lists      = $data['list'];
//$nl         = $data['nl'];
$noEndTimeFilter = true;
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
                        <table id="dataTable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><?=$lang['lev_t']?></th>
                                <th>人数</th>
                                <th>元宝数</th>
                                <th>金币数</th>
                                <th><?=$lang['date'];?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $list):?>
                                <tr>
                                    <td><?php echo $list['lev']?></td>
                                    <td><?php echo $list['nop']?></td>
                                    <td><?php echo $list['emoney']?></td>
                                    <td><?php echo $list['money']?></td>
                                    <td><?php echo date('Y年m月d日', strtotime( $list['sday']));?></td>
                                </tr>
                            <?php endforeach; ?>
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