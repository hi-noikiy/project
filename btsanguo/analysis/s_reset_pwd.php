<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
<!--            <div class="panel-heading">-->
<!--                <a href="s_user.php" class="btn btn-primary">返回用户列表</a>-->
<!--            </div>-->
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <input value="UserPasswordReset" name="action" type="hidden"/>

                    <div class="form-group">
                        <label class="control-label" for="oldpwd">原密码</label>
                        <input type="password" name="user[oldpwd]" id="oldpwd" class="form-control" placeholder="请输入旧的密码">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="upwd">修改密码</label>
                        <input type="password" name="user[upwd]" id="upwd" class="form-control" placeholder="请输入新密码">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="repwd">确认密码</label>
                        <input type="password" name="user[repwd]" id="repwd" class="form-control" placeholder="请再次输入新密码">
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
