<?php

/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 订单处理类
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/

class XMWPurchase extends XMWRequest
{

    const CREATE_PURCHASE_URL = '/v2/purchases';
    const CREATE_PURCHASE_METHOD = 'POST';
    const VERIFY_PURCHASE_URL = '/v2/purchases/verify';
    const VERIFY_PURCHASE_METHOD = 'POST';

    public function createPurchase($accessToken, $data)
    {
    	$data = self::createFilter($data);
    	$data['sign'] = $this->sign($data);
    	$data['access_token'] = $accessToken;
    	$data['client_id'] = $this->_clientId;
    	$data['client_secret'] = $this->_clientSecret;
    	return $this->_request(self::CREATE_PURCHASE_URL, $data);
    }

    private static function createFilter($data)
    {
    	$purchase = array();

    	if(!array_key_exists('app_order_id', $data) || empty($data['app_order_id']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: app_order_id .');
    	}
    	$purchase['app_order_id'] = $data['app_order_id'];

    	if(!array_key_exists('app_user_id', $data) || empty($data['app_user_id']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: app_user_id .');
    	}
    	$purchase['app_user_id'] = $data['app_user_id'];

    	if(!array_key_exists('amount', $data) || empty($data['amount']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: amount .');
    	}
    	$purchase['amount'] = $data['amount'];

    	if(!array_key_exists('notify_url', $data) || empty($data['notify_url']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: notify_url .');
    	}
    	$purchase['notify_url'] = $data['notify_url'];

    	if(!array_key_exists('timestamp', $data) || empty($data['timestamp']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: timestamp .');
    	}
    	$purchase['timestamp'] = $data['timestamp'];

    	if(array_key_exists('app_subject', $data) && !empty($data['app_subject']))
    	{
    		$purchase['app_subject'] = $data['app_subject'];
    	}

    	if(array_key_exists('app_subject', $data) && !empty($data['app_subject']))
    	{
    		$purchase['app_subject'] = $data['app_subject'];
    	}

    	if(array_key_exists('app_description', $data) && !empty($data['app_description']))
    	{
    		$purchase['app_description'] = $data['app_description'];
    	}

    	if(array_key_exists('app_ext1', $data) && !empty($data['app_ext1']))
    	{
    		$purchase['app_ext1'] = $data['app_ext1'];
    	}

    	if(array_key_exists('app_ext2', $data) && !empty($data['app_ext2']))
    	{
    		$purchase['app_ext2'] = $data['app_ext2'];
    	}

    	return $purchase;
    }

    private function sign($data)
    {
    	ksort($data);
    	foreach($data as $field => &$value)
    	{
    		$value = $field . '=' . $value;
    	}
    	$sign = md5(implode('&', $data) . '&client_secret=' . $this->_clientSecret);
    	return $sign;
    }

    public function checkSign($data)
    {
		if(!array_key_exists('sign', $data) || empty($data['sign']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 sign 未获取到.');
    	}

    	$sign = array();

    	if(!array_key_exists('serial', $data) || empty($data['serial']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 serial 未获取到.');
    	}
    	$sign['serial'] = $data['serial'];

    	if(!array_key_exists('amount', $data) || empty($data['amount']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 amount 未获取到.');
    	}
    	$sign['amount'] = $data['amount'];

    	if(!array_key_exists('status', $data) || empty($data['status']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 status 未获取到.');
    	}
    	$sign['status'] = $data['status'];

    	if(!array_key_exists('app_order_id', $data) || empty($data['app_order_id']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 app_order_id 未获取到.');
    	}
    	$sign['app_order_id'] = $data['app_order_id'];

    	if(!array_key_exists('app_user_id', $data) || empty($data['app_user_id']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '参数 app_user_id 未获取到.');
    	}
    	$sign['app_user_id'] = $data['app_user_id'];

    	if(array_key_exists('app_subject', $data) && !empty($data['app_subject']))
    	{
            $sign['app_subject'] = $data['app_subject'];
    	}
    	if(array_key_exists('app_description', $data) && !empty($data['app_description']))
    	{
            $sign['app_description'] = $data['app_description'];
    	}
    	if(array_key_exists('app_ext1', $data) && !empty($data['app_ext1']))
    	{
            $sign['app_ext1'] = $data['app_ext1'];
    	}
    	if(array_key_exists('app_ext2', $data) && !empty($data['app_ext2']))
    	{
            $sign['app_ext2'] = $data['app_ext2'];
    	}

    	$sign = $this->sign($sign);

    	return $sign === $data['sign'] ? true : false;
    }

    public function verifyPurchase($data)
    {
    	$data = $this->verifyFilter($data);
    	$data['sign'] = $this->sign($data);
    	$data['client_id'] = $this->_clientId;
    	$data['client_secret'] = $this->_clientSecret;
    	return $this->_request(self::VERIFY_PURCHASE_URL, $data);
    }

    private function verifyFilter($data)
    {
    	$purchase = array();

    	if(!array_key_exists('serial', $data) || empty($data['serial']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: serial .');
    	}
    	$purchase['serial'] = $data['serial'];

    	if(!array_key_exists('amount', $data) || empty($data['amount']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: amount .');
    	}
    	$purchase['amount'] = (int) $data['amount'];

    	if(!array_key_exists('app_order_id', $data) || empty($data['app_order_id']))
    	{
            throw new XMWException(XMWException::CODE_PARAM_ERROR, '需要参数: app_order_id .');
    	}
    	$purchase['app_order_id'] = $data['app_order_id'];

    	return $purchase;
    }

}
