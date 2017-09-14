<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 * 添加区服
 */
include 'header.php';
if ($_GET['sid'] && is_numeric($_GET['sid'])) {
    $id     = intval($_GET['sid']);
    $sys    = new System($db_sum);
    $server = array_shift($sys->SeverList($id));
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_server.php" class="btn btn-primary">返回区服列表</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <input value="ServerGroupAdd" name="action" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="servername">服务器分组名称</label>
                        <input type="text" name="group_name" value="" placeholder="例如：官方区服" id="servername" class="form-control">
                    </div>
                    <div class="form-group">
                        <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                        <button type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
