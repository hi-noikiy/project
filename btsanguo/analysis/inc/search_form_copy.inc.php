<?php
    $action = isset($action) ? $action : $_SERVER['PHP_SELF'];
?>
<form class="form-inline" role="form" action="<?=$action;?>">
    <?php if($userInfoFilter):?>
    <p>
        <div class="form-group">
        <label>角色名</label>
        <input name="userName" type="text" class="form-control" size="12" value="<?=$_GET['userName']?>" >
    </div>
    <div class="form-group">
        <label>账号ID</label>
        <input name="accountid" type="text" class="form-control" size="12" value="<?=$_GET['accountid']?>" >
    </div>
        </p>
    <?php endif;?>
    <?php if(!isset($noTimeFilter)):?>
    <div class="form-group">
        <label><?=$prefixTime?>时间</label>
        <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
       <?php if(!isset($noEndTimeFilter)):?>
        --
        <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd<?=$time_format?>',0,0)">
       <?php endif;?>
    </div>
    <?php endif;?>
    <?php if(!isset($noServerFilter) || !$noServerFilter):?>
<!--    --><?php //if($_GET['test']):?>
        <div class="form-group">
            <label>区服ID</label>
            <input type="number" name="min_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['min_sid']?>" class="form-control" size="12"/>至
            <input type="number" name="max_sid" placeholder="请输入区服ID（数字）" value="<?=$_GET['max_sid']?>" class="form-control" size="12"/>
<!--            --><?php //echo htmlSelect($servers_grp, 'server_gid', $s_gid);?>
<!--            --><?php //echo htmlSelect(isset($servers_list[$s_gid]) ? $servers_list[$s_gid] : array(), 'server_id', $sid);?>
        </div>
    <?php endif;?>
    <button type="submit" class="btn btn-primary">查 询</button>
    <?php if($exportFlag):?>
    <button type="button" id="export2excel" class="btn btn-primary">导 出</button>
    <?php endif;?>
    <?php if(!empty($warnMsg)):?>
        <span class="alert-danger" style="padding: 5px 10px;"><?=$warnMsg?></span>
    <?php endif;?>
</form>