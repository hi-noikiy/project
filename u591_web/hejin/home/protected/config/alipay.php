<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/17
 * Time: 下午3:34
 */
$config = array (
    //应用ID,您的APPID。
    'app_id' => "2088801186307426",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIICXgIBAAKBgQDsHHAUHdDNsJ3+G1J6rfZV6esAZ5KC8h4ggjhD2SENLCvFLZay
qRdnY2C3YXNTX5Kp1CEWG7rg42g4Ml7i4F3rbgezlFMrNUGpMkWeT50Ws0mbsGGL
Yqz6IutS9bftUU8CjJNDn6FxVJwesiW5rS7C6qyzQqJ+ms1IlMeH4BtpRQIDAQAB
AoGBAIlMqCgqhmd2EwnXHYlVa+/dzNg5YktfPAGvd8SMmNmuG+3XatCN35wxf+Zj
dsq+wuya/Hp8uZlY5Bv5vo+z5SiKq24z/z2cSO4efktJVJazd/Z6yyEbtQWqP0Yc
6YVaQ39kdYvcnKEd/Zb0GksnnB1qlAyDS6aFtQbZQa2b2+/BAkEA9+tJBpjXUvrz
W4nX6wqUGaz2T1p5M9ePqPM0HZz2OhmD+uPb7tUhMykZE1mehq3vUfRHfRNtUbxg
695xQVDAWQJBAPPOn3a0T7/JFC6KYHBQrfC3+QTaqACMxzjDjDcvkohdUURqVaj5
yqbdkSSIQ+z3h40MlSfArvXhfWOAuJfgMs0CQCIDDnawO0IwGXjidVPSlLTdDMGg
OOaK2TOlge3aHdGktH2UCxU9+hsJtV35Oo1hiWal67TWGHZRML7LOqBqUqkCQQCV
eqhwSgrfJjSWeEa6dey/OryVik4Y0DdRCSuNpkAsFTK/RIaybDwgZJYZlOY4gmb8
RquoTM24eQC8oe4LR+09AkEAzTQbBWZRsB96cJxz3R9K+uTUnGreNzj4adsj6zls
uQp/0O07RN5BEU9+EvulVHZ/yFQrBGKWySnWcGrZ0AQoFw==",
    //异步通知地址
    'notify_url' => "http://www.u776.com/pay/callback",
    //同步跳转
    'return_url' => "http://www.u776.com/pay",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB",
);
return $config;