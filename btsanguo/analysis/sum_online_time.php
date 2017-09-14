<?php
$pageHeader = '在线时长';
include 'header.php';
//数据库连接
//$db_sum  = db('analysis');
$bt = isset($_GET['bt']) ? $_GET['bt'] :date('Y-m-d', strtotime('-1 days'));
$dis = new Display($db_sum, $gameid, $bt);
$data = $dis->ShowSumPlayOnline( $serverids, $fenbaoids);
$chartArray = array();
$jrmb = '';
$noEndTimeFilter = true;
//print_r($data);
$lvl_list = array(
    '0-4','5-10', '11-20', '21-30',
    '31-40',  '41-50', '51-60',
    '61-70', '71-80','81-90',
    '91-100','101-110','111-120',
    '121-240','241-300','301-360',
    '361-420', '421-480', '>=481',
);
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
                                <th style="text-align: center;" colspan="6">
                                    玩家总数:<?=$data['total']?>
                                </th>
                            </tr>
                            <tr>
                                <th>在线时长（分钟）</th>
                                <th>RMB玩家</th>
                                <th>RMB玩家占比</th>
                                <th>非RMB玩家</th>
                                <th>非RMB玩家占比</th>
                                <th>总玩家</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['total']):?>
                                <?php foreach($lvl_list as $key=>$lv):?>
                                    <tr>
                                        <td><?=$lv?></td>
                                        <?php if(isset($data['list'][$key])):?>
                                            <td><?=$data['list'][$key]['rmb']?></td>
                                            <td><?=round($data['list'][$key]['rmb']/$data['total'], 4)?> %</td>
                                            <td><?=$data['list'][$key]['not_rmb']?></td>
                                            <td><?=round($data['list'][$key]['not_rmb']/$data['total'], 4)?> %</td>
                                            <td><?=$data['list'][$key]['player']?></td>
                                        <?php else:?>
                                            <td>0</td>
                                            <td>0%</td>
                                            <td>0</td>
                                            <td>0%</td>
                                            <td>0</td>
                                        <?php endif;?>
                                        <?php
                                        //$rmb_sum  += $data['list'][$k]['rmb'];
                                        //$rmb_sum  += $data['list'][$k]['rmb'];
                                        $lev[] = $lv;
                                        $jrmb[] = (int)$data['list'][$key]['rmb'];
                                        $not_rmb[] = (int)$data['list'][$key]['not_rmb'];
                                        ?>
                                    </tr>
                                <?php endforeach;?>
<!--                                <tr>-->
<!--                                    <td>合计：</td>-->
<!--                                    <td></td>-->
<!--                                    <td></td>-->
<!--                                    <td></td>-->
<!--                                    <td></td>-->
<!--                                    <td></td>-->
<!--                                </tr>-->
                            <?php else:?>
                                <tr>
                                    <td colspan="6">抱歉，没有数据。</td>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

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
$categories = json_encode($lev);
$jrmb = json_encode($jrmb);
$jnotrmb = json_encode($not_rmb);
$script = <<<HTML
    <script src="public/js/plugins/hightcharts/highcharts.js"></script>
    <script src="public/js/plugins/hightcharts/modules/exporting.js"></script>
    <script>
        $(function () {
        $('#chart').highcharts({
            title: {
                text: '在线时长示意图',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: '时长'
                },
                categories: {$categories}
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
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'RMB玩家',
                data: {$jrmb}
            }, {
                name: '非RMB玩家',
                data: {$jnotrmb}
            }]
        });
    });
    </script>
HTML;

?>

<?php include 'footer.php';?>