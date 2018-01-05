<?php
include_once 'config.php';
function get_code($config) { 
	$deal_url = <<<EOF
	<form name="form1" id="form1" action="{$config['action']}" method="post">
    <input type="hidden" name="api_key" value="{$config['api_key']}">
    <input type="hidden" name="api_sig" value="{$config['api_sig']}">
    <input type="hidden" name="pm_id" value="{$config['pm_id']}">
    <input type="hidden" name="order_id" value="{$config['order_id']}">
    <input type="hidden" name="description" value="{$config['description']}">
    <input type="hidden" name="amount" value="{$config['amount']}">
    <input type="hidden" name="currency" value="{$config['currency']}">
    <input type="hidden" name="return_url" value="{$config['return_url']}">
</form>
	<script type='text/javascript'>function load_submit(){document.form1.submit();}load_submit();</script>
EOF;
	 return $deal_url; 
}
$order_id = $_REQUEST['orderinfo'];
$amount = $_REQUEST['amount'];
$pm_id = $_REQUEST['pm_id'];
$description = $_REQUEST['description'];
$description = 'test';
$infos = explode('_', $order_id);
$gameId = $infos[0];
$type = $infos[3];
$ruby = $infos[4];
global $key_arr;
$config = $key_arr[$gameId][$type];


$config['pm_id'] = $pm_id;
$config['order_id'] = $order_id;
$config['amount'] = $amount;
$config['description'] = $description;
$msg = implode("|", array($config['api_key'], $pm_id, $amount, $config['currency'],$order_id, $config['secret_key']));
$config['api_sig'] = md5($msg);
echo get_code($config);