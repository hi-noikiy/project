<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户充值
 */
include 'header.php';
$dis = new Show($db_sum, $game_id, $bt, $et, $serverids, $fenbaoids);
$lists = $dis->PlayerPay();
if($_SESSION['uid']==17) {
    $noFenbaoFilter = true;
}
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
                            <tr>
                                <th><?=$lang['date'];?></th>
                                <th>角色数</th>
                                <th>总金额</th>
                                <th>新增角色数</th>
                                <th>新增付费角色付费金额</th>
                                <th><?=$lang['server'];?></th>
                                <th><?=$lang['channel'];?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $list):?>
                                <tr>
                                    <td><?php echo date('Y年m月d日', strtotime( $list['sday']));?></td>
                                    <td><?php echo $list['paynopall']?></td>
                                    <td><?php echo $list['income']?></td>
                                    <td><?php echo $list['paynopnew']?></td>
                                    <td><?php echo $list['paynopnew_money']?></td>
                                    <?php if(count($serverids) || count($fenbaoids)):?>
                                    <td><?php echo $serversList[$list['serverid']]?></td>
                                    <td><?php echo $fenbaos[$list['fenbaoid']]?></td>
                                    <?php else:?>
                                    <td>----</td>
                                    <td>----</td>
                                    <?php endif?>
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