<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-09
 * logtime: 下午3:49
 * 比如我想查这个月VIP8级玩家的数据呢。
 * 就是在这个月期间VIP等级是8级的，满足这个条件的玩家这个月的充值元宝数量和消费数量。
 */
include 'header.php';
$server_id_list = array(
    501, 502, 503, 504, 505, 506, 507, 508, 901, 902, 903, 904, 905, 906, 907, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 935, 936, 937, 938, 939, 940, 941, 942, 943, 944, 945, 946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960, 961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 974, 975, 976, 977, 978, 979, 980, 981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999, 9100, 9101, 9102, 9103, 9104, 9105, 9106, 9107, 9108, 9109, 9110, 9111, 9112, 9113, 9114, 9115, 9116, 9117, 9118, 9119, 9120, 9121, 9122, 9123, 9124, 9125, 9126, 9127, 9128, 9129, 9130, 9131, 9132, 9133, 9134, 9135, 9136, 9137, 9138, 9139, 9140, 9141, 9142, 9143, 9144, 9145, 9146, 9147, 9148, 9149, 9150, 9151, 9152, 9153, 9154, 9155, 9156, 9157, 9158, 9159, 9160, 9161, 9162, 9163, 9164, 9165, 9166, 9167, 9168, 9169, 9170, 9171, 9172, 9173, 9174, 9175, 9176, 9177, 9178, 9179, 9180, 9181, 9182, 9183, 9184, 9185, 9186, 9187, 9188, 9189, 9190, 9191, 9192, 9193, 9194,
);
$db_game = db('gamedata');
$flag = false;
if (!empty($_GET)) {
    $flag = true;
    $vip_lvl = intval($_GET['vip_lvl']);
    $bt_time_stamp = strtotime($_GET['bt']);
    $et_time_stamp = strtotime($_GET['et']);
    //get account by vip level
    $t1 = date('1ymd', $bt_time_stamp);
    $t2 = date('1ymd', $et_time_stamp);
    $tt1 = date('ymd0000', $bt_time_stamp);
    $tt2 = date('ymd2359', $et_time_stamp);

    $month  = date('m', $bt_time_stamp);
    $current_month = date('m', $_SERVER['REQUEST_TIME']);
    $month_table = '';
    if ($month!=$current_month) {
        $month_table = "_{$month}";
    }

    $sql = "SELECT accountid,userid,serverid FROM dayonline{$month_table} WHERE (daytime BETWEEN ? and ?) AND viplev=?";
    $stmt = $db_game->prepare($sql);
    $stmt->execute(array( $t1,$t2,$vip_lvl));
    $account_id_arr = $user_id_arr = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $account_id_arr[] = $row['accountid'];
        $user_id_arr[$row['serverid']][] = $row['userid'];
    }
    if ($total_account=count($account_id_arr)) {
        $account_id_str = implode(',', $account_id_arr);
        //消费数量
        $sql = "SELECT SUM(emoney) FROM rmb WHERE daytime BETWEEN $tt1 AND $tt2 AND accountid IN($account_id_str)";
        $stmt = $db_game->prepare($sql);
        $stmt->execute();
        $total_emoney_use = $stmt->fetchColumn(0);
        $emoney_pay = 0;//充值产出元宝
        //充值数量
        foreach ($user_id_arr as $server_id=>$userid) {
            $user_id_str = implode(',', $userid);
            $sql = <<<SQL
SELECT SUM(emoney) FROM rmb_emoney WHERE serverid=$server_id
AND idUser IN($user_id_str)
SQL;
            $stmt = $db_game->prepare($sql);
            $stmt->execute();
            $emoney_pay += $stmt->fetchColumn(0);
        }
        unset($user_id_arr);
        unset($account_id_arr);
    }
}



?>
<style>
    .form-inline .form-control.w80 {
        width: 80px;
    }
    .form-inline .form-control.w120 {
        width: 120px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form id="frm" class="form-inline" role="form" method="get">
                    <div class="form-group">
                        <label>筛选时间</label>
                        <input name="bt" type="text"
                               class="form-control" size="18"
                               value="<?=$bt?>"
                               onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                        --
                        <input name="et" type="text"
                               class="form-control" size="18"
                               value="<?=$et?>"
                               onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
                    </div>
                    <div class="form-group">
                        <label>VIP等级</label>
                        <input type="number"
                               size="4"
                               min="1"
                               class="form-control w80"
                               name="vip_lvl" id="server_id1"
                               value="<?=$_GET['vip_lvl']?>"/>
                    </div>

                    <button type="submit" id="BtnSearch" class="btn btn-primary">统计</button>
                </form>
            </div>
            <div class="panel-body">
                <?php if($flag):?>
                    <?php if($total_account>0):?>
                    <div class="alert alert-success" role="alert">
                        <h2>玩家总数: <strong><?=$total_account?></strong> </h2>
                    </div>
                    <div>以下数据由查询时间段统计得到。</div>
                    <div class="alert alert-success" role="alert">
                        <h2>充值元宝数量: <strong><?=$emoney_pay?></strong> </h2>
                    </div>
                    <div class="alert alert-success" role="alert">
                        <h2>消费元宝数量: <strong><?=$total_emoney_use?></strong> </h2>
                    </div>
                    <?php else:?>
                    <div class="alert alert-danger" role="alert">
                        抱歉，以上查询条件没有查找到玩家数据，请您更换查询条件试试吧。
                    </div>
                    <?php endif;?>
                <?php else:?>
                    <div class="alert alert-warning" role="alert">请选择时间和VIP等级进行统计。由于数据量较大，请耐心等待。</div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
