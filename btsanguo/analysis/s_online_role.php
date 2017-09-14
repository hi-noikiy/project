<?php
$pageHeader = '活跃玩家平均在线时长';
$initDbSource = true;
include 'header.php';
$s_date = !empty($_GET['bt']) ? date('1ymd',strtotime($_GET['bt'])) : date('1ymd',$_SERVER['REQUEST_TIME']);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');

$lev_diff = array(0, 10, 20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200 );
//时间等级
$lvl_list = array(
    '0-10', '11-20', '21-30',
    '31-40',  '41-50', '51-60',
    '61-70', '71-80','81-90',
    '91-100','101-110','111-120',
    '121-130','131-140','141-150',
    '151-160','161~170','171~180','181~190','191~200',

);
$where = "where daytime=$s_date AND lev>0  ";
if (count($serverids)) {
    $where .= " AND serverid IN(".implode(',', $serverids).")";
}
$sql = "select count(*) as cnt,SUM(`online`) as online,lev from dayonline $where group by lev ORDER BY lev ASC";
$stmt = $db_source->prepare($sql);
$stmt->execute();
while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['lev']==0) {
        $lvl = 0;
    } else {
        $lvl = halfSearch($lev_diff, $row['lev']);
    }
    $lists[$lvl]['cnt'] += $row['cnt'];
    $lists[$lvl]['online'] += $row['online'] / $row['cnt'];
}

$noEndTimeFilter = true;
$noServerFilter  = false;
$noFenbaoFilter  = true;
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>角色等级</th>
                                <th>人数</th>
                                <th>平均在线时长（分钟）</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($lists as $lev=>$list):?>
                                <tr>
                                    <td><?=$lvl_list[$lev]?></td>
                                    <td><?=$list['cnt']?></td>
                                    <td><?=$list['online'] / 60?></td>
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