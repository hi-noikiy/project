<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户充值
 */
include 'header.php';

$player = new Player($db_sum, $game_id);
$minMoney = isset($_GET['minMoney']) ? intval($_GET['minMoney']) : 5000;
$data   = $player->ShowVipPlayer($bt, $et, $minMoney, $fenbaoids, $offset, $pageSize);
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <label>充值时间</label>
                            <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                            --
                            <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        </div>
                        <div class="form-group">
                            <label>最小充值金额</label>
                            <input name="minMoney" type="number" class="form-control" size="4" value="<?=$minMoney?>" >
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label>区服</label>-->
<!--                            --><?php //echo htmlMulSelect($servers, 'serverids[]', $serverids, array('id'=>'serverid','class'=>'mul'), true, $grps);?>
<!--                        </div>-->
                        <div class="form-group">
                            <label>渠道</label>
                            <?php echo htmlMulSelect($fenbaos, 'fenbaoids[]', $fenbaoids, array('id'=>'fenbaoid','class'=>'mul'));?>
                        </div>
                        <button type="submit" class="btn btn-primary">查 询</button>
                        <?php if(!empty($warnMsg)):?>
                            <span class="alert-danger" style="padding: 5px 10px;"><?=$warnMsg?></span>
                        <?php endif;?>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>充值时间</th>
                                <th>玩家账号</th>
                                <th>角色名</th>
                                <th>充值金额</th>
                                <th><?=$lang['server'];?></th>
                                <th><?=$lang['channel'];?></th>
                            </tr>
                            </thead>
                            <tbody id="PayLogList">
                            <?php foreach($data['list'] as $list):?>
                                <tr>
                                    <td><?=$list['Add_Time']?></td>
                                    <td><?=$list['PayID']?></td>
                                    <td>
                                        <input type="text" name="username"/>
                                        <a class="showPlayerName" data-server="<?=$list['ServerID']?>" href="javascript:;" data-account="<?=$list['PayID']?>">查看</a>
                                    </td>
                                    <td><?=$list['PayMoney']?></td>
                                    <td>[<?=$list['ServerID']?>]<?=$serversList[$list['ServerID']]?></td>
                                    <td>[<?=$list['dwFenBaoID']?>]<?=$fenbaos[$list['dwFenBaoID']]?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6"><?php page($data['total'],$currentPage,$pageSize);?></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php include 'footer.php';?>