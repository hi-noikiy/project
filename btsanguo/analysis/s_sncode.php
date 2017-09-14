<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 激活码列表
 */
include 'header.php';
$where = '1=1';
if (!empty($_GET)) {
    if (!empty($_GET['bt'])) {
        $bt1 = date('ymd0000', strtotime($_GET['bt']));
        $bt2 = date('ymd2359', strtotime($_GET['bt']));
        $where .= " AND createtime>$bt1 and createtime<$bt2";
    }
    if (!empty($_GET['item_name'])) {
        $where .= " AND item_name='".trim($_GET['item_name'])."'";
    }
    if (!empty($_GET['param'])) {
        $where .= " AND item_id=".trim($_GET['param']);
    }
}
$total_sql = "SELECT COUNT(*) FROM s_code WHERE {$where}";
//echo $total_sql;
$stmt = $db_sum->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    $sql = "SELECT * FROM s_code WHERE " . $where . " LIMIT $offset,$pageSize";
    $stmt = $db_sum->prepare($sql);
    $stmt->execute();
    $codesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
//$itemId = array();
    $codes = array();
    foreach ($codesArr as $code) {
        $codes[$code['item_id']] = $code;
//    $itemId[$code['item_id']] = $code['createtime'];
    }
    $itemIdStr = implode(',', array_keys($codes));
    $sqlCtn = "SELECT COUNT(*) as cnt,used,param FROM u_code_exchange WHERE param IN($itemIdStr) GROUP BY used";
    $stmt = $db_sum->prepare($sqlCtn);
    $stmt->execute();
    $cnts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cnts as $cnt) {
//    $data[$cnt['params']]['total'] += $cnt['cnt'];
        if ($cnt['used']>0) {
            $codes[$cnt['param']]['used'] = $cnt['cnt'];
        }
        else {
            $codes[$cnt['param']]['un_used'] = $cnt['cnt'];
        }
    }
}


?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form id="frm" class="form-inline" role="form" method="get">
                    <div class="form-group">
                        <label>礼包名称</label>
                        <input name="item_name" type="text" class="form-control" value="<?=$_GET['item_name']?>" >
                    </div>
                    <div class="form-group">
                        <label>礼包ID</label>
                        <input name="param" type="text" class="form-control" value="<?=$_GET['param']?>" >
                    </div>
                    <div class="form-group">
                        <label>生成时间</label>
                        <input type="text" name="bt" class="form-control" size="12" value="<?=$_GET['bt']?>" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)"/>
                    </div>
<!--                    <div class="form-group">-->
<!--                        <label>过期时间</label>-->
<!--                        <input type="text" name="et" class="form-control" size="12" value="--><?//=$_GET['et']?><!--" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)"/>-->
<!--                    </div>-->
                    <button type="button" id="BtnSearch" class="btn btn-primary">查 询</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>礼包ID</th>
                            <th>礼包名称</th>
                            <th>礼包数量</th>
                            <th>使用数量</th>
                            <th>剩余数量</th>
                            <th>生成时间</th>
                            <th>结束时间</th>
                        </thead>
                        <tbody>
                        <?php if(isset($codes)):?>
                            <?php foreach($codes as $code):?>
                            <tr>
                                <td><?=$code['item_id']?></td>
                                <td><?=$code['item_name']?></td>
                                <td><?=$code['used']+$code['un_used']?></td>
                                <td><?=$code['used']?></td>
                                <td><?=$code['un_used']?></td>
                                <td><?=date('Y-m-d H:i',strtotime('20'.$code['createtime']))?></td>
                                <td><?=date('Y-m-d', $code['endtime'])?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9"><?php page($total_rows,$currentPage,$pageSize);?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var frm = $("form#frm");
        $("#BtnExport").on('click', function(){
            frm.attr('target','_target');
            frm.attr('action','s_sncode_export.php');
            frm.submit();
        });
        $("#BtnSearch").on('click', function(){
            frm.attr('target','_self');
            frm.attr('action','#');
            frm.submit();
        });
    });
</script>
<?php include 'footer.php';?>
