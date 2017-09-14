<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户留存——渠道统计
 */
include 'header.php';
//$bt = date('Y-m-d', strtotime())
$lists = array();
$dis = new DisplayUser($db_sum, $game_id, $bt);
$lists = $dis->ShowUserRemainByChannel();
$noEndTimeFilter = true;
$noServerFilter  = true;
$noFenbaoFilter  = true;

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
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>渠道</th>
                                <th>新注册数</th>
                                <th>次日留存</th>
                                <th>3日留存</th>
                                <th>5日留存</th>
                                <th>7日留存</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <?php if($list['fenbaoid']==60073) continue;?>
                                    <tr>
                                        <td>[<?=$list['fenbaoid']?>]<?=$fenbaos[$list['fenbaoid']]?></td>
                                        <td><?=$list['usercount']?></td>
                                        <td>
                                            <?=($list['usercount']>0 &&$list['day1']) ? round($list['day1']/$list['usercount'], 2)*100 : 0?>%(<?=$list['day1']?>/<?=$list['usercount']?>)
                                        </td>
                                        <td>
                                            <?=($list['usercount']>0 &&$list['day3']) ? round($list['day3']/$list['usercount'], 2)*100 : 0?>%(<?=$list['day3']?>/<?=$list['usercount']?>)
                                        </td>
                                        <td>
                                            <?=($list['usercount']>0 &&$list['day5']) ? round($list['day5']/$list['usercount'], 2)*100 : 0?>%(<?=$list['day5']?>/<?=$list['usercount']?>)
                                        </td>
                                        <td>
                                            <?=($list['usercount']>0 &&$list['day7']) ? round($list['day7']/$list['usercount'], 2)*100 : 0?>%(<?=$list['day7']?>/<?=$list['usercount']?>)
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:?>
                                <tr>
                                    <td colspan="<?php echo $col;?>">没有相关数据。。。</td>
                                </tr>
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