<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System($db_sum);
$users = $sys->UserList();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_user_add.php" class="btn btn-primary">添加用户</a>
                <a href="s_user_grp.php" class="btn btn-primary">用户组</a>
                <a href="s_file.php" class="btn btn-primary">文件权限</a>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>账号ID</th>
                            <th>姓名</th>
                            <th>上次登录时间</th>
                            <th>登录IP</th>
                            <th>登录次数</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user):?>
                                <tr>
                                    <td><?=$user['account']?></td>
                                    <td><?=$user['uname']?></td>
                                    <td><?=$user['logintime']?></td>
                                    <td><?=$user['loginip']?></td>
                                    <td><?=$user['logincnt']?></td>
                                    <td><?=$user['ustatus']==1? '正常':'<code>已禁用</code>'?></td>
                                    <td>
                                        <a href="s_user_edit.php?uid=<?=$user['id']?>&action=edit">修改</a>
                                        <?php if($user['ustatus']==1):?>
                                            <a href="s_user_edit.php?uid=<?=$user['id']?>&action=disable&status=0">禁用</a>
                                            <?php else:?>
                                            <a href="s_user_edit.php?uid=<?=$user['id']?>&action=disable&status=1">启用</a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
