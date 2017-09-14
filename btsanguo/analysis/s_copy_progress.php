<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-08
 * Time: 下午3:49
 * 当前元宝总数
 */
$initDbSource = true;
include 'header.php';
$where = "1=1";
if (!empty($_GET)) {
    if (!empty($_GET['accountid'])) {
        $accountid = $_GET['accountid'];
        $where .= " AND accountid={$accountid}";
    }
    if (is_numeric($_GET['server_id']) && $_GET['server_id']>0) {
        $where .= " AND serverid=" . $_GET['server_id'];
    }
    if (!empty($_GET['throuh_normal'])) {
        $where .= " AND throuh_normal=" . ($_GET['throuh_normal']+0);
    }
    if (!empty($_GET['through_smart'])) {
        $where .= " AND through_smart=" . ($_GET['through_smart']+0);
    }

}
$total_sql = "SELECT COUNT(*) FROM u_copy_progress WHERE {$where}";
//echo $total_sql;
$stmt = $db_source->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    $sql = <<<SQL
SELECT accountid, serverid, login_time, level, viplevel, combat, copy_normal,
 copy_smart, copy_evil, throuh_normal, through_smart
  FROM u_copy_progress WHERE $where LIMIT $offset,$pageSize
SQL;
    $stmt = $db_source->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form id="frm" class="form-inline" role="form" method="get">
                    <div class="form-group">
                        <label>账号</label>
                        <input type="text" name="accountid" class="form-control" size="12" value="<?=$_GET['accountid']?>" />
                    </div>
                    <div class="form-group">
                        <label><?=$lang['server']?></label>
                        <?php echo htmlSelect($serversList, 'server_id', $_GET['server_id']);?>
                    </div>
                    <div class="form-group">
                        <label>普通过关斩将进度</label>
                        <input type="text" name="throuh_normal" class="form-control" size="12" value="<?=$_GET['throuh_normal']?>" />
                    </div>
                    <div class="form-group">
                        <label>精英过关斩将进度</label>
                        <input type="text" name="through_smart" class="form-control" size="12" value="<?=$_GET['through_smart']?>" />
                    </div>
                    <button type="submit" id="BtnSearch" class="btn btn-primary">SEARCH</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>账号ID</th>
                            <th>服务器ID</th>
                            <th>登陆时间</th>
                            <th>等级</th>
                            <th>VIP等级</th>
                            <th>战力</th>
                            <th>普通副本进度</th>
                            <th>精英副本进度</th>
                            <th>魔王副本进度</th>
                            <th>普通过关斩将</th>
                            <th>精英过关斩将</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($data)):?>
                            <?php foreach($data as $list):?>
                                <tr>
                                    <td><?=$list['accountid']?></td>
                                    <td><?=$list['serverid']?></td>
                                    <td><?=time_format($list['login_time'])?></td>
                                    <td><?=$list['level']?></td>
                                    <td><?=$list['viplevel']?></td>
                                    <td><?=$list['combat']?></td>
                                    <td><?=$list['copy_normal']?></td>
                                    <td><?=$list['copy_smart']?></td>
                                    <td><?=$list['copy_evil']?></td>
                                    <td><?=$list['throuh_normal']?></td>
                                    <td><?=$list['through_smart']?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="12"><?php page($total_rows,$currentPage,$pageSize);?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
