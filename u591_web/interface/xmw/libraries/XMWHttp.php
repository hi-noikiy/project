<?php

/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 HTTP请求类
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/

class XMWHttp
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const USERAGENT = 'XMW_SDK_PHP_v1.4 beta(20140721)';

    public static function request($url, $mode, $params = '', $timeout = 10)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, self::USERAGENT);
        if($mode == 'POST')
        {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));
            curl_setopt($curlHandle, CURLOPT_POST, true);
            if(is_array($params))
            {
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            else
            {
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $params);
            }
        }
        else
        {
            if(is_array($params))
            {
                $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
            }
            else
            {
                $url .= (strpos($url, '?') === false ? '?' : '&') . $params;
            }
        }
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        $result = curl_exec($curlHandle);
        $errno = curl_errno($curlHandle);
        if($errno)
        {
            $result = $errno;
        }
        curl_close($curlHandle);
        return $result;
    }
}
