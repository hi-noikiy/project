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
$uid = intval($_GET['uid']);
if($_GET['action']=='disable') {
    $ret = $sys->UserDisable($uid, intval($_GET['status']));
    echo '<script>alert("'.$ret['msg'].'");location.href="s_user.php";</script>';
    exit;
}
$user = $sys->UserShow($uid);

$arr = $sys->GroupFileList($user['ugrp']);
$urights = $sys->FilesFormat($arr, $user['urights']);
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
                    <input value="UserUpdate" name="action" type="hidden"/>
                    <input value="<?=$user['id']?>" name="user[id]" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="account">登录账号</label>
                        <input type="text" name="user[account]" id="account" value="<?=$user['account']?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="uname">真实姓名</label>
                        <input type="text" name="user[uname]" id="uname" value="<?=$user['uname']?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ugrp">用户组</label>
                        <select class="form-control" name="user[ugrp]" id="ugrp">
                            <option value="0">--选择--</option>
                            <?php foreach ($grplist as $grp):?>
                            <option value="<?=$grp['id']?>" <?=$grp['id']==$user['ugrp'] ? 'selected':''?>><?=$grp['gname']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>用户权限</label>
                        <div id="urights"><?=$urights?></div>
                    </div>
                    <div class="form-group">
                        <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                        <button id="btnCancel"   type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
