<?php
include 'header.php';
include 'inc/give_emoney_types.inc.php';
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-1 days'));
$sql = "SELECT SUM(cnt) as cnt,SUM(sum_emoney) as sum_emoney,stype,serverid,fenbaoid FROM sum_give_emoney WHERE sday=" .  date('Ymd', strtotime($bt));
//echo $sql;
if (count($serverids)) {
    $sql .= ' AND serverid IN('. implode(',', $serverids) . ')';
}
if (count($fenboids)) {
    $sql .= ' AND fenbaoid IN(' . implode(',', $fenbaoids) . ')';
}
$sql .= " GROUP BY stype";
$stmt = $db_sum->prepare($sql);
$stmt->execute();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
$noEndTimeFilter = true;
$goods = Display::GetVipGoods($db_sum);
//print_r($give_emoney_types);
$give_emoney_types = $give_emoney_types + $goods;
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
                                <th>类型</th>
                                <th>人数</th>
                                <th>赠送元宝</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php foreach($lists as $key=>$list):?>
                                    <tr>
                                        <td>[<?=$list['stype']?>]<?=$give_emoney_types[$list['stype']]?></td>
                                        <td><?=$list['cnt']?> </td>
                                        <td><?=$list['sum_emoney']?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="3">抱歉，没有数据。</td></tr>
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