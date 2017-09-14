<?php
/**
 * 
 * 微信支付API异常类
 * @author widyhu
 *
 */
namespace Micro\Frameworks\Pay\Wxpay;
use Phalcon\DI\FactoryDefault;
class WxPayException extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
