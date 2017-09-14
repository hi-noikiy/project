<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 支付宝支付
* ==============================================
* @date: 2016-11-24
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/alipay_wap/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$config = array (
		//应用ID,您的APPID。
		'app_id' => "2016121504297914",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAvsAIbYIO/5VFxw2daSxSKYJYna0H2pVUKTeFAX5h+hEGz9bk8TYmQ0OPDmEe8kVfErcVzVhJ7zutwB6vKnGPwxZj5qmrFsBx7z4UNbuyq6zJ6DsPk3hOgN8orKOP7wBAhnnRCMxSdLceMOEUnk2mN+D7NwNGCZidgpyR4nEy2zbvEfUSkAcajNMfddsys4ISy5wQu/bJMxHGZcNhEbLJRqeo7Wqgk2Z3F0IJFXrORlUkxlMKSii5t0Ltj5K75SYc5BcCEPLtBaFrBw1EFp7WpPH55EGoqNMyaReK6eXN811S+YczkAVtT0FN//0URs7yvUroCEOCF+iXr6biDk657wIDAQABAoIBAQCcmnIQ3d9MbBQOeRoL18xYsd/pC77P8RtYf3FeKZFsyX/LMQVjF27QeG3Bg3DxvIxyhAeNP/frRha/DTIIaZV6uC4qmI+TLUoc/d1/w+rLUR+u3pZKH1JsMUpmeh5YPN+8x9QbIgxsME7EGHZiBSP66mW046YjiL2AFuUagI8dMw7CQQrQ+NdRd6CScSBp1G7q3/cJU8N0Aq1W4C4MEewet9Q5hTC6Zaz9QrQ8ruMxwv8UX/6XyokDTHxtlZl0Gu68Q+b53A3FtHED8rOTcI0zjjF4/l6s4z+sDMBCDp/V0wBciyYa0ZBUJ/31CUqNASHYqdC6tY5uyv9xW+HjNtBxAoGBAPUvm6SXcFVkqmOQn/tY+V6hVklczInhWMuXlNa5JW7wCiZm1gDHnL2z/6cScHqkfMno4kvv7zlRzVebFLIt0FQEa/TgkSz4efBkZT71WUar2i0xnh2BSzdD4Fj5ZcewtMop7TQElrgW+9MqdtgC3t9lPIe1LTtsLGYZ3ZGgonT3AoGBAMcpylz/3Y1oJg9Q1Um29RXvJ+xaipFYzT8tyi6S7CiIvEeVOTWFMpFiKCyB19dJG+YvSSwNVzrf1eC9XyY7Vkj19fRPmmn++X68H0BGaB265Am8fkWGmEY1rkZe8kDPKlH0Ss8dKhfbjWBRa2I3yZ40dEjKFM9YYkDp1poUoTzJAoGAQKqWzVlXul37hpkNaNh1pNy+ikjcdX0m00OecOeSFOlgc/JpfPkowOvpgKIfYmlhtEsk7ExS9vY5R2LSmY/1w0MLSiG0iHzchGemQG2rZzVKLONKRZPlR6UyVaUpj/puJGfcdPzE17bQIkiv4ZcYOylTRqOlU2fbae5mftM0Jc8CgYB34VfFkt2w+DewkF9R96aehU3qnrU5t9ITbWR9lEJCQ8vQ6ql85agCiqrT33QkSgVZEK1irsUK0yDSHirfwGe3kxVw8Vlo4+kZt/K3pamV+6C3m5YGE3YlDLrR8OSwzgITQpClClED+0ul5lxUym+5oqk8BydvhyvdFuPt6u0CwQKBgBjiN0jUgVi5OT5fqBVQSbUXvZq344s3UiTzzQseXAfp4BlGIuhPWVbsymmyzyBmJbkfK4waf207S25DE4vMrKBbRMFfatcDLilcgkxq3ms2sKJKDbbHA+9+FuTJM/WkhAquWqnxgZJfQY4jB+U8jqdKgVdxbZkrxpKYiK/irQui",
		//异步通知地址
		'notify_url' => "http://fhweb.u776.com:86/interface/alipay_wap/callback.php",

		//同步跳转
		'return_url' => "http://fhweb.u776.com:84/interface/aliwappay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi5h2NLK88SDo/hOVHO1I6iRBL2Wg7WtgPV4JIUHsenMXdh9A40loywOyiwFFrWSok+YRoTfdIKUbtwjTazZ+mTTjVwf25J8LmHOow2Zebw9fbBnUg7iFzYHhFXN8VUOfKuVXOjDiFx3Uhnb3DdVnshyl939vcKYSxVDofqffR+WU8ZjKigB82vQl6rEztnLT9S+/ko2sprBwQT00bnVMoOIk8BwE8QRqKKnZ3/cuoX+lP9LZOmtboDOQPENdSwquiaJUHilQRcPWbMKKG22ON5Xa3F0r7Ojof8IIf84rznI8f6UDlokFRWIfZIs5tKGbzMm2zT4Ymhzwa4fjvc6GSwIDAQAB",


);