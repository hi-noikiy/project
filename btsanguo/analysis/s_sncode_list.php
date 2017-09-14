<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 激活码列表
 */
include 'header.php';
$rtL = array('早于','晚于');
if (!empty($_GET)) {
    $where = '1=1';
    if (!empty($_GET['sn_code'])) {
        $where .= " AND code_id='".trim($_GET['sn_code'])."'";
    }
    else {
        if (!empty($_GET['bt'])) {
            $bt1 = date('ymd0000', strtotime($_GET['bt']));
            $bt2 = date('ymd2359', strtotime($_GET['bt']));
            $where .= " AND time_stamp>$bt1 and time_stamp<$bt2";
        }
        if (!empty($_GET['et'])) {
            $tm = strtotime($_GET['et']);
            $where .= " AND time_limit=$tm";
        }
        if (!empty($_GET['account_id'])) {
            $account_id = trim($_GET['account_id']);
            $where .= " AND account_id=$account_id";
        }
        if ($_GET['used']>-1) {
            $where .= " AND used=".intval($_GET['used']);
        }
        if ($_GET['used_type']) {
            $where .= " AND used_type=".intval($_GET['used_type']);
        }
        if ($_GET['param']) {
            $where .= " AND param=".trim($_GET['param']);
        }
    }
    $total_sql = "SELECT COUNT(*) FROM u_code_exchange WHERE {$where}";
    $stmt = $db_sum->prepare($total_sql);
    $stmt->execute();
    $total_rows = $stmt->fetchColumn(0);
    $sql = "SELECT * FROM u_code_exchange WHERE " . $where . " LIMIT $offset,$pageSize";
//    echo $sql;
    $stmt = $db_sum->prepare($sql);
    $stmt->execute();
    $codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form id="frm" class="form-inline" role="form" method="get">
                    <div class="form-group">
                        <label>激活码</label>
                        <input name="sn_code" type="text" class="form-control" value="<?=$_GET['sn_code']?>" >
                    </div>
                    <div class="form-group">
                        <label>物品ID</label>
                        <input name="param" type="text" class="form-control" value="<?=$_GET['param']?>" >
                    </div>
                    <div class="form-group">
                        <label>账号ID</label>
                        <input name="account_id" type="text" class="form-control" value="<?=$_GET['account_id']?>" >
                    </div>
                    <div class="form-group">
                        <label>批次号</label>
                        <input type="text" name="used_type" class="form-control" size="4" value="<?=$_GET['used_type']?>"/>
                    </div>
                    <div class="form-group">
                        <label>新批次号</label>
                        <input type="text" name="new_use_type" class="form-control" size="4" value=""/>
                        <button type="button" id="BtnUpdate" class="btn btn-default" title="更新批次号">更新</button>
                    </div>
                    <br/> <br/>
                    <div class="form-group">
                        <label>生成时间</label>
                        <input type="text" name="bt" class="form-control" size="12" value="<?=$_GET['bt']?>" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)"/>
                    </div>
                    <div class="form-group">
                        <label>过期时间</label>
                        <input type="text" name="et" class="form-control" size="12" value="<?=$_GET['et']?>" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)"/>
                    </div>
                    <div class="form-group">
                        <label>使用状态</label>
                        <label class="radio-inline"><input type="radio" <?=$_GET['used']==-1 || !isset($_GET['used']) ? 'checked':''?>  value="-1" name="used"/>不限</label>
                        <label class="radio-inline"><input type="radio" <?=$_GET['used']==0 ? 'checked':''?> value="0" name="used"/>未使用</label>
                        <label class="radio-inline"><input type="radio" <?=$_GET['used']==1 ? 'checked':''?> value="1" name="used"/>已使用</label>
                    </div>
                    <button type="button" id="BtnSearch" class="btn btn-primary">查 询</button>
                    <button type="button" id="BtnExport" class="btn btn-info">导 出</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th>物品ID</th>
                            <th>激活码</th>
                            <th>所属游戏</th>
                            <th>生成时间</th>
                            <th>过期时间</th>
                            <th>注册时间限制</th>
                            <th>批次号</th>
                            <th>使用状态</th>
                            <th>使用人账号</th>
                            <th>使用时间</th>
                        </thead>
                        <tbody>
                        <?php if(isset($codes)):?>
                            <?php foreach($codes as $code):?>
                            <tr>
                                <td><?=$code['param']?></td>
                                <td><?=$code['code_id']?></td>
                                <td><?=$code['game_type']?></td>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$code['time_stamp']))?></td>
                                <td><?=$code['time_limit']>0 ? date('Y-m-d',$code['time_limit']) :'----'?></td>
                                <td><?=$code['register_time']>0 ? $rtL[$code['register_type']].$code['register_time'] :'不限'?></td>
                                <td><?=$code['used_type']?></td>
                                <td><?=$code['used']==1 ? '已使用':'未使用'?></td>
                                <td><?=$code['account_id']==0? '----' : $code['account_id']?></td>
                                <td><?=$code['used_time_stamp']==0?'----':$code['used_time_stamp']?></td>
                            </tr>
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
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var frm = $("form#frm");
        $("#BtnExport").on('click', function(){
            frm.attr('target','_target');
            frm.attr('action','s_sncode_export.php');
            frm.submit();
        });
        $("#BtnSearch").on('click', function(){
            frm.attr('target','_self');
            frm.attr('action','#');
            frm.submit();
        });
        $("#BtnUpdate").on('click', function(){
            var old_use_type = $("input[name=used_type]").val(),
                new_use_type = $("input[name=new_use_type]").val();
            if (!old_use_type || !new_use_type) {
                alert('批次号不能为空');
                return false;
            }
            if (!confirm('您确定要更新批次号吗？\n该操作不可逆！！！')) {
                return false;
            }
            $.post('ajax/generate_sn_code.php', {
                action:'UPDATE_USED_TYPE',
                old_used_type:old_use_type,
                new_used_type:new_use_type}, function(json){
                if (json.status=='ok') {
                    alert('更新成功，刷新页面后查看效果');
                } else {
                    alert('更新失败了');
                }
            }, 'json');

        });
    });
</script>
<?php include 'footer.php';?>
