<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>赢越七亿，问鼎天下</title>
    <!--<link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"/>-->
    <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css"/>
    <!--<script src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script>-->
    <style>
        html {
            background: url(<?=base_url()?>public/ma/img/form_bg.jpg?111) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        body{
            padding-top: 4em;
            /*background: url(*/<?//=base_url()?>/*public/ma/img/form_bg.jpg) no-repeat;*/
            /*background-size: auto 100%;*/
        }
        .page_title {
            text-align: center;
            font-size: 28px;
            color: #ff0000;
            font-weight: bold;
            margin: 0 15%;
            text-shadow: 2px 2px 8px #ff920b;
        }
        .weui_cells {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .weui_label{
            font-weight: bold;
            width: 125px;
        }
    </style>
</head>
<body>
<!--<div id="div1"><img src="--><?//=base_url()?><!--public/ma/img/form_bg.jpg" /></div>-->
<div class="hd">
    <h1 class="page_title">赢越七亿，问鼎天下</h1>
</div>
<div class="bd">
    <form id="submit_form">
        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <div class="weui_cells weui_cells_form">
            <!--<div class="weui_cell weui_cell_select weui_select_after">-->
            <!--    <div class="weui_cell_hd">-->
            <!--        <label for="" class="weui_label">站区</label>-->
            <!--    </div>-->
            <!--    <div class="weui_cell_bd weui_cell_primary">-->
            <!--        <select class="weui_select" name="zone">-->
            <!--            <option value="1">第一战区</option>-->
            <!--            <option value="2">第二战区</option>-->
            <!--            <option value="3">第三战区</option>-->
            <!--        </select>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="weui_cell">-->
            <!--    <div class="weui_cell_hd"><label class="weui_label">营业部代码</label></div>-->
            <!--    <div class="weui_cell_bd weui_cell_primary">-->
            <!--        <input class="weui_input" name="com_code" type="text"  placeholder="请输入营业部代码">-->
            <!--    </div>-->
            <!--</div>-->
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">业务代码</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="code" type="text" placeholder="请输入业务代码">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="name" type="text"  placeholder="请输入姓名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">类型</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select id="select_type" class="weui_select" name="type">
                        <option value="1">个人</option>
                        <option value="2">团队</option>
                    </select>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">认购件数</label></div>
                <div id="wrap_content" class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select" name="num">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
            <!--<div class="weui_cell">-->
            <!--    <div class="weui_cell_hd"><label class="weui_label">保费目标</label></div>-->
            <!--    <div class="weui_cell_bd weui_cell_primary">-->
            <!--        <input class="weui_input" name="money" type="number"  placeholder="单位：万">-->
            <!--    </div>-->
            <!--</div>-->
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">保费目标(万元)</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="money" type="number"  placeholder="请输入保费目标">
                </div>
            </div>
            <!--<div class="weui_cell">-->
            <!--    <div class="weui_cell_hd"><label class="weui_label">团队认购件数</label></div>-->
            <!--    <div class="weui_cell_bd weui_cell_primary">-->
            <!--        <input class="weui_input" name="group_num" type="number" >-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="weui_cell">-->
            <!--    <div class="weui_cell_hd"><label class="weui_label">团队保费目标(万元)</label></div>-->
            <!--    <div class="weui_cell_bd weui_cell_primary">-->
            <!--        <input class="weui_input" name="group_money" type="number" >-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="weui_cell">-->
            <!--    <a class="weui_btn weui_btn_primary" href="javascript:" id="submitBtn">提交</a>-->
            <!--</div>-->
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_primary" href="javascript:" id="submitBtn">提交</a>
        </div>
    </form>
    <div id="loadingToast" class="weui_loading_toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">数据加载中</p>
        </div>
    </div>
</div>
<!--<script src="http://cdn.staticfile.org/zepto/1.1.6/zepto.min.js"></script>-->
<script src="<?=base_url()?>public/ma/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#select_type").on('change', function(){
            var t = $(this).find('option:selected').val();
            var content = '';
            if (t==1) {
                content =  '<select class="weui_select" name="num">' +
                    ' <option value="1">1</option> <option value="2">2</option> ' +
                    '<option value="3">3</option> <option value="4">4</option> ' +
                    '<option value="5">5</option> <option value="6">6</option> ' +
                    '<option value="7">7</option> <option value="8">8</option> ' +
                    '<option value="9">9</option> <option value="10">10</option> ' +
                    '</select>';
            } else {
                content = '<input class="weui_input" name="num" type="number" placeholder="请输入认购件数">';
            }
            $("#wrap_content").html(content);
        });
        $("#submitBtn").on('click', function(){
            var data = $("#submit_form").serialize();
            //$('#loadingToast').show();return;
            $.ajax({
                type : "post",
                async : false, //同步执行
                url : '<?php echo site_url('Dev/index');?>',
                data : data,
                dataType : "json", //返回数据形式为json
                before: function(){
                    $("#submitBtn").attr('disabled', 'disabled');
                    $('#loadingToast').show();
                },
                success : function(result) {
                    alert(result.msg);
                    $('#loadingToast').hide();
                    $("#submitBtn").removeAttr('disabled');
                    if (result.code==200) {
                        location.reload();
                    }
                },
                error : function(errorMsg) {
                    alert("请求数据失败");
                }
            });
        });
    });
</script>
</body>
</html>