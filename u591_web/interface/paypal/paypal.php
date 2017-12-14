<?php
include_once 'config.php';
function get_code($config) { 
	$deal_url = '<br /><form style="text-align:center;" id="form1" name="form1" action="'.$config['action'].'" method="post" class="paypal">' .
			 "<input type='hidden' name='cmd' value='_xclick'>" . //告诉paypal该表单是立即购买
			"<input type='hidden' name='item_name' value='{$config['item_name']}'>" . //商品名称 item_number 
			"<input type='hidden' name='business' value='{$config['business']}'>" .//卖家帐号 也就是收钱的帐号 
			"<input type='hidden' name='amount' value='{$config['amount']}'>" .// 订单金额 
			"<input type='hidden' name='currency_code' value='{$config['currency_code']}'>" .// 货币 
			"<input type='hidden' name='return' value='{$config['return']}'>" .// 支付成功后网页跳转地址 
			"<input type='hidden' name='notify_url' value='{$config['notify_url']}'>" .//支付成功后paypal后台发送订单通知地址 
			"<input type='hidden' name='cancel_return' value='{$config['cancel_return']}'>" .//用户取消交易返回地址 
			"<input type='hidden' name='invoice' value='{$config['invoice']}'>" .//自定义订单号 
			"<input type='hidden' name='charset' value='{$config['charset']}'>" .// 字符集 
			"<input type='hidden' name='no_shipping' value='{$config['no_shipping']}'>" .// 不要求客户提供收货地址 
			"<input type='hidden' name='no_note' value='{$config['no_note']}'>" .// 付款说明 
			"</form><br /> 
					<script type='text/javascript'>function load_submit(){document.form1.submit();}load_submit();</script>";
	echo 'loading...';
	 return $deal_url; 
}
$orderinfo = $_REQUEST['orderinfo'];
$amount = $_REQUEST['amount'];
$infos = explode('_', $orderinfo);
$gameId = $infos[0];
$type = $infos[3];
$ruby = $infos[4];
global $key_arr;
$config = $key_arr[$gameId][$type];
$config['item_name'] = $gameId.'_'.$type.'_'.$ruby;
$config['amount'] = $amount;
$config['invoice'] = $orderinfo;
$config['no_note'] = $ruby.'ruby';
echo get_code($config);