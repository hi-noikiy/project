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

            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>编号</th>
                            <th>操作人</th>
                            <th>登录时间</th>
                            <th>登录IP</th>
                            <th>操作功能</th>
                            <th>操作说明</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log):?>
                                <tr>
                                    <td><?=$log['ID']?></td>
                                    <td><?=$log['account']?></td>
                                    <td><?=$log['uname']?></td>
                                    <td><?=$log['logintime']?></td>
                                    <td><?=long2ip($log['loginip'])?></td>
                                    <td><?=$log['logincnt']?></td>
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
