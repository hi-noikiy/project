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
    '0'=>array(
        'appid'             =>'wxfcf82770f12547eb',
        'MCHID'             =>'1424877802',
        'appkey'            =>'abc18293ddaafgyrvihicdaefwchscsc',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'appsecert'         =>'1efbdee92855e1ac82fbe0675b66f7ae',
        //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
        'SSLCERT_PATH'      =>'../cert/apiclient_cert.pem',
        'SSLKEY_PATH'       =>'../cert/apiclient_key.pem',
        //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
        'CURL_PROXY_HOST'   =>'0.0.0.0',
        'CURL_PROXY_PORT'   =>'0',
        //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'REPORT_LEVEL'      =>'1',
    ),
		'1'=>array(
				'appid'             =>'wx393ce797eb8fef75',
				'MCHID'             =>'1500093862',
				'appkey'            =>'08d369aadb5a29f5797a460cb79008ab',
				//APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
				'appsecert'         =>'qifkahhfiahd738649hkaghdgaad5914',
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