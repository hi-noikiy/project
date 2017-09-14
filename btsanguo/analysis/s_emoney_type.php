<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sql = "SELECT * FROM emoney_type LIMIT $offset,$pageSize";
$s = $db_sum->prepare($sql);
$s->execute();
$emoneyTypes = $s->fetchAll(PDO::FETCH_ASSOC);
$totalSql = "SELECT COUNT(*) FROM emoney_type";
$s2 = $db_sum->prepare($totalSql);
$s2->execute();
$total_rows = $s2->fetchColumn(0);
if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST' ) {
    if ($_POST['action']=='add') {
        if (!empty($_POST['type']) && !empty($_POST['type_name'])) {
            $sqlInsert = "INSERT INTO emoney_type (type,type_name) VALUES (?,?)";
            $data = array($_POST['type'],trim($_POST['type_name']));
            $q = $db_sum->prepare($sqlInsert);
            $ret = $q->execute($data);
            $msg = '添加成功!';
        }
        else {
            $msg = '类型名称和类型ID不能为空!';
        }
    }
    elseif ($_POST['action']=='rm') {
        if (count($_POST['ids'])) {
            $ids = implode(',', $_POST['ids']);
            $sqlRm = "DELETE FROM emoney_type WHERE id IN($ids)";
//            echo $sqlRm;
            $ret = $db_sum->exec($sqlRm);
            $msg = '删除成功';
        }
    }
    if ($ret!==false && $msg) {
        echo '<script>alert("'.$msg.'");location.href="s_emoney_type.php";</script>';
    }
}
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <form class="form-inline" role="form"method="post">
                        <input name="action" value="add" type="hidden"/>
                        <div class="form-group">
                            <label>类型名称</label>
                            <input name="type_name" type="text" class="form-control" size="10">
                        </div>
                        <div class="form-group">
                            <label>类型ID</label>
                            <input name="type" type="text" class="form-control" size="4">
                        </div>
                        <button type="submit" class="btn btn-primary">添 加</button>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <form method="post">
                            <input name="action" value="rm" type="hidden"/>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>选择</th>
                                    <th>类型名称</th>
                                    <th>类型ID</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($emoneyTypes as $etype):?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="<?=$etype['id']?>"/></td>
                                        <td><?=$etype['type']?></td>
                                        <td><?=$etype['type_name']?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                                <tfoot>
                                    <td>
                                        <button type="submit" onclick="return confirm('您确定要删除吗？');">删除</button>
                                    </td>
                                    <td colspan="3">
                                        <?php page($total_rows,$currentPage,$pageSize);?>
                                    </td>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php include 'footer.php';?>