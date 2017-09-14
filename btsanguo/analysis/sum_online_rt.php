<?php
$pageHeader = '实时在线统计';
include 'header.php';
$db = db('gamedata');
$dis = new DisplayUser($db);

$time_format = ' hh:mm:ss';
$bt = !isset($_GET['bt']) ?
    date('Y-m-d H:i:00', strtotime("-10 mins")) : $_GET['bt'];
//$et = !isset($_GET['et']) ? date('Y-m-d H:i:00') : $_GET['et'];
$noEndTimeFilter = true;
try{
    $lists = $dis->ShowOnlineRealTime($db, $bt, $serverids, $game_id);
} catch(Exception $e) {
    echo $e->getMessage();
}
$noFenbaoFilter = true;
$servers_js = $onlineArr = array();
$warnMsg = '实时在线数据无法统计[平均在线],查看每日平均在线请<a href="http://localhost/sanguo/analysis/sum_online.php">点击在线统计</a>。';
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
                        <table class="table onlineFlag" id="dataTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th><?=$lang['server']?></th>
                                <th><?=$lang['time']?></th>
                                <th><?=$lang['online_rt']?></th>
                                <th><?=$lang['online_rt_total']?></th>
                                <th><?=$lang['s_online_max']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            ?>
                            <?php foreach($lists as $list):?>
                                <?php
                                    if ($list['serverid']>900 && $list['serverid']<9000) {
                                        $class = "android";
                                        $servers_js[]       = $list['serverid']-900;
                                        $onlineArr[]        = (int)$list['online'];
                                    } else if ( $list['serverid']>9000) {
                                        $servers_js[]       = $list['serverid']-9000;
                                        $onlineArr[]        = (int)$list['online'];
                                    } else if($list['serverid']>600 && $list['serverid']<800){
                                        $class = 'ios';
                                    } else {
                                        $class = 'other';
                                    }

                                ?>
                                <tr class="<?=$class?>">
                                    <td><?=$list['serverid']?></td>
                                    <td><?=$serversList[$list['serverid']];?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime('20'.$list['daytime']));?></td>
                                    <td><?php echo $list['online'];?></td>
                                    <td><?php echo $list['WorldOnline'];?></td>
                                    <td><?php echo $list['WorldMaxOnline'];?></td>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    示意图
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div id="chart"></div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
<?php
$serversJson        = json_encode($servers_js);
$onlineJson         = json_encode($onlineArr);
$script = <<<HTML
    <script src="public/js/plugins/hightcharts/highcharts.js"></script>
    <script src="public/js/plugins/hightcharts/modules/exporting.js"></script>
    <script>
        $(function () {
        $('#chart').highcharts({
            title: {
                text: '在线统计',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: '区服'
                },
                categories: {$serversJson}
            },
            yAxis: {
                title: {
                    text: '人数'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '人'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '实时在线',
                data: {$onlineJson}
            }]
        });
    });
    </script>
HTML;

?>
<?php include 'footer.php';?>