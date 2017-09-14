<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 * 添加区服
 */
include 'header.php';
if ($_GET['sid'] && is_numeric($_GET['sid'])) {
    $id     = intval($_GET['sid']);
    $sys    = new System($db_sum);
    $server = array_shift($sys->SeverList($id));
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_server.php" class="btn btn-primary">返回</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <input value="ServerAdd" name="action" type="hidden"/>
                    <input name="sid" type="hidden" value="<?=$id?>"/>
                    <div class="form-group">
                        <label class="control-label" for="servername">区服名称</label>
                        <input type="text" name="servername" value="<?=$server['servername']?>" placeholder="例如：【官方区服】1服 君临天下" id="servername" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="serverid">区服ID</label>
                        <input type="number" placeholder="必须是数字" name="serverid" value="<?=$server['serverid']?>" id="serverid" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="groupid">区服组</label>
                        <select class="form-control" name="groupid" id="groupid">
                            <?php foreach($servers_grp as $sid=>$name):?>
                                <option value="<?=$sid?>" <?=$server['groupid']==$sid? 'selected':''?> ><?=$name?></option>
                            <?php endforeach;?>
<!--                            <option value="2" --><?//=$server['groupid']==2? 'selected':''?><!-->渠道专服</option>-->
<!--                            <option value="3" --><?//=$server['groupid']==3? 'selected':''?><!-->IOS正版专服</option>-->
                        </select>
                    </div>
                    <div class="form-group">
                        <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                        <button type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
