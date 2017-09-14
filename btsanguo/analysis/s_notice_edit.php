<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 添加公告
 */
include 'header.php';
$serverId = intval($_GET['serverid']);
$noticeId = $_GET['id'];
$noticeObject = new Notice();
$detail = $noticeObject->Show($serverId, $noticeId, $db_sum);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_notice.php" class="btn btn-primary">返回公告列表</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post" class="form-horizontal">
                    <input value="Notice" name="actionObject" type="hidden"/>
                    <input value="Update" name="action" type="hidden"/>
                    <input name="serverId" value="<?=$serverId?>" type="hidden"/>
                    <input name="noticeId" value="<?=$noticeId?>" type="hidden"/>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">开始时间</label>
                        <div class="col-sm-2">
                            <input name="timeBegin" type="text" class="form-control" value="<?=$noticeObject->formatDateTime($detail['time_begin'],'Y-m-d H:i')?>" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">结束时间</label>
                        <div class="col-sm-2">
                            <input name="timeEnd" type="text" class="form-control" value="<?=$noticeObject->formatDateTime($detail['time_end'],'Y-m-d H:i')?>" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label" for="timeInterval">间隔时间（秒）</label>
                        <div class="col-sm-2">
                            <input type="number" name="timeInterval" value="<?=$detail['time_dis']?>" id="timeInterval" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">注册时间</label>
                        <div class="col-sm-2">
                            <input type="text" name="createTimeMin" id="createTimeMin" value="<?=$noticeObject->formatDateTime($detail['createtime_min'],'Y-m-d H:i')?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                        <input type="text" name="createTimeMax" id="createTimeMax"  value="<?=$noticeObject->formatDateTime($detail['createtime_max'],'Y-m-d H:i')?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm',0,0)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">玩家等级</label>
                        <div class="col-sm-2">
                            <input type="number" name="lvlMin" id="lvlMin" class="form-control" value="<?=$detail['level_min']?>">
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                            <input type="number" name="lvlMax" id="lvlMax" class="form-control" value="<?=$detail['level_max']?>">
                        </div>
                        <span class="alert alert-info">不填写默认全服所有用户</span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">充值范围</label>
                        <div class="col-sm-2">
                            <input type="number" name="rmbMin" id="rmbMin" class="form-control" value="<?=$detail['rmb_min']?>"/>
                        </div>
                        <label style="width: 0.1%;" class="col-sm-1 control-label">至</label>
                        <div class="col-sm-2">
                            <input type="number" name="rmbMax" id="rmbMax" class="form-control" value="<?=$detail['rmb_max']?>"/>
                        </div>
                        <span class="alert alert-info">不填写默认全服所有用户</span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">公告内容</label>
                        <div class="col-sm-4">
                            <textarea class="form-control" name="msg" rows="3"><?=$detail['message']?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="btnSave" type="button" class="btn btn-primary btn-lg">更 新</button>
                            <button type="button" class="btn btn-primary btn-lg">取 消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
