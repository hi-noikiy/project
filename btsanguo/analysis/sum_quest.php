<?php
$initDbSource = true;
include 'header.php';
if (file_exists(A_ROOT.'inc/quests.inc.php')) {
    include A_ROOT.'inc/quests.inc.php';
}
else {
    $taskList = Quest::QuestList($db_sum, 110);
}
$bt = isset($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-1 days'));
$quest = new Quest($db_source);
$data = $quest->QuestComplete($bt, $serverids, $fenbaoids);
$noEndTimeFilter = true;
$prefixTime = '注册';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <?php include 'inc/search_form.inc.php'; ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table <?=$data['totalPlayer']>0? 'id="dataTable"' :''?> class="table table-striped table-bordered table-hover questFlag">
                        <thead>
                            <tr>
                                <th><?=$lang['task_id']?></th>
                                <th><?=$lang['task_name']?></th>
                                <th><?=$lang['task_min_level']?></th>
                                <th><?=$lang['task_max_level']?></th>
                                <th><?=$lang['task_people']?></th>
                                <th><?=$lang['task_people_scale']?></th>
                                <th><?=$lang['user_reg_date']?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($data['totalPlayer']>0):?>
                        <?php foreach($data['quests'] as $list):?>
                            <tr>
                                <td><?=$list['systemid']?></td>
                                <td><?=$taskList[$list['systemid']]['name']?></td>
                                <td><?=$taskList[$list['systemid']]['min_level']?></td>
                                <td><?=$taskList[$list['systemid']]['max_level']?></td>
                                <td><?=$list['cnt']?></td>
                                <td><?=round($list['cnt']/$data['totalPlayer'], 4)*100?>%</td>
                                <td><?=date('Y-m-d H:i:s', strtotime('20'.$list['time']));?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr><td colspan="10"><?=$lang['no_data']?></td></tr>
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