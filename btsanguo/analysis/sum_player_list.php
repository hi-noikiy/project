<?php
/**
 * 用户信息
 */
include 'header.php';
$dis = new Display($db_sum);
$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d H:i', strtotime('-1 hours'));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d H:i');
$time_format = ' hh:mm';
if ($_SESSION['uid']==17) {
    $noFenbaoFilter = true;
}
try{
    $db = db('gamedata');
//    $total_rows = $dis->PlayerInfo($db, $bt, $et, $serversid, $fenbaosid, 0,0 , true);
    if (!empty($_GET['userName']) || !empty($_GET['accountid'])) {
        $username = $_GET['userName'] ? trim($_GET['userName']) : null;
        $accountid   = $_GET['accountid'] ? intval($_GET['accountid']) : 0;
        $data = $dis->PlayerSearch($db, $accountid, $username,$serverids);
        if (!$data['total']) {
            $show_tbls = "SHOW TABLES";
            $t1 = $db->prepare($show_tbls);
            $t1->execute();
            $tables = $t1->fetchAll(PDO::FETCH_COLUMN);
            foreach( $tables as $table ) {
                if(strpos($table, 'player_20')!==false) {
                   // echo strpos($table,'_') ,'----',$table . PHP_EOL;
                    $date = substr($table,strpos($table,'_')+1);
                    $tbls[$date]['player'] = $table;
                }
                elseif(strpos($table, 'newmac_20')!==false) {
                   // echo $pos ,'----',$table . PHP_EOL;
                    $date = substr($table,strpos($table,'_')+1);
                    $tbls[$date]['newmac'] = $table;
                }
                elseif(strpos($table, 'loginmac_20')!==false) {
                //echo $pos ,'----',$table . PHP_EOL;
                    $date = substr($table,strpos($table,'_')+1);
                    $tbls[$date]['loginmac'] = $table;
                }
            }
            // print_r($tbls);
            foreach($tbls as $date=>$tb) {
                // print_r($tb);
                $data = $dis->PlayerSearch($db, $accountid, $username,$serverids,$tb);
                if (count($data)) {
                    break;
                }
            }
        }
    }
    elseif($_SESSION['uid']!=17) {
        $data = $dis->PlayerInfo($db, $bt, $et, $serverids, $fenbaoids, $offset, $pageSize);
    }
} catch(Exception $e) {
    echo $e->getMessage();
}
//$noEndTimeFilter = true;
$profArr = array(1=>'龙将', 2=>'剑舞',3=>'箭神','军师');
//$action = 'sum_reg_trans_total.php';
$userInfoFilter = true;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <?php include 'inc/search_form.inc.php'; ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?=$lang['user_reg_date']?></th>
                                <th><?=$lang['user_account_id']?></th>
                                <th><?=$lang['user_role_name']?></th>
                                <th><?=$lang['user_role_id']?></th>
                                <th><?=$lang['server']?></th>
                                <th><?=$lang['channel']?></th>
                                <th><?=$lang['user_reg_ip']?></th>
                                <th><?=$lang['user_last_login']?></th>
                                <th><?=$lang['user_last_login_ip']?></th>
                                <th><?=$lang['user_mac']?></th>
                                <th><?=$lang['client_type']?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($data['total']):?>
                        <?php foreach($data['list'] as $list):?>
                                <?php if($list['fenbaoid']==60073) continue;?>
                            <tr>
                                <td><?=date('Y-m-d H:i:00', strtotime('20'.$list['createtime']));?></td>
                                <td><?=$list['accountid']?></td>
                                <td><?=$list['name'];?></td>
                                <td><?=$list['userid'];?></td>
                                <td>[<?=$list['serverid']?>]<?=$serversList[$list['serverid']];?></td>
                                <td>[<?=$list['fenbaoid']?>]<?=$fenbaos[$list['fenbaoid']];?></td>
                                <td><?=$list['ip'];?></td>
                                <td><?=$list['logintime'];?></td>
                                <td><?=$list['loginip'];?></td>
                                <td><input type="text" value="<?=$list['mac'];?>"/></td>
                                <td><?=$list['clienttype'];?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr><td colspan="11"><?=$lang['no_data']?></td></tr>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="11"><?php page($data['total'],$currentPage,$pageSize);?></td>
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