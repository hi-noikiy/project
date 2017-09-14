<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';

if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST' ) {
//    print_r($_POST);
    if ($_POST['action']=='add') {
        if (!empty($_POST['id']) && !empty($_POST['name'])) {
            $sqlInsert = "INSERT INTO u_vipgoods SET id=?,name=?,price=?";
            if ($_POST['multi']) {
                $cnt = count($_POST['id']);
                for($i=0; $i<$cnt; $i++) {
                    if(!$_POST['id'][$i] || !$_POST['name'][$i]) {
                        break;
                    }
                    $data = array(
                        trim($_POST['id'][$i]),
                        trim($_POST['name'][$i]),
                        isset($_POST['price'][$i]) ? intval($_POST['price'][$i]) : 0
                    );
                    $q = $db_sum->prepare($sqlInsert);
                    $ret = $q->execute($data);
                }
            }
            else {
                $data = array(
                    trim($_POST['id']),
                    trim($_POST['name']),
                    isset($_POST['price']) ? intval($_POST['price']) : 0
                );

                $q = $db_sum->prepare($sqlInsert);
                $ret = $q->execute($data);
            }

            $msg = '添加成功!';
        }
        else {
            $msg = '商品ID和商品名称不能为空！';
        }

    }
    elseif ($_POST['action']=='rm') {
        if (count($_POST['ids'])) {
            $ids = implode(',', $_POST['ids']);
            $sqlRm = "DELETE FROM u_vipgoods WHERE id IN($ids)";
            $ret = $db_sum->exec($sqlRm);
            $msg = '删除成功';
        }
    }

    if ($ret!==false && $msg) {
        echo '<script>alert("'.$msg.'");location.href="s_vipgoods.php";</script>';
    }
}
$query = array();
if (!empty($_GET['id'])) {
    $where .= " AND id=?";
    $query[] = trim($_GET['id']);
}
if (!empty($_GET['name'])) {
    $where .= " AND name LIKE ?";
    $query[] = '%'.trim($_GET['name']).'%';
}
$sql = "SELECT * FROM u_vipgoods WHERE 1=1  {$where}  LIMIT $offset,$pageSize";
$s = $db_sum->prepare($sql);
$s->execute($query);
$vipgoods = $s->fetchAll(PDO::FETCH_ASSOC);
$totalSql = "SELECT COUNT(*) FROM u_vipgoods  WHERE 1=1 {$where}";
$s2 = $db_sum->prepare($totalSql);
$s2->execute($query);
$total_rows = $s2->fetchColumn(0);
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php if($_GET['add_multi']):?>
                        <form id="frm" class="form-inline" role="form" method="post">
                            <input name="action" value="add" type="hidden"/>
                            <input name="multi" value="1" type="hidden"/>
                            <?php for($i=0; $i<10; $i++):?>
                            <p>
                                <div class="form-group">
                                    <label>物品ID</label>
                                    <input name="id[]" type="text" class="form-control" size="10">
                                </div>
                                <div class="form-group">
                                    <label>物品名称</label>
                                    <input name="name[]" type="text" class="form-control" size="10">
                                </div>
                                <div class="form-group">
                                    <label>物品价格</label>
                                    <input name="price[]" type="text" class="form-control" size="4">
                                </div>
                            </p>
                            <?php endfor;?>
                            <button data-id="add" type="button" class="btn btn-primary">添 加</button>
                            <a href="s_vipgoods.php" class="btn btn-info">取 消</a>
                        </form>
                    <?php else:?>
                        <form id="frm" class="form-inline" role="form" method="post">
                            <input id="act" name="action" value="add" type="hidden"/>
                            <div class="form-group">
                                <label>物品ID</label>
                                <input name="id" type="text" value="<?=$_GET['id']?>" class="form-control" size="10">
                            </div>
                            <div class="form-group">
                                <label>物品名称</label>
                                <input name="name" type="text"  value="<?=$_GET['name']?>" class="form-control" size="10">
                            </div>
                            <div class="form-group">
                                <label>物品价格</label>
                                <input name="price" type="text" class="form-control" size="4">
                            </div>
                            <button data-id="search" type="button" class="btn btn-info">查 找</button>
                            <button data-id="add" type="button" class="btn btn-primary">添 加</button>
                            <a href="s_vipgoods.php?add_multi=1">批量添加</a>
                        </form>
                    <?endif;?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <form method="post">
                            <input name="action" value="rm" type="hidden"/>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>选择</th>
                                    <th>物品ID</th>
                                    <th>物品名称</th>
                                    <th>物品价格</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($vipgoods as $good):?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="<?=$good['id']?>"/></td>
                                        <td><?=$good['id']?></td>
                                        <td><?=$good['name']?></td>
                                        <td><?=$good['price']?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                                <tfoot>
                                    <td>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('您确定要删除吗？');">删除</button>
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
<?php
$script = <<<SCRIPT
    <script>
        $(".btn").on('click', function(){
            var type = $(this).data('id'),
                act = $("#act");
            if (type=='add') {
                $("#frm").attr('method', 'post');
                act.val('add');
            } else if(type='search') {
                $("#frm").attr('method', 'get');
                act.val('search');
            }
            $("#frm").submit();
        });
    </script>
SCRIPT;

?>
<?php include 'footer.php';?>