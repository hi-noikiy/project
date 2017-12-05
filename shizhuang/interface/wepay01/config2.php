<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/14 下午2:14
 * @desc:
 * @param:
 * @return:
 */
define('ROOT_PATH', str_replace('interface/wepay01/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$fenbao_arr = array(
    '9501'=>array(
        'appid'             =>'wxe9ab07793643ad98',
        'MCHID'             =>'1460030502',
        'appkey'            =>'chjanxum0e82toybd36z95svwfkp1i74',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'41a131406a8239c3df7a252d819a6ca1',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),
    '9004'=>array(
        'appid'             =>'wx345d962ebbaeb33c',
        'MCHID'             =>'1415707102',
        'appkey'            =>'8934e7d15453e97507ef794cf7b0619d',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'76b0746279baf361be7692e98d4e7ea5',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),

    '9005'=>array(
        'appid'             =>'wxa4c03b90d0687906',
        'MCHID'             =>'1459781302',
        'appkey'            =>'CS0g9UOj2BQ1CkGrgGDfunyO9twbEB1r',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'5a51657acaac328408057db366c76548',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),

    '676001'=>array(
        'appid'             =>'wxa4c03b90d0687906',
        'MCHID'             =>'1459781302',
        'appkey'            =>'CS0g9UOj2BQ1CkGrgGDfunyO9twbEB1r',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'5a51657acaac328408057db366c76548',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),

    '9006'=>array(
        'appid'             =>'wxe9ab07793643ad98',
        'MCHID'             =>'1460030502',
        'appkey'            =>'chjanxum0e82toybd36z95svwfkp1i74',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'41a131406a8239c3df7a252d819a6ca1',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),

);