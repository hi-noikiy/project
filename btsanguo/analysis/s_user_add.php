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
                    <input value="UserAdd" name="action" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="account">登录账号</label>
                        <input type="text" name="user[account]" id="account" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="uname">真实姓名</label>
                        <input type="text" name="user[uname]" id="uname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ugrp">用户组</label>
                        <select class="form-control" name="user[ugrp]" id="ugrp">
                            <option value="0">--选择--</option>
                            <?php foreach ($grplist as $grp):?>
                            <option value="<?=$grp['id']?>"><?=$grp['gname'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="upwd">密码</label>
                        <input type="password" name="user[upwd]" id="upwd" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="repwd">确认密码</label>
                        <input type="password" name="user[repwd]" id="repwd" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>用户权限</label>
                        <div id="urights"></div>
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
