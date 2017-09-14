<?php
/* *
 * 配置文件
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$alipay_config['partner']		= '2088801186307426';

//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
$alipay_config['seller_id']	= $alipay_config['partner'];

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
$alipay_config['private_key']	= 'MIICXQIBAAKBgQCsfsvTG/6ifg7advN/ygjbQIWsp0q8mgpFNKwaKA5g/wCUr0nE
bWu6JFTNYDJwdtUR45msedOKB9CNzk7Ix2dvkOApRYYCkKawekdjVp1jzAli5cvr
QUbdBy75G4NCYC+zarr3iOmWPfhGv5k3VxD4Iwdoavzj9apf5niZ9VzgywIDAQAB
AoGAauUEoKW3jbD6uW1/hlCIXn7El/LwAKKg/LQgdvEdwAQmsv1RhHWejbLYDpGv
kDxD3Bskb1rruZ0QI+CWuBvCP79T536tGhCYtMLUzFQpthr1T6FmUjK5hn/m4DUq
JQjjayVgxyIlhAwrj8s3iPWJ0QJupiLSdn7YaYN7CsYZAUECQQDcaeCHkdjhczK6
21ta1fracCRikTlIjAEZrxaLSu6iPk1Sodo9aKJa3mlzCnV3KMiXnQfj0hPymfgz
3f0KlkehAkEAyFhc7zemDwVEH/4srQSIRhL7/qGZyp2xHMKUolisxvxI2P7IKwFv
BQwcVAGV03WMcuddFVfMRAviHd8pLFUg6wJAYDiQXl6bMYCaytvr+7GiOy3tlGIV
gcgsysuWqeRiXM2Z79LcdSZyifzCSAhu00AooZdZ1GjncGcq5Wmph6+nQQJBAJeY
dUn1u8ul2nSy6a9JvKv5dYCpWyu7wubWPY3St3oiMCUyNNiFzzHshgry4CIP0mSn
uQGLW98pdsHPuOH40F0CQQDYRGQ4GPC1hpMjXEfdwx4WooEnPaqUm7nGEko1zFKd
9v3/oKfGdTL0A6AQJnvZ92X21HQrOXVzn2EZSUk/O+SU';

//支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm 
$alipay_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['notify_url'] = "http://商户网址/create_direct_pay_by_user-PHP-UTF-8/notify_url.php";

$alipay_config['return_url'] = "http://pokemon.u776.com/pay";

//签名方式
$alipay_config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = getcwd().'\\cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

// 支付类型 ，无需修改
$alipay_config['payment_type'] = "1";
		
// 产品类型，无需修改
$alipay_config['service'] = "create_direct_pay_by_user";

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	
// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
$alipay_config['anti_phishing_key'] = "";
	
// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
$alipay_config['exter_invoke_ip'] = "";
		
//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>