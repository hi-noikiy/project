<?php
$pageHeader = '活跃玩家平均在线时长';
$initDbSource = true;
include 'header.php';
$timestamp = !empty($_GET['bt']) ? strtotime($_GET['bt']) : $_SERVER['REQUEST_TIME'];
$s_date = !empty($_GET['bt']) ? date('1ymd',$timestamp) : date('1ymd',$timestamp);
$s_e_date = !empty($_GET['et']) ? date('1ymd',strtotime($_GET['et'])) : date('1ymd',$_SERVER['REQUEST_TIME']);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d');
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d');
$where  = '';
$month  = date('m', $timestamp);
$current_month = date('m', $_SERVER['REQUEST_TIME']);
$month_table = '';
$year   = date('Y', $timestamp);
if ($year==2015 && $month!=$current_month) {
    $month_table = "_{$month}";
}
$where .= !empty($_GET['server_id']) && $_GET['server_id']>0 ? " AND serverid=".intval($_GET['server_id']) : '';
$where .= !empty($_GET['vip_lev']) && is_numeric($_GET['vip_lev']) ? " AND viplev={$_GET['vip_lev']}":"";
$where .= !empty($_GET['lev1']) && is_numeric($_GET['lev1']) ? " AND lev>={$_GET['lev1']}":"";
$where .= !empty($_GET['lev2']) && is_numeric($_GET['lev2']) ? " AND lev<={$_GET['lev2']}":"";

$sql = "select count(*) as cnt,active,daytime from `dayonline{$month_table}` where lev>35 AND daytime >=$s_date and daytime<=$s_e_date $where GROUP BY daytime,active";
//echo $sql;
$stmt = $db_source->prepare($sql);
$stmt->execute();
$data = array();
while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
   if ($row['active']==0) {
       $data[$row['daytime']][0] += $row['cnt'];
   }
   elseif ($row['active']>=1 && $row['active']<=49) {
       $data[$row['daytime']][1] += $row['cnt'];
   }
   elseif ($row['active']>=50 && $row['active']<=99) {
       $data[$row['daytime']][2] += $row['cnt'];
   }
   elseif ($row['active']>=100 && $row['active']<=149) {
       $data[$row['daytime']][3] += $row['cnt'];
   }
   elseif ($row['active']>=150 && $row['active']<=199) {
       $data[$row['daytime']][4] += $row['cnt'];
   }
   elseif ($row['active']>=200 && $row['active']<=249) {
       $data[$row['daytime']][5] += $row['cnt'];
   }
   elseif ($row['active']>=250 && $row['active']<=299) {
       $data[$row['daytime']][6] += $row['cnt'];
   }
   else {
       $data[$row['daytime']][7] += $row['cnt'];
   }
}

?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form id="frm" class="form-inline" role="form" method="get">
                        <div class="form-group">
                            <label>时间</label>
                            <input name="bt" type="text" style="width: 120px;" class="form-control" size="10" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            至<input name="et" type="text" style="width: 120px;" class="form-control" size="10" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>VIP 等级</label>
                            <input name="vip_lev"  style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['vip_lev']?>">
                        </div>
                        <div class="form-group">
                            <label>等级</label>
                            <input name="lev1"  style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['lev1']?>">
                            ~<input name="lev2"  style="width: 80px;" type="number" class="form-control" size="3" value="<?=$_GET['lev2']?>">
                        </div>
                        <div class="form-group">
                            <label><?=$lang['server']?></label>
                            <?php echo htmlSelect($serversList, 'server_id', $_GET['server_id']);?>
                        </div>
                        <button type="submit" id="BtnSearch" class="btn btn-primary">SEARCH</button>
                    </form>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>日期</th>
                                <th>0活跃人数</th>
                                <th>1~49活跃人数</th>
                                <th>50~99活跃人数</th>
                                <th>100~149活跃人数</th>
                                <th>150~199活跃人数</th>
                                <th>200~249活跃人数</th>
                                <th>250~299活跃人数</th>
                                <th>≥300活跃人数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data as $daytime=>$list):?>
                                <tr>
                                    <td><?=time_format($daytime-1000000)?></td>
                                    <td><?=$list[0]?></td>
                                    <td><?=$list[1]?></td>
                                    <td><?=$list[2]?></td>
                                    <td><?=$list[3]?></td>
                                    <td><?=$list[4]?></td>
                                    <td><?=$list[5]?></td>
                                    <td><?=$list[6]?></td>
                                    <td><?=$list[7]?></td>
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