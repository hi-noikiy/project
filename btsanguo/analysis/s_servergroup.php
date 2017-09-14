<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System($db_sum);
$servers = $sys->ServerGroupList();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_servergroup_add.php" class="btn btn-primary">添加区服分组</a>
                <span id="msg" style="display: none;"></span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>分组名称</th>
                            <th>拥有区服数</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($servers as $server):?>
                                <tr>
                                    <td><?=$server['group_name']?></td>
                                    <td><?=$server['cnt']?></td>
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
