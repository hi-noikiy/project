<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System(db('analysis'));
$id = intval($_GET['gid']);
$data = $sys->GroupList($id);
$grp = array_shift($data);
$fileList = $sys->FileList();
$my_files = array();
foreach ($fileList as $f) {
    $my_files[$f['gid']][] = array(
        'id'            => $f['id'],
        'title_zh_CN'   => $f['ftitle_zh'],
        'title_en_US'   => $f['ftitle_en'],
        'path'          => $f['fpath'],
    );
}
//print_r($fileList);
//print_r($files);
//exit;
$grights = $sys->FilesFormat($my_files, $grp['grights']);
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
                    <input value="GroupUpdate" name="action" type="hidden"/>
                    <input value="<?=$grp['id']?>" name="grp[id]" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="account">组名称</label>
                        <input type="text" name="grp[gname]" value="<?=$grp['gname']?>" id="gname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ugrp">组类型</label>
                        <label class="radio-inline">
                            <input type="radio" <?=$grp['gtype']==2 ? 'checked':''?> name="grp[gtype]" value="2">管理员
                        </label>
                        <label class="radio-inline">
                            <input type="radio" <?=$grp['gtype']==3 ? 'checked':''?> name="grp[gtype]" value="3">普通用户
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
