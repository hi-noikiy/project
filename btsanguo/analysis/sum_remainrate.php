<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户留存
 */
include 'header.php';
$lists = array();
$dis = new DisplayUser($db_sum, $game_id, $bt, $et);

$data = $dis->ShowUserRemain($serverids, $fenbaoids, $offset, $pageSize);
$total_rows = $data['total'];
$lists = $data['list'];
$warnMsg = "<a href='sum_remainrate_by_channel.php'>渠道留存统计</a>";
$col = 9;
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
                                <th>日期</th>
                                <?php if(count($serverids)>0 || count($fenbaoids)>0):?>
                                    <?php $col=11?>
                                    <th>区服</th>
                                    <th>渠道</th>
                                <?php endif;?>
                                <th>注册</th>
                                <th>新增登录</th>
                                <th>活跃数(DAU)</th>
                                <th>次日留存</th>
                                <th>3日留存</th>
                                <th>7日留存</th>
                                <th>15日留存</th>
                                <th>月留存</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)):?>
                                <?php foreach($lists as $list):?>
                                    <tr>
                                        <td><?php echo date('Y年m月d日', strtotime( $list['sday']));?></td>
                                        <?php if(count($serverids)>0 || count($fenbaoids)>0):?>
                                            <?php $col = 11;?>
                                            <td><?php echo $serverList[$list['serverid']];?> -<?php echo $list['serverid'];?></td>
                                            <td><?php echo $result_fenbao[$list['fenbaoid']];?> - <?php echo $list['fenbaoid'];?></td>
                                        <?php endif;?>
                                        <td><?php echo $list['usercount']?></td>
                                        <td><?php echo $list['newlogin']?></td>
                                        <td><?php echo $list['dau']?></td>
                                        <td><?php echo $list['day1']?> | <?php echo @round($list['day1']/$list['newlogin'], 4)*100;?>%</td>
                                        <td><?php echo $list['day3']?> | <?php echo @round($list['day3']/$list['newlogin'], 4)*100;?>%</td>
                                        <td><?php echo $list['day7']?> | <?php echo @round($list['day7']/$list['newlogin'], 4)*100;?>%</td>
                                        <td><?php echo $list['day15']?>| <?php echo @round($list['day15']/$list['newlogin'], 4)*100;?>%</td>
                                        <td><?php echo $list['day30']?>| <?php echo @round($list['day30']/$list['newlogin'], 4)*100;?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:?>
                                <tr>
                                    <td colspan="<?php echo $col;?>">没有相关数据。。。</td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="<?php echo $col;?>"><?php page($total_rows,$currentPage,$pageSize);?></td>
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
<?php include 'footer.php';?>