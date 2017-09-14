<div class="wepay" style="text-align: center">
    <ul class="lt" style="width: 720px; margin-top: 30px;">
        <li>
            <p>微信扫码支付</p>
            <p style="margin-top: 15px;">应付金额：<span style="color: #f60;"><?=$totalFee;?></span>元</p>
        </li>
        <li>
            <img alt="模式二扫码支付" src="http://hejin.u591.com/interface/wepay/phpqrcode/qrcode.php?data=<?=urlencode($url);?>" style="width:300px;height:300px;"/>
            <p>请使用微信扫一扫，扫描二维码支付</p>
        </li>
    </ul>
</div>
<style>
    .wepay{
        background-color: #FFF;
        width:740px;
        height:585px;
        border:1px solid #A8A8A8;
        margin:0 auto;
        position:relative;
    }
</style>
