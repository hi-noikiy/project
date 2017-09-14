<?php
$initDbSource = true;
include 'header.php';
$dis = new Display($db_sum, $game_id, $bt, $et);
$types = $dis->GetEmoneyTypes();
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-2 days'));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d');

$s_date = !empty($bt) ? date('ymd0000',strtotime($bt)) : date('ymd0000',$_SERVER['REQUEST_TIME']);
$e_date = !empty($et) ? date('ymd2359',strtotime($et)) : date('ymd2359',$_SERVER['REQUEST_TIME']);

$where = "where daytime BETWEEN $s_date AND $e_date";
if (isset($_GET['accountid']) && $_GET['accountid']>0) {
    $where .= " AND `accountid`={$_GET['accountid']}";
}
if (isset($_GET['userid']) && $_GET['userid']>0) {
    $where .= " AND `userid`={$_GET['userid']}";
}
if (count($serverids)) {
    $where .= " AND serverid IN(".implode(',', $serverids).")";
}
$sql_total = "SELECT COUNT(*) AS cnt FROM rmb $where";
$stmt = $db_source->prepare($sql_total);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
$sql = "SELECT * from rmb $where ORDER BY id DESC LIMIT $offset, $pageSize";
if ($_GET['debug']) {
    echo '<prev>';
    echo $sql;
    echo '</prev>';
}
$stmt = $db_source->prepare($sql);
$stmt->execute();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline" role="form" action="<?=$action;?>">
                        <input name="_col" type="hidden" value="<?=$col_id?>"/>
                        <div class="form-group">
                            <label>时间</label>
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            --
                            <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>区服ID</label>
                            <input type="number" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="12"/>至
                            <input type="number" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="12"/>
                        </div>
                        <div class="form-group">
                            <label>账号ID</label>
                            <input type="number" name="accountid" placeholder="" value="<?=$_GET['accountid']?>" class="form-control"/>
                        </div>
                         <button type="submit" class="btn btn-primary">查 询</button>
                    </form>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover onlineFlag">
                            <thead>
                            <tr>
                                <th>时间</th>
                                <th>玩家名</th>
                                <th>玩家ID</th>
                                <th>玩家账号ID</th>
                                <th>区服</th>
                                <th>消费类型</th>
                                <th>消费元宝数量</th>
                                <th>消费后剩余元宝数量</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($lists)>0):?>
                            <?php foreach($lists as $list):?>
                                <tr>
                                    <td><?=date('Y-m-d H:i:s', strtotime('20'.$list['daytime']))?></td>
                                    <td>--</td>
                                    <td><?=$list['userid']?></td>
                                    <td><?=$list['accountid']?></td>
                                    <td><?=$list['serverid']?></td>
                                    <td>[<?=$list['type']?>]<?=$types[$list['type']]?></td>
                                    <td><?=$list['emoney']?></td>
                                    <td><?=$list['curemoney']?></td>
                                </tr>
                            <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="8"><?php page($total_rows,$currentPage,$pageSize);?></td>
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