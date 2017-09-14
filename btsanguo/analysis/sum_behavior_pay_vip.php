<?php
$initDbSource = 1;
include 'header.php';

$sql_get_tables = "SHOW TABLES LIKE 'rmb%'";
$stmt = $db_source->prepare($sql_get_tables);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (count($_GET) > 0) {
    $dis = new Display($db_sum, $game_id, $bt, $et);
    $types = $dis->GetEmoneyTypes();
    $table_name = $_GET['tb'];
    //$db_sum  = db('analysis');
    $min_sid = intval($_GET['min_sid']);
    $max_sid = intval($_GET['max_sid']);

    $min_vip = intval($_GET['min_vip']);
    $max_vip = intval($_GET['max_vip']);

    $where = ' 1=1';
    if ($min_sid>0 )
    {
        $where .= " AND serverid>=$min_sid";
    }
    if ($max_sid>0) {
        $where .= " AND serverid<=$max_sid";
    }
//查询accountid
    $sql = "SELECT accountid FROM player_info WHERE $where AND viplev BETWEEN $min_vip and $max_vip";
//    echo $sql;
//    $sql = "SELECT accountid FROM player_info WHERE serverid between ? AND ? AND viplev BETWEEN ? and ?";
    $stmt = $db_source->prepare($sql);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_COLUMN);

//    $stmt->execute(array($min_sid, $max_sid, $min_sid, $max_vip));
//    print_r($accounts);
//    exit;
    if ($accounts) {
        $t1 = date('ymd0000', strtotime($bt));
        $t2 = date('ymd2359', strtotime($et));
        $account_ids = implode(',', $accounts);
//        $table_name =
        $sql = <<<SQL
SELECT sum(emoney) as sum_emoney,`type` as stype FROM `{$table_name}`
WHERE accountid IN($account_ids) AND daytime BETWEEN $t1 AND $t2
GROUP BY `type`
SQL;
//        echo $sql;
        $stmt = $db_source->prepare($sql);
        $stmt->execute(array());
//        $stmt->execute(array($t1, $t2));
        $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($lists as $list) {
            $total_emoney += $list['sum_emoney'];
        }
//        print_r($lists);
    }
}
$list_cnt = count($lists);

?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <form class="form-inline" role="form" action="<?=$action;?>">
                            <div class="form-group">
                                <label>表名称</label>
                                <select name="tb">
                                <?php foreach($tables as $table):?>
<!--                                    --><?php //if():continue;?>
                                    <option <?=($table==$table_name) ? 'selected':''?> value="<?=$table?>"><?=$table?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        <div class="form-group">
                            <label>消费时间</label>
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            --
                            <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>

                        <div class="form-group">
                            <label>区服ID</label>
                            <input type="number" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="8"/>至
                            <input type="number" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="8"/>
                        </div>
                        <div class="form-group">
                            <label>VIP等级</label>
                            <input type="number" name="min_vip" placeholder="请输入VIP等级（数字）" value="<?=$_GET['min_vip']?>" class="form-control" size="8"/>至
                            <input type="number" name="max_vip" placeholder="请输入VIP等级(数字）" value="<?=$_GET['max_vip']?>" class="form-control" size="8"/>
                        </div>

                        <button type="submit" class="btn btn-primary">查 询</button>

                        <?php if(!empty($warnMsg)):?>
                            <span class="alert-danger" style="padding: 5px 10px;"><?=$warnMsg?></span>
                        <?php endif;?>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" <?=$list_cnt>0 ? 'id="dataTable"':''?>>
                            <thead>
                            <tr>
                                <th>消费行为</th>
                                <th>总消费</th>
                                <th>比率</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($list_cnt):?>
                                <?php foreach($lists as $key=>$list):?>
                                    <tr>
                                        <td><?php echo $types[$list['stype']]?> [<?=$list['stype']?>] </td>
                                        <td><?php echo $list['sum_emoney']?></td>
                                        <td><?php echo round($list['sum_emoney']/$total_emoney, 6)*100?> %</td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="7">抱歉，没有数据。</td></tr>
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
<?php include 'footer.php'; ?>