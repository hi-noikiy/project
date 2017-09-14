<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System($db_sum);
$grps = $sys->GroupList();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_user_add.php" class="btn btn-primary">添加用户</a>
                <a href="s_user_grp_add.php" class="btn btn-primary">添加用户组</a>
                <a href="s_file.php" class="btn btn-primary">文件权限</a>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>组名称</th>
                            <th>组类型</th>
                            <th>组权限</th>
                            <th>修改</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grps as $grp):?>
                                <tr>
                                    <td><?=$grp['id']?></td>
                                    <td><?=$grp['gname']?></td>
                                    <td><?=$grp['gtype']==2?'管理员':'普通用户';?></td>
                                    <td>
                                        <?php $grights = explode(',', rtrim($grp['grights'],','));?>
                                        <?php foreach ($grights as $rid):?>
                                            <span><?=$files_no_grp[$rid]['title_'.$_COOKIE['lang']]?>;</span>
                                        <?php endforeach;?>
                                    </td>
                                    <td>
                                        <a href="s_user_grp_edit.php?gid=<?=$grp['id']?>">修改</a>
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
