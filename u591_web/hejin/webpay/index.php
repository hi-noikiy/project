<!DOCTYPE html>
<!-- saved from url=(0034)http://recharge.iwantang.com/order -->
<html style="font-size: 96px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1">
<title>口袋妖怪-充值</title>
<!--<base href="http://recharge.iwantang.com">--><base href=".">
<link href="./recharge/wap501b.css" rel="stylesheet">
<script src="./recharge/utility.js"></script>
<script src="./recharge/wap.js"></script>
</head><body style="visibility: visible;">
<dl class="pagetop">
 <dt><a href="http://recharge.iwantang.com/#"><img src="./recharge/icon.png" alt="口袋妖怪用户平台">
    <h1>口袋妖怪</h1>
    <h4>挑战精灵道馆</h4>
    </a></dt>
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
    <li>P51茸茸羊 自私の银次</li>
  </ul>
  <ul class="selectlist" id="payWayList">
        <li id="weixinpay" class="selected" payway="微信">
      <dl class="payway weixinpay">
        <dt>微信支付</dt>
        <dd></dd>
      </dl>
    </li>
            <li id="alipay" class="select" payway="支付宝">
      <dl class="payway alipay">
        <dt>支付宝支付</dt>
        <dd></dd>
      </dl>
    </li>
          </ul>
  <ul class="formline">
    <li>充值金额</li>
  </ul>
<ul class="selectlist" id="packageList"><li id="1184827998" class="select" money="6">
                <dl>
                    <dt>
                        <span class="money">6元</span>
                        <span class="yuanbao">60钻石</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li><li id="1184830493" class="select" money="30">
                <dl>
                    <dt>
                        <span class="money">30元</span>
                        <span class="yuanbao">300钻石</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li><li id="1184836118" class="select" money="128">
                <dl>
                    <dt>
                        <span class="money">128元</span>
                        <span class="yuanbao">1280钻石</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li><li id="1184836366" class="select" money="328">
                <dl>
                    <dt>
                        <span class="money">328元</span>
                        <span class="yuanbao">3280钻石</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li><li id="1184836997" class="select" money="648">
                <dl>
                    <dt>
                        <span class="money">648元</span>
                        <span class="yuanbao">6480钻石</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span></span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li><li id="1184841747" class="select" money="25">
                <dl>
                    <dt>
                        <span class="money">25元</span>
                        <span class="yuanbao">25元月卡</span>
                        <span class="gift" style="display:inline-block;vertical-align:top;">
                                    <span>领取120钻石/天</span><br>
                        </span>
                    </dt>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;</dd>
                </dl>
            </li></ul>
  <form action="" method="get">
    <ul class="formline">
      <li>
        <div class="inputbutton">
          <input type="button" value="使用微信支付" id="paysubmit">
        </div>
      </li>
    </ul>
  </form>
</div>
<script>
    $(function(){
    	function u(){
    		n&&(n.indexOf("point")>=0||!t?$("#paysubmit").val("使用"+i+"支付"):$("#paysubmit").val("使用"+i+"支付"+(e/1)+"元"))
    	}
    	function s(){
    		$("#packageList li").click(function(){
    		var n=this.id;t!=n&&(t=n,e=$(this).attr("money"),$("#packageList .selected").attr("class","select"),$(this).attr("class","selected"),u())
    		})
    	}
    	/*function f(){
    		$("#packageList li").click(function(){
    			$("#packageList").html("<li><dl><dt>正在获取..<\/dt><\/dl><\/li>"),setTimeout(o,100)
    		})
    	}
    	 function o(){
    	    	currentAjax=$.ajax({
    	    		type:"POST",
    	    		contentType:"application/json",
    	    		url:"getpackage",
    	    		data:$.toJSON({_token:"1VqDr3QpILpka9nC5j13XZR518ewgDYFycEKvzF0"}),
    	    		dataType:"json",
    	    		success:function(n){
    	        		r||(n.errcode==0?n.content?(r=!0,$("#packageList").html(n.content),s()):($("#packageList").html("<li><dl><dt>暂未定义充值套餐，请点击重试<\/dt><\/dl><\/li>"),f()):($("#serverList").html("<li><dl><dt>获取套餐列表失败，点此重试<\/dt><\/dl><\/li>"),f()))
    	        	},
    	        	error:function(){
    	        		r||($("#serverList").html("<li><dl><dt>获取套餐列表失败，点此重试<\/dt><\/dl><\/li>"),f())
    	        	}
    	    	})
    	    }*/
    	 var n=$("#payWayList .selected").attr("id"),i=$("#payWayList .selected").attr("payway"),t=null,e=null,r=!1;
    	    $(".rechargePopClose").click(function(){
    	    	$(".rechargePop").hide()
    	    }),
    	    $("#paysubmit").click(function(){
    	    	if(n=="alipay"&&$.isWeiXin())
    	    		return $(".rechargePop").show(),!1;
    	    	 if(n){
    	    	    if(!t&&n!="molpointpay")return alert("请先选择充值金额"),!1
    	    	}
    	    	else return alert("请先选择充值方式"),!1;
    	    	return location.href="payorder/"+n+"/"+t,!0
    	    }),
    	    u(),
    	    $("#payWayList li").click(function(){
    	    	var t=this.id;
    	    	if(t=="alipay"&&$.isWeiXin())
    	    		return $(".rechargePop").show(),!1;
    	    	n!=t&&(n=t,i=$(this).attr("payway"),$("#payWayList .selected").attr("class","select"),$(this).attr("class","selected"),n=="molpointpay"?($("#cashpay").hide(),$("#pointpay").show()):($("#cashpay").show(),$("#pointpay").hide()),u()
    	   				/*,currentAjax=$.ajax({
        	    			type:"POST",
        	    			contentType:"application/json",
        	    			url:"setpayway",
        	    			data:$.toJSON({payway:n,_token:"1VqDr3QpILpka9nC5j13XZR518ewgDYFycEKvzF0"}),
        	    			dataType:"json",
        	    			success:function(){},
        	    			error:function(){}
        	    		})*/
        	    	)
    	    }),
    	    s()
    })
</script>

</body></html>