<?php

/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 服务器通讯类
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/

class XMWRequest
{

	protected $_clientId = "";
    protected $_clientSecret = "";

    const HOST = 'http://open.xmwan.com';

    public function __construct($clientId, $clientSecret)
    {
        if(empty($clientId))
        {
            throw new XMWException(XMWException::CODE_NO_APPKEY);
        }
        if(empty($clientSecret))
        {
            throw new XMWException(XMWException::CODE_NO_SECRET);
        }
        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;
    }

    protected function _request($url, $data, $decode = true)
    {
        $jsonStr = XMWHttp::request(self::HOST . $url, XMWHttp::METHOD_POST, $data);
        if(empty($jsonStr))
        {
            throw new XMWException(XMWException::CODE_NET_ERROR);
        }
        if(!$decode)
        {
            return $jsonStr;
        }

        $response = json_decode($jsonStr, true);
        if(empty($response))
        {
            throw new XMWException(XMWException::CODE_JSON_ERROR);
        }

        if(array_key_exists('error', $response))
        {
            throw new XMWException(XMWException::CODE_PARAM_ERROR, $response['error_description']);
        }
        return $response;
    }

}
