<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System(db('analysis'));
$grplist = $sys->GroupList();
include 'inc/files.inc.php';
$fileList = $sys->FileList();
foreach ($fileList as $f) {
    $files[$f['gid']][] = array(
        'id'            => $f['id'],
        'title_zh_CN'   => $f['ftitle_zh'],
        'title_en_US'   => $f['ftitle_en'],
        'path'          => $f['fpath'],
    );
}
$grights = $sys->FilesFormat($files);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_user.php" class="btn btn-primary">返回用户列表</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <input value="GroupAdd" name="action" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="account">组名称</label>
                        <input type="text" name="grp[gname]" id="gname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ugrp">组类型</label>
                        <label class="radio-inline">
                            <input type="radio" name="grp[gtype]" value="2">管理员
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="grp[gtype]" value="2">普通用户
                        </label>
                    </div>
                    <div class="form-group">
                        <label>组权限</label>
                        <div id="urights"><?=$grights?></div>
                    </div>
                    <div class="form-group">
                        <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                        <button id="btnCancel" type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
