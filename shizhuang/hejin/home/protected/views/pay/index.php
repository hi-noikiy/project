<!DOCTYPE html>
<!-- saved from url=(0034)http://recharge.iwantang.com/order -->
<html style="font-size: 96px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1">
<title>衣范儿-充值</title>
<!--<base href="http://recharge.iwantang.com">--><base href=".">
<link href="webpay/recharge/wap501b.css" rel="stylesheet">
<script src="webpay/recharge/utility.js"></script>
<script src="webpay/recharge/wap.js"></script>
<script src="webpay/recharge/layer.js"></script>
<style type="text/css">
	.pagetop{
		height: 0.737rem!important;
		border-bottom: 1px solid #252525!important;
	}
	.pagetop dt img{
		left: 0.213rem!important;
	}
	.pagetop dt h1{
		left: 0.84rem!important;
	}
	.pagetitle h2 strong{
		font-size: 0.1466rem!important;
		font-weight: bold!important;
		color: #CCCCCC!important;
	}
	.formline li{
		font-size: 0.1466rem!important;
		color: #a2873d!important;
	}
	.selected{
		margin-left: 0.213rem!important;
		height: 0.573!important;
	}
	.payway{
		padding-left: 0.46rem!important;
	}
	.selectlist .selected dt{
		font-size: 0.1466rem!important;
		color: #f5ff00!important;
	}
	
	.selectlist .select dt{
		font-size: 0.1466rem!important;
		color: #CCCCCC!important;
	}
	.select,.selected{
		margin-left: 0.213rem!important;
	}
	.selectlist .selected dt span{
		font-size: 0.1466rem!important;
		color: #f5ff00!important;
	}
	.selectlist .select  dt span{
		font-size: 0.1466rem!important;
		color: #CCCCCC!important;
	}
	
	.selectlist dd{
		margin-right: 0.14rem!important;
	}
	form {
		position: relative!important;
		background-color: #424242!important;
		height: 0.7rem!important;
	}
	form .formline{
		margin: 0px!important;
	}
	form .formline li{
		margin: 0px!important;
	}
	.formline .inputbutton input{
		position: absolute;
		top: 15px!important;
		background-image: none!important;
		font-size: 0.2rem!important;
		color: #fefefe!important;
	}
</style>
</head><body style="visibility: visible;">
<dl class="pagetop">
 <dt><img src="webpay/recharge/icon.png" alt="衣范儿用户平台">
    <h1>衣范儿</h1>
    </dt>
  <!-- <dd><a class="b2" href="http://recharge.iwantang.com/">返回</a></dd> -->
</dl>
<div id="container">
  <dl class="pagetitle">
    <dt></dt>
    <dd>
      <h2><strong>充值</strong></h2>
    </dd>
    <dd>&nbsp;</dd>
  </dl>
  <ul class="formline">
    <li><?=$player_name;?></li>
  </ul>
  <ul class="selectlist" id="payWayList">
        <!-- <li id="weixinpay" class="selected" payway="微信">
      <dl class="payway weixinpay">
        <dt>微信支付</dt>
        <dd></dd>
      </dl>
    </li> -->
            <li id="alipay" class="selected" payway="支付宝">
      <dl class="payway alipay">
        <dt>支付宝支付</dt>
        <dd></dd>
      </dl>
    </li>
          </ul>
  <ul class="formline">
    <li style="margin: 0.213rem;font-size: 0.1466rem;color: #a2873d;">充值金额</li>
  </ul>
<ul class="selectlist" id="packageList">
			<?php foreach($menu as $k=>$v){ ?>
			<li id="<?=$k;?>" class="select" money="<?=$v['money'];?>">
                <dl>
                    <dt>
                        <span class="money"><?=$v['money'];?>元</span>
                        <span class="yuanbao"><?=$v['desc'];?></span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span><?=$v['gift'];?></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li>
            <?php } ?>
            </ul>
  <form action="" method="get">
    <ul class="formline">
      <li>
        <div class="inputbutton">
          <input type="button" value="确定" id="paysubmit">
        </div>
      </li>
    </ul>
  </form>
</div>
<script>
    $(function(){
    	function s(){
    		$("#packageList li").click(function(){
    		var n=this.id;t!=n&&(t=n,e=$(this).attr("money"),$("#packageList .selected").attr("class","select"),$(this).attr("class","selected")
    	    		)
    		})
    	}
    	
    	 var n=$("#payWayList .selected").attr("id"),i=$("#payWayList .selected").attr("payway"),t=null,e=null,r=!1;
    	    $(".rechargePopClose").click(function(){
    	    	$(".rechargePop").hide()
    	    }),
    	    $("#paysubmit").click(function(){
    	    	if(n=="alipay"&&$.isWeiXin())
    	    		return $(".rechargePop").show(),!1;
    	    	 if(n){
    	    	    if(!t&&n!="molpointpay")return layer.alert("请先选择充值金额"),!1
    	    	}
    	    	else return layer.alert("请先选择充值方式"),!1;
    	    	return location.href="info?t="+t,!0
    	    }),
    	    $("#payWayList li").click(function(){
    	    	var t=this.id;
    	    	if(t=="alipay"&&$.isWeiXin())
    	    		return $(".rechargePop").show(),!1;
    	    	n!=t&&(n=t,i=$(this).attr("payway"),$("#payWayList .selected").attr("class","select"),$(this).attr("class","selected"),n=="molpointpay"?($("#cashpay").hide(),$("#pointpay").show()):($("#cashpay").show(),$("#pointpay").hide())
        	    	)
    	    }),
    	    s()
    })
</script>

</body></html>