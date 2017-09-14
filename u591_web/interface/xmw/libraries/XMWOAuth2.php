<?php

/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 OAuth2验证类
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/

class XMWOAuth2 extends XMWRequest
{

    const ACESSTOKEN_URL = '/v2/oauth2/access_token';
    const ACESSTOKEN_METHOD = 'POST';
    const RESPONSE_TYPE = 'code';

    public function getAccessTokenByCode($code)
    {
        $data = array(
            'grant_type' => "authorization_code",
            'code' => $code,
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
        );
        return $this->_request(self::ACESSTOKEN_URL, $data);
    }

    function getAccessTokenByRefreshToken($refreshToken)
    {
        $data = array(
            'grant_type' => "refresh_token",
            'refresh_token' => $refreshToken,
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
        );
        return $this->_request(self::ACESSTOKEN_URL, $data);
    }

}

