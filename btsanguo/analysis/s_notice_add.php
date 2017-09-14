<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 添加公告
 */
include 'header.php';
?>
<style>
    .alert{padding:4px;}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_notice.php" class="btn btn-primary">返公告列表</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post" class="form-horizontal">
                    <input value="Notice" name="actionObject" type="hidden"/>
                    <input value="Add" name="action" type="hidden"/>
                    <div class="form-group has-success">
                        <label class="col-sm-2 control-label">区服（必选）</label>
                        <div class="col-sm-3">
                            <?php echo htmlMulSelect($servers, 'serverids[]', $serverids, array('id'=>'serverid','class'=>'mul'), true, $grps);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">渠道（可选）</label>
                        <div class="col-sm-4">
                            <?php echo htmlMulSelect($fenbaos, 'fenbaoids[]', $fenbaoids, array('id'=>'fenbaoid','class'=>'mul'));?>
                            <span class="alert alert-info">不选择将默认发送到所有渠道</span>
                        </div>
                    </div>
                    <div class="form-group has-success">
                        <label class="col-sm-2 control-label">开始时间（必填）</label>
                        <div class="col-sm-3">
                            <input name="timeBegin" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', strtotime('+30 mins'))?>" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                    </div>
                    <div class="form-group has-success">
                        <label class="col-sm-2 control-label">结束时间（必填）</label>
                        <div class="col-sm-3">
                            <input name="timeEnd" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', strtotime('+1 days'))?>" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="timeInterval">间隔时间（单位“秒”，必填）</label>
                        <div class="col-sm-3">
                            <input type="number" name="timeInterval" id="timeInterval" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">注册时间（可选）</label>
                        <div class="col-sm-2">
                            <input type="text" name="createTimeMin" id="createTimeMin" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                        <input type="text" name="createTimeMax" id="createTimeMax" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                        <span class="alert alert-info">不填写默认全服所有用户</span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">玩家等级（可选）</label>
                        <div class="col-sm-2">
                            <input type="number" name="lvlMin" id="lvlMin" class="form-control">
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                            <input type="number" name="lvlMax" id="lvlMax" class="form-control">
                        </div>
                        <span class="alert alert-info">不填写默认全服所有用户</span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">充值范围（可选）</label>
                        <div class="col-sm-2">
                            <input type="number" name="rmbMin" id="rmbMin" class="form-control">
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                            <input type="number" name="rmbMax" id="rmbMax" class="form-control">
                        </div>
                        <span class="alert alert-info">不填写默认全服所有用户</span>
                    </div>
                    <div class="form-group has-success">
                        <label class="col-sm-2 control-label">公告内容</label>
                        <div class="col-sm-4">
                            <textarea class="form-control" name="msg" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                            <button type="button" class="btn btn-primary btn-lg">取 消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
