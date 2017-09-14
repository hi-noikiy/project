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
    if (is_numeric($_GET['server_id']) && $_GET['server_id']>0) {
        $where .= " AND server_id=" . $_GET['server_id'];
    }
    if (!empty($_GET['logtime'])) {
        $bt = date('Ymd', strtotime($_GET['logtime']));
        $where .= " AND logtime=$bt";
    }
    if (!empty($_GET['compile_time'])) {
        $et = date('Ymd', strtotime($_GET['compile_time']));
        $where .= " AND compile_time=$et";
    }

}
$total_sql = "SELECT COUNT(*) FROM u_server_event WHERE {$where}";
//echo $total_sql;
$stmt = $db_source->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    $sql = <<<SQL
SELECT ID, typeid, logtime, compile_time, server_id  FROM u_server_event WHERE $where LIMIT $offset,$pageSize
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
                        <label>服务器编译时间</label>
                        <input type="text" name="compile_time" class="form-control" size="12" value="<?=$_GET['compile_time']?>" />
                    </div>
                    <div class="form-group">
                        <label>数据维护时间</label>
                        <input type="text" name="logtime" class="form-control" size="12" value="<?=$_GET['logtime']?>" />
                    </div>
                    <div class="form-group">
                        <label>服务器ID</label>
                        <?php echo htmlSelect($serversList, 'server_id', $_GET['server_id']);?>
                    </div>
                    <button type="submit" id="BtnSearch" class="btn btn-primary">SEARCH</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>类型</th>
                            <th>服务器编译时间</th>
                            <th>数据维护时间</th>
                            <th>服务器ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($data)):?>
                            <?php foreach($data as $list):?>
                                <tr>
                                    <td><?=$list['typeid']?></td>
                                    <td><?=$list['logtime']?></td>
                                    <td><?=$list['compile_time']?></td>
                                    <td><?=$list['server_id']?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7"><?php page($total_rows,$currentPage,$pageSize);?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
