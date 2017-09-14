function sub(str,data) {
    return str
        .replace(/{(.*?)}/igm,function($,$1) {
            return data[$1]?data[$1]:$;
        });
}
jQuery(document).ready(function($){
    $("#nav_toggle").on('click', 'a', function(){
        if ($(this).hasClass('status-open')) {
            $(this).removeClass('status-open').addClass('status-close');
            $(".sidebar-collapse").fadeIn('fast', function(){
                $("#nav_toggle").css({left:250});
                $("#page-wrapper").css({'margin-left':250});
            });
        } else {
            $(this).removeClass('status-close').addClass('status-open');
            $(".sidebar-collapse").fadeOut('fast', function(){
                $("#nav_toggle").css({left:0});
                $("#page-wrapper").css({'margin-left':0});
            });

        }
    });
    var $dt = $('#dataTable');
    if ($dt.length) {
        var dtOptions = {
            paginate:false,
            "bSearchable": false,
            "searching": false//禁止搜索功能
        };
        if ($dt.hasClass('onlineFlag')) {
            dtOptions["columnDefs"] = [
                { "orderable": false, "targets": [1,2] }
            ];
        } else if($dt.hasClass('questFlag')) {
            dtOptions["columnDefs"] = [
                { "orderable": false, "targets": [1] }
            ];

        } else if ($dt.hasClass('rechargeFlag')) {
            dtOptions['paginate'] = true;
            dtOptions['pageLength'] = 40;
        }
        $dt.dataTable(dtOptions);
    }


    $("select.mul").multiselect({
        selectAll: false,
        oneOrMoreSelected: '*',
        noneSelectedText:'---请选择---',
        checkAllText:'全选',
        unCheckAllText:'取消全选',
        height:375
    });
    var ajaxUrl = 'ajax/call.php';
    $("select[name=server_gid]").on('change', function(){
        var sid = $(this).find('option:selected').val();

        $.getJSON(ajaxUrl, {action:'ServerList',sid:sid}, function(json) {
//            console.log(json);
            var options = '<option value="-1">--请选择--</option>';
            for (var i in json) {
                options += "<option value="+i+">"+json[i]+"</option>";
            }
            $("select[name=server_id]").html(options);
        });
    });
    $("#ugrp").on('change', function(){
        var grpid = $(this).find('option:selected').val();
        $.get(ajaxUrl, {action:'GetGrpRights',grpid:grpid})
            .done(function(ret) {
                //{"":"","":[]}
                $("#urights").html(ret);
            });
    });
    //Save Form
    $("#btnSave").on('click', function(){
        var data = $("#frm").serialize(),
            btn = $(this);
        btn.attr('disabled', 'disalbed');
        $.post(ajaxUrl, data, function(res) {
            alert(res.msg);
            if (res.status!='ok') {
                btn.removeAttr('disabled');
            } else {
                location.reload();
            }
        }, 'json');
    });
    $(".noticeRm").on('click', function(){
        if (!confirm('确定要删除吗')) {
            return false;
        }
        var param = {
          action:'Delete',
          actionObject:'Notice',
          serverId: $(this).data('server'),
          noticeId: $(this).data('id')
        };
//        console.log(param);return;
        $.post(ajaxUrl, param, function(res){
            alert(res.msg);
            if (res.status=='ok') {
                $("#nt_"+param.noticeId).remove();
            }
        }, 'json');
    });
    $("input[name='fsort']").on('change', function(){
         var fsort = $(this).val(),
             param = {action:'FileSort',fid: $(this).data('fid'), fsort: fsort};
        if(isNaN(fsort)) {
            alert('排序值必须是数字！');
        }
        if ($(this).hasClass('fenbao')) {
            param.action = 'FenbaoSort';
        }
        $.post(ajaxUrl, param)
            .done(function(ret) {
                if(ret=='ok') {
                    $("#msg").html('设置成功').show();
                    setTimeout(function(){
                        $("#msg").hide();
                    }, 2000);
                }
            });
    });
    //添加分组
//    $("#btnSaveGroup").on('click', function(){
//        var data = $("#frm").serialize(),
//            btn = $(this);
//        btn.attr('disabled', 'disalbed');
//        $.post(ajaxUrl, data, function(res) {
//            alert(res.msg);
//            if (res.status!='ok') {
//                btn.removeAttr('disabled');
//            } else {
//                location.reload();
//            }
//        });
//    });
    $("#btnCancel").on('click', function(){
        history.go(-1);
    });

    //保存公告


    //充值日志-查询玩家信息
    if($("#PayLogList").length) {
        $("#PayLogList").on('click','a.showPlayerName', function(){
            var param = {
                    actionObject: 'PlayerInfo',
                    serverid : $(this).data('server'),
                    accountid: $(this).data('account')
                },
                $txtInput = $(this).siblings('input');
            $.getJSON(ajaxUrl,param, function(res){
                if(res.status=='ok') {
                    $txtInput.val(res.name);
                } else {
                    alert(res.msg);
                }
            });
        });
    }
});