<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-08
 * logtime: 下午3:49
 * 特别活动 (World Boss(Special Activity))
 */
include 'header.php';
$flagList = array( 0=>'未封', 1=>'已封',);
$where = '1=1';
$db_game = db('gamedata');
if (!empty($_GET)) {
    if (is_numeric($_GET['account_id']) && $_GET['account_id']>0) {
        $where .= " AND u.accountid=" . $_GET['account_id'];
    }
    if (is_numeric($_GET['server_id1']) && $_GET['server_id1']>0) {
        $where .= " AND u.serverid>=" . $_GET['server_id1'];
    }
    if (is_numeric($_GET['server_id2']) && $_GET['server_id2']>0) {
        $where .= " AND u.serverid<=" . $_GET['server_id2'];
    }
    //|| $_GET['through_type2']>0
    if ($_GET['through_type1']>0 ) {
        $min_time = '';
        $where .= " AND u.min_time{$_GET['through_type1']}>0";
        for ($i=$_GET['through_type1']; $i<=30;$i++) {
            $min_time .= ",u.min_time{$i}";

        }
//        $where .= " AND u.min_time{$_GET['through_type1']}>0";
    }
//    if (is_numeric($_GET['through_type2']) && $_GET['through_type2']>0) {
//        $min_time2 = ",u.min_time{$_GET['through_type2']}";
//        $where .= " AND u.min_time{$_GET['through_type2']}>0";
//    }
    if ($_GET['flag']==1) {
        $where .= " AND u.flag=1";
    }
    if ($_GET['flag']==2) {
        $where .= " AND u.flag=0";
    }
}

$total_sql = <<<SQL
SELECT COUNT(*) FROM u_player_through_time_bg AS u
 LEFT JOIN account_limit l
ON u.accountid=l.accountid AND u.serverid=l.serverid
WHERE $where
SQL;
//echo $total_sql;
$stmt = $db_game->prepare($total_sql);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
if ($total_rows) {
    
    if (isset($min_time)) {
       $sql = <<<SQL
SELECT u.accountid, u.serverid, u.through_type, u.combat, u.`level`, u.logtime,
 u.flag,l.created_time {$min_time} FROM u_player_through_time_bg AS u
 LEFT JOIN account_limit l
ON u.accountid=l.accountid AND u.serverid=l.serverid
WHERE $where
ORDER BY logtime ASC
LIMIT $offset,$pageSize
SQL;
    }
    else {
        $sql = <<<SQL
SELECT u.*, l.created_time FROM u_player_through_time_bg AS u
 LEFT JOIN account_limit l
ON u.accountid=l.accountid AND u.serverid=l.serverid
WHERE $where
ORDER BY logtime ASC
LIMIT $offset,$pageSize
SQL;
    }
//    $sql = <<<SQL
//SELECT u.*, l.created_time FROM u_player_through_time_bg AS u
// LEFT JOIN account_limit l
//ON u.accountid=l.accountid AND u.serverid=l.serverid
//WHERE $where
//ORDER BY logtime ASC
//LIMIT $offset,$pageSize
//SQL;
//    echo $sql;
    $stmt = $db_game->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <label>账号</label>
                        <input type="number"
                               size="4"
                               class="form-control w120"
                               name="account_id" id="account_id"
                               value="<?=$_GET['account_id']?>"/>
                    </div>
                    <div class="form-group">
                        <label>区服</label>
                        <input type="number"
                               size="4"
                               min="900"
                               class="form-control w80"
                               name="server_id1" id="server_id1"
                               value="<?=$_GET['server_id1']?>"/>
                        ~
                        <input type="number"
                               min="900"
                               class="form-control w80"
                               name="server_id2"
                               id="server_id2"
                               value="<?=$_GET['server_id2']?>"/>
                    </div>
                    <div class="form-group">
                        <label>关卡</label>
                        <input type="number"
                               size="4"
                               class="form-control w80"
                               name="through_type1"
                               id="through_type1"
                               value="<?=$_GET['through_type1']?>"/>
<!--                        ~-->
<!--                        <input type="number"-->
<!--                               size="4"-->
<!--                               class="form-control w80"-->
<!--                               name="through_type2"-->
<!--                               id="through_type2"-->
<!--                               value="--><?//=$_GET['through_type2']?><!--"/>-->
                    </div>
                    <div class="form-group">
                        <label for="flag">标记</label>
                        <select class="form-control" name="flag" id="flag">
                            <option value="0" <?=$_GET['flag']==0? 'selected':''?>>默认</option>
                            <option value="1" <?=$_GET['flag']==1? 'selected':''?>>已封</option>
                            <option value="2" <?=$_GET['flag']==2? 'selected':''?>>未封</option>
                        </select>
                    </div>
                    <button type="submit" id="BtnSearch" class="btn btn-primary">查一下</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>账号ID</th>
                            <th>服务器ID</th>
                            <th>战力</th>
                            <th>等级</th>
                            <th>关卡</th>
                            <th>通关时间</th>
                            <th>下线时间</th>
                            <th>标记</th>
                            <th>标记时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php if(isset($data)):?>
                            <?php foreach($data as $row):?>
                            <?php $list_tr = '';?>
                            <?php for($i=1;$i<=30;$i++):?>
                                <?php if($row["min_time{$i}"]>0){
                                 $list_tr .= <<<HTML
                                 <tr class="child">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{$i}</td>
                                        <td>{$row["min_time{$i}"]}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
HTML;
                                    }?>
                            <?php endfor;?>
                                <?php if(!empty($list_tr)):?>
                            <tr>
                                <td class="account"><?=$row['accountid']?></td>
                                <td class="server"><?=$row['serverid']?></td>
                                <td><?=$row['combat']?></td>
                                <td><?=$row['level']?></td>
                                <td>-</td>
                                <td>-</td>
                                <td><?=date('Y-m-d H:i:s',$row['logtime'])?></td>
                                <td><?=$flagList[$row['flag']]?></td>
                                <td><?=$row['created_time'];?></td>
                                <td>
                                    <a class="setFlag"
                                       data-flag="<?php echo !$row['flag'];?>"
                                       data-txt="<?=$flagList[!$row['flag']]?>"
                                       href="javascript:;"
                                       class="btn btn-info">
                                        设置为<?=$flagList[!$row['flag']]?>
                                    </a>
                                </td>
                            </tr>
                            <?php echo $list_tr;?>
                            <?php endif?>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10"><?php page($total_rows,$currentPage,$pageSize);?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        $('.table').on('click', 'a.setFlag', function(){
            var param = {
                    action:'AccountLimit',
                    serverid : $(this).parent().siblings('td.server').text(),
                    accountid : $(this).parent().siblings('td.account').text(),
                    flag: $(this).data('flag')
                },
                txt = $(this).data('txt');
            if (!confirm('您确定要将该区服['+param.serverid+']，账号id['+param.accountid+']设置为['+txt+']吗?')){
                return false;

            }
            $.post('ajax/call.php',param, function(res) {
                if (res.status=='ok') {
                    alert('操作成功');
                    location.reload();
                } else {
                    alert('操作失败，失败信息：' + res.msg);
                }
            }, 'json');
        });
    });
</script>
<?php include 'footer.php';?>
