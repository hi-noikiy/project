<?php

/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 异常类
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/

class XMWException extends Exception
{
    const CODE_NET_ERROR = '50001';
    const CODE_JSON_ERROR = '50002';
    const CODE_NO_APPKEY = '40001';
    const CODE_NO_SECRET = '40002';
    const CODE_PARAM_ERROR = '400';

    private static $_MESSAGE_MAP = array(
        '50001' => '访问远程接口失败, 请检查网络.',
        '50002' => 'JSON解析失败.',
        '40001' => '请填写ClientId.',
        '40002' => '请填写ClientSecret.',
        '400' => '传入的参数有误.'
    );

    public function __construct($code, $message = null)
    {
        if(array_key_exists($code, self::$_MESSAGE_MAP) && $message === null)
        {
            $message = self::$_MESSAGE_MAP[$code];
        }
        parent::__construct($message, $code);
    }
}
