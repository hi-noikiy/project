<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-19
 * Time: 下午2:53
 */
include 'header.php';
$db = db(81);
$player = new Player($db);
$searchValue = strip_tags($_GET['searchValue']);
$data = $player->Search(intval($_GET['stype']), $searchValue);
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <form class="form-inline" role="form" method="get">
                        <div class="form-group">
                            <label>查询条件</label>
                            <select name="searchType">
                                <option value="1">玩家账号</option>
                                <option value="2">玩家ID</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>账号或ID</label>
                            <input name="searchValue" type="text" class="form-control" size="18">
                        </div>
                        <button type="submit" class="btn btn-primary">查 询</button>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>账号</th>
                                <th>vip等级</th>
                                <th>总积分</th>
                                <th>剩余积分</th>
                                <th>分包id</th>
                                <th>渠道账号</th>
                                <th>手机标识</th>
                                <th>最后登录</th>
                                <th>注册日期</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($data)):?>
                                <tr>
                                    <td><?=$data['user']['id']; ?></td>
                                    <td><?=$data['user']['NAME']; ?></td>
                                    <td><?=$data['vip']['vip']; ?></td>
                                    <td><?=$data['vip']['pointstotal']; ?></td>
                                    <td><?=$data['vip']['points']; ?></td>
                                    <td><?=$data['user']['dwFenBaoID']; ?></td>
                                    <td><?=$data['user']['channel_account']; ?></td>
                                    <td><?=$data['user']['mac']; ?></td>
                                    <td><?=$data['user']['login_date']; ?></td>
                                    <td><?=$data['user']['reg_date']; ?></td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php include 'footer.php';?>