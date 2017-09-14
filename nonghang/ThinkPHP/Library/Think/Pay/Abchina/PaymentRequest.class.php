<?php
namespace Think\Pay\Abchina;
use Think\Pay\Abchina\core;
class PaymentRequest extends \Think\Pay\Abchina\core\TrxRequest {
	public $order = array (
		"PayTypeID" => "ImmediatePay",
		"OrderNo" => "",
		"ExpiredDate" => "60",
		"OrderAmount" => "",
		"Fee" => "0",
		"CurrencyCode" => "156",
		"ReceiverAddress" => "",
		"InstallmentMark" => "0",
		"InstallmentCode" => "",
		"InstallmentNum" => "",
		"BuyIP" => "",
		"OrderDesc" => "",
		"OrderURL" => "",
		"OrderDate" => "",
		"OrderTime" => "",
		"orderTimeoutDate" => "",
		"CommodityType" => "0202"
	);
	public $orderitems = array ();
	public $request = array (
		"TrxType" => 'PayReq',
		"PaymentType" => "A",
		"PaymentLinkType" => "",
		"UnionPayLinkType" => "",
		"ReceiveAccount" => "",
		"ReceiveAccName" => "",
		"NotifyType" => "1",
		"ResultNotifyURL" => "",
		"MerchantRemarks" => "",
		"IsBreakAccount" => "0",
		"SplitAccTemplate" => ""
	);
	private $Json = null;
	function __construct() {
		$this->Json = new \Think\Json();
	}

	protected function getRequestMessage() {
		$this->Json->arrayRecursive($this->order, "urlencode", false);
		$this->Json->arrayRecursive($this->request, "urlencode", false);
		$js = '"Order":' . (json_encode(($this->order)));
		$js = substr($js, 0, -1);
		$js = $js . ',"OrderItems":[';
		$count = count($this->orderitems, COUNT_NORMAL);
		for ($i = 0; $i < $count; $i++) {
			$this->Json->arrayRecursive($this->orderitems[$i], "urlencode", false);
			$js = $js . json_encode($this->orderitems[$i]);
			if ($i < $count -1) {
				$js = $js . ',';
			}
		}
		$js = $js . ']}}';
		$tMessage = json_encode($this->request);
		$tMessage = substr($tMessage, 0, -1);
		$tMessage = $tMessage . ',' . $js;
		$tMessage = urldecode($tMessage);
		return $tMessage;
	}

	/// 支付请求信息是否合法
	protected function checkRequest() {
		$tError = $this->isValid();
		if ($tError != null){
			echo "订单信息不合法！[" . $tError . "]";
			die();
		}
		
		// if ($tError != null)
			// throw new TrxException(TrxException :: TRX_EXC_CODE_1101, TrxException :: TRX_EXC_MSG_1101 . "订单信息不合法！[" . $tError . "]");
	}
	/// 支付请求信息是否合法
	private function isValid() {
		$DataVerifier = new \Think\Pay\Abchina\core\DataVerifier();
		$ILength = new \Think\Pay\Abchina\core\ILength();
		// if ($this->request["PaymentType"] === IPaymentType :: PAY_TYPE_UCBP && $this->request["PaymentLinkType"] === IChannelType :: PAY_LINK_TYPE_MOBILE) {
		// 	if (!($this->request["UnionPayLinkType"] === IChannelType :: UPMPLINK_TYPE_WAP) && !($this->request["UnionPayLinkType"] === IChannelType :: UPMPLINK_TYPE_CLIENT))
		// 		return "银联跨行移动支付接入方式不合法";
		// } else {
		// 	unset ($this->request["UnionPayLinkType"]);
		// }

		// if (!($this->request["NotifyType"] === INotifyType :: NOTIFY_TYPE_URL) && !($this->request["NotifyType"] === INotifyType :: NOTIFY_TYPE_SERVER))
		// 	return "支付通知类型不合法！";

		// if (!(DataVerifier :: isValidURL($this->request["ResultNotifyURL"])))
		// 	return "支付结果回传网址不合法！";

		if (strlen($this->request["MerchantRemarks"]) > 100) {
			return "附言长度大于100";
		}
		// if (($this->request["IsBreakAccount"] !== IIsBreakAccountType :: IsBreak_TYPE_YES) && ($this->request["IsBreakAccount"] !== IIsBreakAccountType :: IsBreak_TYPE_NO)) {
		// 	return "交易是否分账设置异常，必须为：0或1";
		// }

		//验证order信息
		// $payTypeId = $this->order["PayTypeID"];
		// if (!($payTypeId === IPayTypeID :: PAY_TYPE_DIRECTPAY) && !($payTypeId === IPayTypeID :: PAY_TYPE_PREAUTH) && !($payTypeId === IPayTypeID :: PAY_TYPE_INSTALLMENTPAY))
		// 	return "设定交易类型错误";

		

		if (!$DataVerifier :: isValidString($this->order["OrderNo"], $ILength::ORDERID_LEN))
			return "交易编号不合法";
		if (!$DataVerifier :: isValidDate($this->order["OrderDate"]))
			return "订单日期不合法";
		if (!$DataVerifier :: isValidTime($this->order["OrderTime"]))
			return "订单时间不合法";
		// if (!ICommodityType :: InArray($this->order["CommodityType"]))
		// 	return "商品种类不合法";
		if (!$DataVerifier :: isValidAmount($this->order["OrderAmount"], 2))
			return "订单金额不合法";
		if ($this->order["CurrencyCode"] !== "156")
			return "设定交易币种错误";

		return "";
	}
}