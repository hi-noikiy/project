<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
//$str = '';
//$cnt = 0;
//foreach ($fenbaos as $id=>$name) {
//    $str .= "INSERT INTO user_fenbao (fenbao_name,fenbao_id,fenbao_sort) VALUES ('$name',$id,$cnt);\n";
//    $cnt += 1;
//
//}
//echo $str;
//exit;
$sql = "SELECT * FROM user_fenbao ORDER BY fenbao_sort ASC,fenbao_id ASC";
$s = $db_sum->prepare($sql);
$s->execute();
$fenBaoList = $s->fetchAll(PDO::FETCH_ASSOC);
if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST' ) {
    $sys = new System($db_sum);
    if ($_POST['action']=='add') {
        if (!empty($_POST['fenbao_name']) && !empty($_POST['fenbao_id'])) {
            $fenbao_sort = $_POST['fenbao_sort'] ? intval($_POST['fenbao_sort']) : 0;
            $sqlInsert = "INSERT INTO user_fenbao (fenbao_name,fenbao_id,fenbao_sort) VALUES (?,?,?)";
            $data = array($_POST['fenbao_name'],trim($_POST['fenbao_id']),$fenbao_sort);
            $q = $db_sum->prepare($sqlInsert);
            $ret = $q->execute($data);
            $sys->GenerateFenBaoCache();
            $msg = '添加成功!';
        }
        else {
            $msg = '渠道ID和渠道名称不能为空！';
        }
    }
    elseif ($_POST['action']=='rm') {
        if (count($_POST['ids'])) {
            $ids = implode(',', $_POST['ids']);
            $sqlRm = "DELETE FROM user_fenbao WHERE id IN($ids)";
            $ret = $db_sum->exec($sqlRm);
            $sys->GenerateFenBaoCache();
            $msg = '删除成功';
        }
    }
    if ($ret!==false && $msg) {
        echo '<script>alert("'.$msg.'");location.href="s_fenbao.php";</script>';
    }
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <span id="msg" style="display: none;"></span>
                <form id="frm" class="form-inline" role="form" method="post">
                    <input id="act" name="action" value="add" type="hidden"/>
                    <div class="form-group">
                        <label>渠道ID</label>
                        <input name="fenbao_id" type="text" class="form-control" size="10">
                    </div>
                    <div class="form-group">
                        <label>渠道名称</label>
                        <input name="fenbao_name" type="text" class="form-control" size="10">
                    </div>
                    <div class="form-group">
                        <label>排序</label>
                        <input name="fenbao_sort" type="number" class="form-control" size="4">
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
                                <th>序号</th>
                                <th>渠道名称</th>
                                <th>渠道ID</th>
                                <th>排序</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0; ?>
                            <?php foreach ($fenBaoList as $fenbao):?>
                                <?php $i+=1;?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?=$fenbao['id']?>"/><?=$i?></td>
                                    <td><?=$fenbao['fenbao_name']?></td>
                                    <td><?=$fenbao['fenbao_id']?></td>
                                    <td><input style="width: 80px;" data-fid="<?=$fenbao['id']?>" name="fsort" type="number" class="fenbao form-control" value="<?=$fenbao['fenbao_sort']?>" /></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                            <tfoot>
                                <td colspan="4">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('您确定要删除吗？');">删除</button>
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
