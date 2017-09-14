<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 公告列表
 */
include 'header.php';
$salt   = 'u591212';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form class="form-inline" role="form">
                    <input name="token" id="token" type="hidden" value="<?=md5(md5($salt.'List'))?>"/>
                    <div class="form-group">
                        <label>开始时间</label>
                        <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)">
                        <label>结束时间</label>
                        <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)">
                    </div>

                    <div class="form-group">
                        <label>区服</label>
                        <?php echo htmlSelect($serversList, 'serverid', $serverid);?>
                        <span class="alert-danger" style="padding: 5px 10px;">请选择具体区服后查找</span>
                    </div>
                    <div class="form-group">
                        <label>渠道</label>
                        <?php echo htmlMulSelect($fenbaos, 'fenbaoids[]', $fenbaoids, array('id'=>'fenbaoid','class'=>'mul'));?>
                    </div>
                    <button type="button" id="BtnNoticeList" class="btn btn-primary">查 询</button>

                    <a href="s_notice_add.php" class="btn btn-warning" style="float:right;"><i class="fa fa-plus"></i>发布新公告</a>
                </form>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>编号</th>
                                <th>公告内容</th>
                                <th>开始时间</th>
                                <th>发送间隔（秒）</th>
                                <th>结束时间</th>
                                <th>发送次数</th>
                                <th>发送范围</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="NoticeList"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="ListTpl" type="text/template">
    <tr id="nt_{id}">
        <td>{id}</td>
        <td>{message}</td>
        <td>{time_begin}</td>
        <td>{time_dis}</td>
        <td>{time_end}</td>
        <td>{send_times}</td>
        <td>
            <p>注册时间：{createtime_min}至{createtime_max}</p>
            <p>玩家等级：{level_min}至{level_max}</p>
            <p>充值金额：{rmb_min}至{rmb_max}</p>
            <p>渠道：{fenbao}</p>
        </td>
        <td>
            <a href="s_notice_edit.php?serverid=<?=$_GET['serverid']?>&id={id}">修改</a>
            <a class="noticeRm" href="javascript:;" data-server="<?=$_GET['serverid']?>" data-id="{id}">删除</a>
        </td>
    </tr>
</script>
<script>
    function jsonCall(res) {
        if (res.status=='fail') {
            alert('发生错误！'+res.msg);
        } else if (res.status=='ok' ) {
            if (res.total>0) {
                var htmlList = '',
                    tpl      = $("#ListTpl").html();
                $.each(res['list'], function(i){
                    htmlList += sub(tpl, res['list'][i]);
                });
                $("#NoticeList").html(htmlList);
            } else {
                alert("No data exists!");
            }
        }
        else {
            alert(res);
        }
    }
    $("#BtnNoticeList").on('click', function(){
        var param = {
            jsonCall:'jsonCall',
            action : 'List',
            token  : $("#token").val(),
            bt: $("input[name='bt']").val(),
            et: $("input[name='et']").val(),
            serverid : $("select[name='serverid']").find('option:selected').val(),
            fenbaoids : $("select#fenbaoid").val()
        };
        $.getJSON('http://14.17.105.217/interface/notice_212/callback.php?callback=?', param);
//            .done(function(res){
//                if (res.status=='fail') {
//                    alert('发生错误！');
//                } else if (res.status=='ok' && res.total>0) {
//                    var htmlList = '',
//                        tpl      = $("#ListTpl").html();
//                    $.each(res['list'], function(i){
//                        htmlList += sub(tpl, res['list'][i]);
//                    });
//                    $("#htmlList").html(htmlList);
//                }
//                else {
//                    alert(res);
//                }
//            })
//            .fail(function(){
//                alert('Request Fail!');
//            });
    });
</script>
<?php include 'footer.php';?>
