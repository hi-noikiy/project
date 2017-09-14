<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System($db_sum);
$servers = $sys->SeverList();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_server_add.php" class="btn btn-primary">添加区服</a>
<!--                <a href="s_user_grp_add.php" class="btn btn-primary">添加用户组</a>-->
<!--                <a href="s_file_add.php" class="btn btn-primary">添加文件权限</a>-->
                <span id="msg" style="display: none;"></span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>区服ID</th>
                            <th>区服名称</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($servers as $server):?>
                                <tr>
                                    <td><?=$server['serverid']?></td>
                                    <td><?=$server['servername']?></td>
                                    <td><?=$server['opentime']?></td>
                                    <td>
                                        <a href="s_server_add.php?sid=<?=$server['id']?>">修改</a>
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
