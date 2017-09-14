<?php if ($this->beginCache($id, array(
    'duration' => 3600,
    'varyByParam' => array('page'),
    'dependency'=>array(
            'class'=>'CDbCacheDependency',
            'sql'=>'select count(id) from {{gift}} where status=0',
    ),
    ))) { ?>
<h4 class="data_header" style="margin-top: 20px;">
    <span>口袋妖怪VS礼包领取</span>
</h4>
<?php if ($giftList) { ?>
<div class="tab-info-box">
    <div class="tab-warp">
        <div class="template-box"></div>
        <ul class="gift-box">
            <?php foreach ($giftList as $key => $gift) { ?>
            <li>
                <div style="float:left;">
                    <img src="<?=ASSETS_URL ?>images/giftfirst.png" alt="领取礼包" style="height: 92px;">
                </div>
                <div class="gift-way" style="margin-left: 115px; height: 70px;">
                    <strong><?=$gift->name; ?></strong>
                    <div class="info">
                        <span>礼包内容：<?=$gift->desc;?></span>
                        <a class="giftCode" data-id="<?=$gift->id;?>" data-title="<?=$gift->name; ?>" style="float:right;"><img src="<?=ASSETS_URL ?>images/present.png" alt="领取礼包"></a>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>
<div class="btn_page_wrapper">
    <?php
    $this->widget('CLinkPager', array(
            'header' => '',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '末页',
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'pages' => $pages,
            'maxButtonCount' => 5
        )
    );
    ?>
</div>

<div class="qiandao-layer qiandao-history-layer">
<div class="qiandao-layer-con qiandao-radius">
    <a href="javascript:;" class="close-qiandao-layer qiandao-sprits" onclick="refreshParent()">关闭</a>
    <form method="post" action="get" id="giftform">
    <ul class="qiandao-history-inf clear">
        <div class="dialog-bd">
            <div data-ui-role="content">
                <div class="tt">
                    恭喜您获得了
                    <span class="red" id="giftTitle"></span>
                </div>
                <p class="ti">
                    请登录手机游戏后，<span class="red">兑换 </span>该礼包码。
                </p>

                <p class="ti">
                    如有问题
                    <a class="blue" href="javascript:;">可以联系客服：0591-87678008</a>
                </p>

                <div class="form-item" style="margin-left: 65px;">
                    <label class="form-label">礼包码：</label>

                    <div class="form-input">
                        <input name="code" id="code" value="" readonly="readonly" type="text">
                    </div>
                    <input class="form-btn" type="button" value="复制" onclick="copyinput()"/>
                </div>
            </div>
        </div>
    </ul>
    </form>
</div>
</div>
<div class="qiandao-layer-bg"></div>
<link href="<?=ASSETS_URL; ?>css/gift.css" rel="stylesheet" type="text/css">
<script charset="utf-8" src="<?=ASSETS_URL; ?>lib/jquery-1.10.2.min.js"></script>
<script>
    function openLayer(a, Fun) {
        $('.' + a).fadeIn(Fun)

    } //打开弹窗
    var closeLayer = function () {
        $("body").on("click", ".close-qiandao-layer", function () {
            $(this).parents(".qiandao-layer").fadeOut()
        })
    }() //关闭弹窗

    $(".giftCode").on("click", function () {
        var codeType = $(this).attr("data-id");
        var tit = $(this).attr("data-title");
        $.ajax({
            type: "POST",
            url: "<?=$this->createUrl('gift/check')?>",
            data: "codeType=" + codeType,
            success: function(data){
                var obj = eval('(' + data + ')');
                if(obj.status == 1){
                    if(obj.data == false){
                        alert('改礼包已经用完。');
                        return false;
                    }
                    openLayer("qiandao-history-layer", myFun);
                    function myFun() {
                        $("#giftTitle").text(tit);
                        $("#code").val(obj.data.code_id);
                        // console.log(1)
                    } //打开弹窗返回函数haox
                }else if( data =='error') {
                    window.location.href="login"; //跳转
                }
            }

        });
       /* openLayer("qiandao-history-layer", myFun);

        function myFun() {
           // console.log(1)

        } //打开弹窗返回函数haox
*/
    })
    function refreshParent() {
        $(".qiandao-layer").hide();
    }
    //注意
    function copyinput() {
        var input = document.getElementById("code");//input的ID值
        input.select(); //选择对象
        document.execCommand("Copy"); //执行浏览器复制命令
        var code = $("#code").val();
    }
</script>
<?php $this->endCache();} ?>