<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午3:49
 * 激活码生成
 */
include 'header.php';
?>
<style>
    a.handler{
        display: block;
        padding:4px;
        font-size: 16px;
        font-weight: bold;
        background-color: #006600;
        color:#FFF;
        text-decoration: none;
        text-align: center;
    }
    a.handler:hover{
        background-color: #005f8d;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_notice.php" class="btn btn-primary">返回</a>
                <span id="msgTxt" class="alert"></span>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <div class="form-group">
                        <label class="control-label">游戏</label>
                        <select class="form-control" name='game_id'>
                            <option value="5">三国将魂录</option>
                        </select>
                    </div>
                    <input value="" name="actionObject" type="hidden"/>
                    <input value="" name="action" type="hidden"/>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 100px;">生成数量</th>
                            <th style="width: 200px;">物品名称</th>
                            <th style="width: 150px;">物品ID</th>
                            <th style="width: 140px;">过期时间</th>
                            <th>注册时间</th>
                            <th style="width: 60px"></th>
                        </tr>
                        </thead>
                        <tbody id="list_body">
                        <tr>
                            <td>
                                <input type="number"
                                       placeholder="不能超过10000"
                                       value="1000"
                                       name="codes[0][sn_nums]"
                                       id="sn_nums"
                                       class="form-control">
                            </td>
                            <td>
                                <input type="text"
                                       name="codes[0][item_name]"
                                       placeholder="与物品ID对应的名称"
                                       class="form-control">
                            </td>
                            <td>
                                <input type="number" name="codes[0][param]" id="param" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="codes[0][time_limit]" placeholder="yyyy-mm-dd" id="time_limit" class="form-control date">
                            </td>
                            <td>
                                <p><label class="radio-inline"><input type="radio" checked value="0" class="register_type" name="codes[0][register_type]"/>不限</label></p>
                                <p>
                                    <label class="radio-inline"><input type="radio" value="1" class="register_type" name="codes[0][register_type]"/>不能早于</label>
                                    <input type="text" placeholder="yyyy-mm-dd" disabled name="codes[0][register_time]" id="register_time_1" class="date"/>
                                </p>
                                <p>
                                    <label class="radio-inline"><input type="radio" value="2" class="register_type" name="codes[0][register_type]"/>不能晚于</label>
                                    <input type="text" disabled placeholder="yyyy-mm-dd"  name="codes[0][register_time]" id="register_time_2" class="date"/>
                                </p>
                            </td>
                            <td>
                                <a href="javascript:;" class="handler btn-add">+</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <button id="btnSnCode" type="button" class="btn btn-primary btn-lg">生 成</button>
                        <button type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="list_tpl">
    <tr id="list_{k}">
        <td>
            <input type="number"
                   placeholder="不能超过10000"
                   value="1000"
                   name="codes[{k}][sn_nums]"
                   id="sn_nums"
                   class="form-control">
        </td>
        <td>
            <input type="text"
                   name="codes[{k}][item_name]"
                   placeholder="与物品ID对应的名称"
                   class="form-control">
        </td>
        <td>
            <input type="number" name="codes[{k}][param]" id="param" class="form-control">
        </td>
        <td>
            <input type="date" name="codes[{k}][time_limit]" placeholder="yyyy-mm-dd" id="time_limit" class="form-control">
        </td>
        <td>
            <p>
                <label class="radio-inline">
                    <input type="radio" checked value="0" name="codes[{k}][register_type]"/>不限
                </label>
            </p>
            <p>
                <label class="radio-inline">
                    <input type="radio" value="1" class="register_type" name="codes[{k}][register_type]"/>不能早于
                </label>
                <input type="date" placeholder="yyyy-mm-dd" disabled name="codes[{k}][register_time]" id="register_time_1"/>
            </p>
            <p>
                <label class="radio-inline">
                    <input type="radio" value="2" class="register_type" name="codes[{k}][register_type]"/>不能晚于</label>
                <input type="date" disabled placeholder="yyyy-mm-dd"  name="codes[{k}][register_time]" id="register_time_2"/>
            </p>
        </td>
        <td>
            <a href="javascript:;" class="handler rm" data-rid="{k}" >—</a>
        </td>
    </tr>
</script>
<script src="public/js/jquery-ui-1.10.4.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#list_body").on('focus', 'input.date', function(){
            $(this).datepicker({ dateFormat: "yy-mm-dd" });
        });
//        $( ".date" ).datepicker({ dateFormat: "yy-mm-dd" });
//        $( "#et" ).datepicker({ dateFormat: "yy-mm-dd" });

        var inc = -1,
            key_index = $("input#key_index").length ? parseInt($("input#key_index").val()) :2;
        $("#list_body").on('click','a.rm',function(){
            //if(index==0) return;
            var round_id = $(this).attr('data-rid');
            console.log(round_id);
            if(inc==0){
                return;
            }
            $("#list_"+round_id).remove();
            key_index -= 1;

        });
        $(".btn-add").on('click',function(){
            if(key_index>20) {
                alert('一次性添加不能超出20个场馆！');
                return;
            }
            var content = $("#list_tpl").html();
            var newcontent = content.replace(/{k}/g,inc).replace(/{inc}/g,key_index);
            $("#list_body").append(newcontent);
            inc -= 1;
            key_index += 1;
        });
        $("#list_body").on('click', 'input.register_type', function(){
            var val = $(this).val();
            console.log(val);
            $(this).parents('td').find('input.register_time').attr('disabled','disabled').val('');
            if (val>0) {
                $(this).parent().next().removeAttr('disabled');
            }
        });
        $("input[name='register_type']").on('click', function(){
            var val = $(this).val();
            $("input[name='register_time']").attr('disabled','disabled').val('');
            if (val>0) {
                $("#register_time_"+val).removeAttr('disabled');
            }
        });
        $("#btnSnCode").on('click', function(){
            var $that = $(this);
            if($("input[name='sn_nums']").val()>50000) {
                alert('激活码个数不能超出50000！');
                return false;
            }
            $.ajax({
                url:'ajax/generate_sn_code.php',
                data: $("#frm").serialize(),
                type: 'post',
                dataType: 'json',
                beforeSend: function(){
                    $that.attr('disabled', 'disabled').text('生成中...');
                    $("#msgTxt").html('数据提交中，生成激活码的过程可能需要较长时间。<img src="public/css/images/loding.gif"/>');
                },
                success: function(res){
                    if(res.status=='ok') {
                        $("#msgTxt").addClass('alert-success');
                    } else {
                        $("#msgTxt").addClass('alert-warning');
                    }
                    $("#msgTxt").html(res.msg+'批次号<span class="ui-state-highlight">'+res.stype+'</span>');
                    $that.removeAttr('disabled').text('生成');
                }
            });
        });
    });
</script>
<?php include 'footer.php';?>
