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
    if (!empty($_GET['bt'])) {
        $bt = date('ymd0000', strtotime($_GET['bt']));
        $where .= " AND logtime>$bt";
    }
    if (!empty($_GET['et'])) {
        $et = date('ymd2359', strtotime($_GET['et']));
        $where .= " AND logtime<$et";
    }
    if (is_numeric($_GET['userid']) && $_GET['userid']>0) {
        $where .= " AND userid=" . $_GET['userid'];
    }
    if (is_numeric($_GET['server_id']) && $_GET['server_id']>0) {
        $where .= " AND serverid=" . $_GET['server_id'];
    }
}
else {
    $bt = date('ymd0000');
    $et = date('ymd2359');
    $where .= " AND logtime>$bt AND logtime<$et";
}
$total_sql = "SELECT COUNT(*) FROM u_player_through_time_bg WHERE {$where}";
//echo $total_sql;
$stmt = $db_game->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    $sql = "SELECT * FROM u_player_through_time_bg WHERE "
        . $where
        . " ORDER BY rank ASC"
        . " LIMIT $offset,$pageSize";
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
                        <label>User ID</label>
                        <input name="userid" type="text" class="form-control" value="<?=$_GET['userid']?>" >
                    </div>
                    <div class="form-group">
                        <label>区服</label>
                        <input type="number" name="server_id1" id="server_id1"/>
                        ~
                        <input type="number" name="server_id2" id="server_id2"/>
                    </div>
                    <div class="form-group">
                        <label>关卡</label>
                        <input type="number" name="through_type1" id="through_type1"/>
                        ~
                        <input type="number" name="through_type2" id="through_type2"/>
                    </div>
                    <button type="submit" id="BtnSearch" class="btn btn-primary">SEARCH</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>Date</th>
                            <th>User Id</th>
                            <th>Server Id</th>
                            <th>Account Id</th>
                            <th>Boss Name</th>
                            <th>Damage</th>
                            <th>Money</th>
                            <th>Rank</th>
                            <th>Lucky</th>
                            <th>Reputation</th>
                        </thead>
                        <tbody>
                        <?php if(isset($data)):?>
                            <?php foreach($data as $row):?>
                            <tr>
                                <td><?=$row['logtime']?></td>
                                <td><?=$row['userid']?></td>
                                <td><?=$row['serverid']?></td>
                                <td><?=$row['accountid']?></td>
                                <td><?=$row['bossname']?></td>
                                <td><?=$row['dmg']?></td>
                                <td><?=$row['money']?></td>
                                <td><?=$row['rank']?></td>
                                <td><?=$row['lucky']?></td>
                                <td><?=$row['reputation']?></td>
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
