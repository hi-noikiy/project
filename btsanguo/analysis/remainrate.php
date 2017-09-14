<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2014年6月6日15:16:50
 * 用户留存-实时
 */
$initDbSource = true;
include 'header.php';
$t1 = date('ymd0000');
$t2 = date('ymdHi');
$loginTime = date('Ymd');
function loginAfterDays(PDO $db, $createTimeBegin, $createTimeEnd, $loginTime, $serverId=array(), $fenbaoId=array())
{
    $sql_cnt_newlogin = <<<SQL
       SELECT accountid
       FROM newmac WHERE createtime >= $createTimeBegin AND createtime <= $createTimeEnd
SQL;
//    echo '<h4>' . $sql_cnt_newlogin . '</h4>';
    $where = '';
    if (count($serverId)) {
        $where = " AND serverid IN(" . implode(',', $serverId) . ")";
    }
    if (count($fenbaoId)) {
        $where = " AND fenbaoid IN(" . implode(',', $fenbaoId) . ")";
    }
    //echo $sql_cnt_newlogin;
    $stmt = $db->prepare($sql_cnt_newlogin . $where);
    $stmt->execute();
    $accountIdArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!count($accountIdArr)) {
        return array('new'=>0, 'login'=>0);
    }
    //echo count($accountIdArr);
    $accountid  = implode(',', $accountIdArr);
    //fenbaoid, serverid, GROUP BY fenbaoid,serverid
    $sql_login = "SELECT count(*) AS cnt FROM loginmac WHERE logintime=$loginTime AND accountid IN({$accountid})";
//    echo '<h4>' . $sql_login . '</h4>';
    $stmt = $db->prepare($sql_login . $where);
    $stmt->execute();
    $loginCnt = $stmt->fetchColumn();
    return array('new'=>count($accountIdArr), 'login'=>$loginCnt);
//        print_r($lists_login);
//        echo count($lists_login);
//        exit;
//    $strValues = '';
//    foreach ($lists_login as $d) {
//        $strValues .= "({$d['serverid']}, {$d['fenbaoid']},{$this->gameid}, {$sday},{$d['cnt']}),";
//    }
//    $strValues = rtrim($strValues, ',');
//    return $strValues;
}


//TODO::今天的注册,
//GROUP BY fenbaoid,serverid
//$sql = <<<SQL
//  SELECT count(*) as cnt
//  FROM `newmac`
//  WHERE `createtime`>=$t1 AND createtime<=$t2
//SQL;
//$stmt = $db_source->prepare($sql);
//$stmt->execute();
//$todayNew = $stmt->fetchColumn();
//昨天的注册
//$day1 = loginAfterDays($db_source, date('ymd0000', strtotime('-1 days')), date('ymd2359', strtotime('-1 days')),$loginTime);
$dayList = array(1, 3, 7, 15, 30);
$data = array();
foreach ( $dayList as $i){
    $tm   = strtotime("- $i days");
    $createTimeBegin = date('ymd0000', $tm);//19
    $createTimeEnd   = date('ymd2359', $tm);//19
    $data['day' . $i] = loginAfterDays($db_source, $createTimeBegin, $createTimeEnd, $loginTime, $serverids, $fenbaids);
}
$noTimeFilter = true;
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
<!--                                <th>注册</th>-->
                                <th>次日留存</th>
                                <th>3日留存</th>
                                <th>7日留存</th>
                                <th>15日留存</th>
                                <th>月留存</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($data)):?>
                                <tr>
                                    <td>
<!--                                        --><?php //echo $data['day1']['login']?><!-- |-->
                                        <?php echo @round($data['day1']['login']/$data['day1']['new'], 4)*100;?>%
                                        （<?=$data['day1']['login']?> / <?=$data['day1']['new']?>）
                                    </td>
                                    <td>
<!--                                        --><?php //echo $data['day3']['login']?><!--|-->
                                        <?php echo @round($data['day3']['login']/$data['day3']['new'], 4)*100;?>%
                                        （<?=$data['day3']['login']?> / <?=$data['day3']['new']?>）
                                    </td>
                                    <td>
<!--                                        --><?php //echo $data['day7']['login']?><!-- |-->
                                        <?php echo @round($data['day7']['login']/$data['day7']['new'], 4)*100;?>%
                                        （<?=$data['day7']['login']?> / <?=$data['day7']['new']?>）
                                    </td>
                                    <td>
<!--                                        --><?php //echo $data['day15']['login']?><!--|-->
                                        <?php echo @round($data['day15']['login']/$data['day15']['new'], 4)*100;?>%
                                        （<?=$data['day15']['login']?> / <?=$data['day15']['new']?>）
                                    </td>
                                    <td>
<!--                                        --><?php //echo $data['day30']['login']?><!--|-->
                                        <?php echo @round($data['day30']['login']/$data['day30']['new'], 4)*100;?>%
                                        （<?=$data['day30']['login']?> / <?=$data['day30']['new']?>）
                                    </td>
                                </tr>
                            <?php else:?>
                                <tr>
                                    <td colspan="<?php echo $col;?>">Sorry,no data exist.</td>
                                </tr>
                            <?php endif;?>
                            </tbody>

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