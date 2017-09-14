<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-08
 * logtime: 下午3:49
 * 特别活动 (World Boss(Special Activity))
 */
include 'header.php';
$where = '1=1';
$db_game = db('gamedata');
if (!empty($_GET)) {
    if (is_numeric($_GET['server_id1']) && $_GET['server_id1']>0) {
        $where .= " AND serverid>=" . $_GET['server_id1'];
    }
    if (is_numeric($_GET['server_id2']) && $_GET['server_id2']>0) {
        $where .= " AND serverid<=" . $_GET['server_id2'];
    }

    if (!empty($_GET['awardsource'])) {
        $where .= " AND awardsource='".trim($_GET['awardsource'])."'";
    }
}

$total_sql = <<<SQL
SELECT COUNT(*) FROM u_world_drop WHERE $where
SQL;
//echo $total_sql;
$stmt = $db_game->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    $sql = <<<SQL
SELECT * FROM u_world_drop
WHERE $where
ORDER BY logtime ASC
LIMIT $offset,$pageSize
SQL;

    $stmt = $db_game->prepare($sql);
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
                        <label>区服</label>
                        <input type="number"
                               size="4"
                               min="900"
                               class="form-control w80"
                               name="server_id1" id="server_id1"
                               value="<?=$_GET['server_id1']?>"/>
                        ~
                        <input type="number"
                               min="900"
                               class="form-control w80"
                               name="server_id2"
                               id="server_id2"
                               value="<?=$_GET['server_id2']?>"/>
                    </div>
                    <div class="form-group">
                        <label>产出礼包</label>
                        <input type="text"
                               name="awardsource"
                               id="awardsource"
                               value="<?=$_GET['awardsource']?>"/>
                    </div>
                    <button type="submit" id="BtnSearch" class="btn btn-primary">查一下</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>账号ID</th>
                            <th>服务器ID</th>
                            <th>产出礼包</th>
                            <th>奖励类型</th>
                            <th>数量</th>
                            <th>掉落时间</th>
                        </thead>
                        <tbody>
                        <?php if(isset($data)):?>
                            <?php foreach($data as $row):?>
                            <tr>
                                <td class="account"><?=$row['accountid']?></td>
                                <td class="server"><?=$row['serverid']?></td>
                                <td><?=$row['awardsource']?></td>
                                <td><?=$row['awardtype']?></td>
                                <td><?=$row['awardnum']?></td>
                                <td><?=date('Y-m-d H:i:s', $row['logtime'])?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10"><?php page($total_rows,$currentPage,$pageSize);?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
