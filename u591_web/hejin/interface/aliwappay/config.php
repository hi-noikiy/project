<?php
define('ROOT_PATH', str_replace('hejin/interface/aliwappay/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$config = array (	
		//异步通知地址
		'notify_url' => "http://pokeweb.u591776.com:83/interface/alipay_wap/callback.php",
		//同步跳转
		'return_url' => "http://pokeweb.u591776.com:84/interface/aliwappay/return_url.php",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAvp8qCnz37YpOAiDYbnPdxhL2SWOaY8NVVFetj00mVCWefDbywmaiIBTyp9A1nZNryvQWbB/vZq7v1UiwYWpqXdNTO/p+NYgThrmJ1wR6Fmi0lxDHMOk5fpVJutNa6AfdZgK/8K/wG7pzn3+6Y0A7g7VGDQJo/k4Mr3mGH/llWH9Cbebhu7/Aajtv/ZOJnOUux7gg2CXaplyqMbX01oWwhC9mYJEgK+gObjBbS4hzpf4kZDXfxPy+ANrVOGfXUafAMVlYHNcK6EaZgtqprE50+frsNMF8PMVpDd5KegIXIc1yrNS/HHAL2emjNTzS/uArl2K+qtTFzyXH0gwTJTVjrwIDAQABAoIBAChDkGrEuM3I/+89Jto1ySt0h+c6jbry4Iw/NN7nCeiORaqxJJfhe+9Q3WyK8zhEkmk9I5tVJ4lF/EzN3MvG4Y7N+2/Y2l3OT2mOuUncnTkshrC+D4UKTmWpfPhkeng2aRKKFCbv0sTYnmGbZjBeufN5D8Hht3G35MAP6D/Kuxh08tN87Fr4z2C6+6sJ4DjUlBXNeQtCozQtsWceIj+EVpY7TtEJ5bSf/+ihr0pU/NDNI3HqZ9hAwS09EdvMeqpCyBsf93OwyNg2qnsKqO/fxvHW/R6EBe2/yT5mhpJcQoj3aWOZx3SSCW1zQdO0o/NouzGDZvarCWBUYWdIIWssZokCgYEA/NcamYQ8AKduUo0MCKjU6JKAq+VeHhwNtgUKrfce1nEqD8N5fOK53zQzrb/zGI1F6IIyH875KCJotBtPhMCf8g4UObUQKy5dlGgh6V0+qiZ7EnBV8crlipBjAMK08sZetPxKNYhTTLlA+4GuOemSBemnL4wcyEdLz66fsHndzjsCgYEAwQECLIQg5Z/0HA1b91VJFiybZXl9Z3T4Iu21Z4A+cUgnSnBTaYpt9RKRN0gUFhpK3HUcjbP7iHFsp1INkcffbZGLAxQzwFyomHs3UYG88lDnAYSifnvnLIvMrTYE6O9EdYw8cKS1wjA8X3MU2IN+N9WXv2K8gHk6V367gSPrpR0CgYByOxi4bQbS4NI2F6VtMCHo5W+eueT4w4HqT4LQsApNphjzwEPeCW0ii6ohgMxqtkD0WAYfWto0qL8XSBN4DbL1oCjBHa2tZKhAyX8wuMD8DKCj5v7dW+ay4KLqEA3CdZ8KM8WvQ+Umj7ftKVy2q3EenL68+5dxYMy+is145MYySwKBgQCCKJDzvoOE9/MjO+sqiIyKdLiznVPyAlMZEyAEVdtGgDdnHwtRoLZ40PW3x1csdLzJof0InzlhkcSJrm9SQe2usbmC8QvZjWNxuZ7by9fFvsObJXyEOkDzrK/ym/yBrmKHt3RHLv0YoXkFkdGcFkl3lAcLXaXsKlUrDl+bPsM7DQKBgHEalG/KhXNb8jcVIYHfOzEY0ar/7W1x5mdAg1+J0Hw2J9T/zy0letl3W1wkH0ZcZ4QzKhSnkW+OoLHSP7F1/3SQeX2eHPAWEivbYQ7uwVYikdoikg9JQ/ABxxyHkKI0u0Kv/NlLi1pZCLUajfBzYglPjNvRN1Q8bWz7DErIWZYK",
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi5h2NLK88SDo/hOVHO1I6iRBL2Wg7WtgPV4JIUHsenMXdh9A40loywOyiwFFrWSok+YRoTfdIKUbtwjTazZ+mTTjVwf25J8LmHOow2Zebw9fbBnUg7iFzYHhFXN8VUOfKuVXOjDiFx3Uhnb3DdVnshyl939vcKYSxVDofqffR+WU8ZjKigB82vQl6rEztnLT9S+/ko2sprBwQT00bnVMoOIk8BwE8QRqKKnZ3/cuoX+lP9LZOmtboDOQPENdSwquiaJUHilQRcPWbMKKG22ON5Xa3F0r7Ojof8IIf84rznI8f6UDlokFRWIfZIs5tKGbzMm2zT4Ymhzwa4fjvc6GSwIDAQAB",
		
		
		//应用ID,您的APPID。
		'app_id' => "2017060607431861",
		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		
		/*'app_id' => "2016110100784197",
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",*/
);