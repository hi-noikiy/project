<?php //if($this->beginCache($id,array('duration'=>3600,'varyByParam'=>array('page')))) { ?>
<h4 class="data_header"style="margin-top: 20px;">
    <span><?=$title; ?></span>
</h4>
<div id="handbook" class="wrap clearfix">
    <div class="ptop">
        <h2>请填写您的账号信息</h2>
    </div>
    <div class="clear"></div>
    <form  name="RegForm" method="post" action="" target="_blank" onSubmit="return InputCheck(this)">
    <ul class="scroll_wrapper clearfix">
        <div  class="gift-way">
            <select name="serverId" required>
                <option value="">==选择服务器==</option>
                <?php
                foreach ($gameServer as $k => $v) {
                    echo "<option value='{$v['server_id']}'>{$v['server_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div  class="gift-way">
            <span>角色名字:</span>
            <input name="playerName" type="text" required>
            <span id="playerVerify" style="display: inline-block; margin-left: 10px; font-size: 12px; cursor: pointer">校验角色</span>
        </div>

        <p style="margin: 60px 0 0 10px;">选择充值金额</p>
        <div class="controls" style="margin-top: 10px;">
            <label data-money="6" data-value="60" class="select">
                <a class="radio-box" href="javascript:void(0);">
                    6元（60钻石）
                    <i class="icon-check"></i>
                </a>
            </label>
            <label class="" data-money="30" data-value="300">
                <a class="radio-box" href="javascript:void(0);">
                    30元（300钻石）
                    <i class="icon-check"></i>
                </a>
            </label>
            <label class="selected" data-money="98" data-value="980">
                <a class="radio-box" href="javascript:void(0);">
                    98元（980钻石）
                    <i class="icon-check"></i>
                </a>
            </label>
            <label class="selected" data-money="198" data-value="1980">
                <a class="radio-box" href="javascript:void(0);">
                    198元（1980钻石）
                    <i class="icon-check"></i>
                </a>
            </label>
            <label class="selected" data-money="328" data-value="3280">
                <a class="radio-box" href="javascript:void(0);">
                    328元（3280钻石）
                    <i class="icon-check"></i>
                </a>
            </label>
        </div>
        <div class="mod-line">
            <p style="margin-bottom: 15px;">选择支付方式</p>
            <ul class="mod-list mobile-list">
                <div class="list-wrap">
                    <input type="radio" name="pay" checked value="ali">
                    <img src="<?=ASSETS_URL?>/images/alipay.png" />
                </div>

                <div class="list-wrap">
                    <input type="radio" name="pay" value="wx">
                    <img src="<?=ASSETS_URL?>/images/weixin.png" />
                </div>
                <!--
                <div class="list-wrap">
                    <img src="<?=ASSETS_URL?>/images/unionpay.png" />
                </div>

                <div class="list-wrap">
                    <img src="<?=ASSETS_URL?>/images/bank.png" />
                </div>-->
            </ul>
        </div>
        <div class="c-btn">
            <input id="submit_btn" disabled="disabled" type="image" src="<?=ASSETS_URL?>/images/submit.png" onClick="document.formName.submit()"style="margin-top: 30px; margin-left: 260px;" />
        </div>
    </ul>
        <input type="hidden" name="accountId" value="" required>
    <input type="hidden" name="money" value="6" required>
    </form>
</div>
<link href="<?=ASSETS_URL; ?>css/pay.css" rel="stylesheet" type="text/css">
<script charset="utf-8" src="<?=ASSETS_URL; ?>lib/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        var $playerVerify = $("#playerVerify");
        $playerVerify.click(function () {
            var serverId = $("select[name='serverId']").val();
            var playerName = $("input[name='playerName']").val();
            if(!serverId){
                alert('请选择服务器。');
                return false;
            }
            if(!playerName){
                alert('请输入角色名');
                return false;
            }
            $.ajax({
                type: "POST",
                url: "<?=$this->createUrl('pay/checkPlayer')?>",
                data: "serverId=" + serverId+"&playerName="+playerName,
                dataType: "json",
                success: function(data){
                    if(data.status == 0){
                        alert('角色校验成功。');
                        $("input[name='accountId']").val(data.data.accountId);
                        $("#submit_btn").removeAttr('disabled');
                    } else {
                        alert('角色不存在。');
                    }
                }
            });
        });
        $(".controls label").click(function () {
            var $money = $("input[name='money']");
            $(".controls label").removeClass("select");
            $(this).addClass("select");
            $money.val($(this).attr("data-money"));
        });

    });
    function InputCheck(RegForm) {
        if (RegForm.serverId.value == ""){
            alert("请选择服务器");
            RegForm.serverId.focus();
            return (false);
        }
        if (RegForm.playerName.value == ""){
            alert("角色名不可为空!");
            RegForm.username.focus();
            return (false);
        }
    }
</script>
<?php //$this->endCache(); } ?>