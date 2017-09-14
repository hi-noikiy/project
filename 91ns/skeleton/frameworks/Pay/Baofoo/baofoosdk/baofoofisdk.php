<?php
	/*
	 * 宝付SDK
	 * 供宝付商户快速集成使用
	 * 接口采用标准接口方法实现，数组作为传输数据类型
	 * 接口仅供参考，商户可自行根据实际需求修改此SDK
	 */

	error_reporting(E_ALL ^ E_NOTICE);
	date_default_timezone_set("PRC");

	// 如果您的网站是HTTPS的，则请求宝付的前缀也必须是HTTPS
	// 反之，则请求宝付的前缀为HTTP
	define("BAOFOO_FI_URL_TEST", "http://vgw.baofoo.com/"); // 测试地址
	define("BAOFOO_FI_URL_REAL", "http://gw.baofoo.com/"); // 正式地址
	define("BAOFOO_FI_PAY", "payindex"); // 正式地址
	define("BAOFOO_FI_QUERY", "order/query"); // 正式地址
	
	define("BAOFOO_FI_MARK_PAY", "|");
	define("BAOFOO_FI_MARK_PAGE", "~|~");

	define("BAOFOO_FI_DESC_0000","支付失败");
	define("BAOFOO_FI_DESC_0001","系统错误");
	define("BAOFOO_FI_DESC_0002","订单超时");
	define("BAOFOO_FI_DESC_0011","系统维护");
	define("BAOFOO_FI_DESC_0012","无效商户");
	define("BAOFOO_FI_DESC_0013","余额不足");
	define("BAOFOO_FI_DESC_0014","超过支付限额");
	define("BAOFOO_FI_DESC_0015","卡号和卡密错误");
	define("BAOFOO_FI_DESC_0016","不合法的IP地址");
	define("BAOFOO_FI_DESC_0017","重复订单金额不符");
	define("BAOFOO_FI_DESC_0018","卡密已被使用");
	define("BAOFOO_FI_DESC_0019","订单金额错误");
	define("BAOFOO_FI_DESC_0020","支付的类型错误");
	define("BAOFOO_FI_DESC_0021","卡类型有误");
	define("BAOFOO_FI_DESC_0022","卡信息不完整");
	define("BAOFOO_FI_DESC_0023","卡号、卡密、金额不正确");
	define("BAOFOO_FI_DESC_0024","不能用此卡继续做交易");
	define("BAOFOO_FI_DESC_0025","订单无效");
	
	final class BaofooFiService {
	
		private $ResultDesc_Msg = array(
			"01"=>"支付成功",
			"0000"=>"支付失败",
			"0001"=>"系统错误",
			"0002"=>"订单超时",
			"0011"=>"系统维护",
			"0012"=>"无效商户",
			"0013"=>"余额不足",
			"0014"=>"超过支付限额",
			"0015"=>"卡号和卡密错误",
			"0016"=>"不合法的IP地址",
			"0017"=>"重复订单金额不符",
			"0018"=>"卡密已被使用",
			"0019"=>"订单金额错误",
			"0020"=>"支付的类型错误",
			"0021"=>"卡类型有误",
			"0022"=>"卡信息不完整",
			"0023"=>"卡号、卡密、金额不正确",
			"0024"=>"不能用此卡继续做交易",
			"0025"=>"订单无效"
		);
	
		private $MemberId;
		private $TerminalId;
		private $SignKey;
		private $TestMode;

		/**
		 *	@Param $MemberId  会员号
		 *	@Param $TerminalId  终端号
		 *	@Param $SignKey  秘钥
		 *	@Param $TestMode  是否为test模式
		 */
		function __construct($MemberId, $TerminalId, $SignKey, $TestMode=false) {
			$this->MemberId = $MemberId;
			$this->TerminalId = $TerminalId;
			$this->SignKey = $SignKey;
			$this->TestMode = $TestMode;
		}

		/*
		 * 支付跳转
		 * @param $payinfo 支付信息
		 * array (
		 *   "PayID" => "", // 参考API文档《附录：产品功能》
		 *   "TradeDate" => "", // 订单日期，为空则自动生成
		 *   "TransID" => "", // 订单号，为空则自动生成
		 *   "OrderMoney" => "", // 交易金额，元，SDK会自动转化为分
		 *   "ProductName" => "", // 商品名称，默认为空
		 *   "Amount" => "", // 商品数量，默认为1
		 *   "Username" => "", // 用户名称，默认为空
		 *   "AdditionalInfo" => "", // 附加字段，默认为空
		 *   "PageUrl" => "", // 页面返回地址，不可为空
		 *   "ReturnUrl" => "", // 交易通知地址，不可为空
		 * )
		 * 返回
		 * array (
		 *   "code" => "", // 返回码  01代表成功
		 *   "message" => "", // 返回消息
		 *   "TransID" => "", // 交易商户订单ID
		 *   "HtmlContent" => "", // 包装后的自动提交表单
		 * )
		 */
		function pay($payinfo) {

			if (empty($this->MemberId)) {
				return array( "code" => "0012", "message" => "会员号不能为空" );
			}

			if (empty($this->TerminalId)) {
				return array( "code" => "0012", "message" => "终端号无效" );
			}

			if (empty($this->SignKey)) {
				return array( "code" => "0012", "message" => "秘钥不能为空" );
			}
			
			if (empty($payinfo)) {
				return array( "code" => "0001", "message" => "支付信息为空" );
			}

			if (array_key_exists("ProductName", $payinfo) && strlen($payinfo["ProductName"]) > 64) {
				return array( "code" => "0001", "message" => "商品名称长度不可超过64位" );
			}
			
			if (array_key_exists("Username", $payinfo) && strlen($payinfo["Username"]) > 64) {
				return array( "code" => "0001", "message" => "用户名长度不可超过64位" );
			}
			
			if (array_key_exists("AdditionalInfo", $payinfo) && strlen($payinfo["AdditionalInfo"]) > 64) {
				return array( "code" => "0001", "message" => "附加字段长度不可超过128位" );
			}
			
			if (!array_key_exists("OrderMoney", $payinfo) || empty($payinfo["OrderMoney"])) {
				return array( "code" => "0001", "message" => "支付金额不可为空" );
			}

			if (!array_key_exists("PageUrl", $payinfo) || empty($payinfo["PageUrl"])) {
				return array( "code" => "0001", "message" => "支付页面返回地址不可为空" );
			}

			if (!array_key_exists("ReturnUrl", $payinfo) || empty($payinfo["ReturnUrl"])) {
				return array( "code" => "0001", "message" => "支付服务后端通知地址不可为空" );
			}

			$nowTime = date("YmdHis");

			if (!array_key_exists("TradeDate", $payinfo) || empty($payinfo["TradeDate"])) {
				$payinfo["TradeDate"] = $nowTime;
			}

			if (!array_key_exists("TransID", $payinfo) || empty($payinfo["TransID"])) {
				$payinfo["TransID"] = $nowTime.rand(1000,9999);
			}

			if (!array_key_exists("Amount", $payinfo) || empty($payinfo["Amount"])) {
				$payinfo["Amount"] = 1;
			}
			
			$formParams = array(
				"MemberID" => $this->MemberId,
				"TerminalID" => $this->TerminalId,
				"InterfaceVersion" => "4.0",
				"KeyType" => 1,
				"PayID" => $payinfo["PayID"],
				"TradeDate" => $payinfo["TradeDate"],
				"TransID" => $payinfo["TransID"],
				"OrderMoney" => abs($payinfo["OrderMoney"]) * 100,
				"ProductName" => $payinfo["ProductName"],
				"Amount" => $payinfo["Amount"],
				"Username" => $payinfo["Username"],
				"AdditionalInfo" => $payinfo["AdditionalInfo"],
				"NoticeType" => 1,
				"PageUrl" => $payinfo["PageUrl"],
				"ReturnUrl" => $payinfo["ReturnUrl"]
			);
			
			//MD5签名格式
			$formParams["signature"] = md5(
				$formParams["MemberID"].BAOFOO_FI_MARK_PAY
				.$formParams["PayID"].BAOFOO_FI_MARK_PAY
				.$formParams["TradeDate"].BAOFOO_FI_MARK_PAY
				.$formParams["TransID"].BAOFOO_FI_MARK_PAY
				.$formParams["OrderMoney"].BAOFOO_FI_MARK_PAY
				.$formParams["PageUrl"].BAOFOO_FI_MARK_PAY
				.$formParams["ReturnUrl"].BAOFOO_FI_MARK_PAY
				.$formParams["NoticeType"].BAOFOO_FI_MARK_PAY
				.$this->SignKey);
			
			$htmlcontent = '<!DOCTYPE html><html>';
			$htmlcontent .=  '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>宝付充值跳转</title><style>body{text-align:center;margin-top:150px;}</style></head>';
			$htmlcontent .=  '<body onload="document.payform.submit()">';
			//$htmlcontent .=  '<body>';
			$htmlcontent .=  '<h1>正在跳转至支付页面</h1>';
			$htmlcontent .=  '<form id="payform" name="payform" method="post" action="'.(($this->TestMode?BAOFOO_FI_URL_TEST:BAOFOO_FI_URL_REAL).BAOFOO_FI_PAY).'">';
			foreach($formParams as $p_name=>$p_value) {
				$htmlcontent .=  '<input type="hidden" name="'.$p_name.'" value="'.$p_value.'" />';
			}
			$htmlcontent .=  '</form></body></html>';
			
			return array(
				"code" => "01",
				"TransID" => $formParams["TransID"],
				"HtmlContent" => $htmlcontent
			);
		}

		/*
		 * 页面返回验证  仅用于验证基本信息
		 * 返回
		 * array (
		 *   "code" => "", // 返回码   01为成功
		 *   "message" => "" // 返回消息
		 *   "TransID" => "", // 订单ID
		 *   "factMoney" => "", // 成功金额（元）
		 *   "additionalInfo" => "", // 附加信息
		 *   "SuccTime" => "" // 订单成功时间
		 * )
		 */
		function recvcheck() {
			$MemberID = $_REQUEST["MemberID"];
			$TerminalID = $_REQUEST["TerminalID"];
			$TransID = $_REQUEST["TransID"];
			$Result = $_REQUEST["Result"];
			$resultDesc = $_REQUEST["ResultDesc"];
			$factMoney = $_REQUEST["FactMoney"];
			$additionalInfo = $_REQUEST["AdditionalInfo"];
			$SuccTime = $_REQUEST["SuccTime"];
			$Md5Sign = $_REQUEST["Md5Sign"];
			
			if ($MemberID != $this->MemberId) {
				return array( "code" => "0012", "message" => "非法的返回会员ID" );
			}
			
			if ($TerminalID != $this->TerminalId) {
				return array( "code" => "0012", "message" => "非法的返回终端号" );
			}
			
			$CalcSign = md5(
				'MemberID='.$this->MemberId.BAOFOO_FI_MARK_PAGE
				.'TerminalID='.$this->TerminalId.BAOFOO_FI_MARK_PAGE
				.'TransID='.$TransID.BAOFOO_FI_MARK_PAGE
				.'Result='.$Result.BAOFOO_FI_MARK_PAGE
				.'ResultDesc='.$resultDesc.BAOFOO_FI_MARK_PAGE
				.'FactMoney='.$factMoney.BAOFOO_FI_MARK_PAGE
				.'AdditionalInfo='.$additionalInfo.BAOFOO_FI_MARK_PAGE
				.'SuccTime='.$SuccTime.BAOFOO_FI_MARK_PAGE
				.'Md5Sign='.$this->SignKey);
		 
			if($Md5Sign != $CalcSign){
				return array( "code" => "0012", "message" => "返回信息校验失败" );
			}
			
			if ($Result != "1") {
				return array( "code" => "0000", "message" => "支付失败" );
			} else if ($resultDesc != "01") {
				$descCode = "BAOFOO_FI_DESC_".$resultDesc;
				$descMsg;
				if (defined($descCode)) {
					$descMsg = constant($descCode);
				} else {
					$descMsg = "未知的错误";
				}
				return array( "code" => $resultDesc, "message" => $descMsg );
			} else {
				return array(
					"code" => "01",
					"message" => "校验成功",
					"TransID" => $TransID,
					"factMoney" => $factMoney/100,
					"additionalInfo" => $additionalInfo,
					"SuccTime" => $SuccTime
					
				);
			}
		}

		/*
		 * 查询不确定订单
		 * @param $TransID 支付订单ID
		 * 返回
		 * array (
		 *   "code" => "", // 返回码   01为成功
		 *   "message" => "" // 返回消息
		 *   "TransID" => "2014010101010101", // 订单ID
		 *   "CheckResult" => "N", // Y：成功 F：失败 P：处理中 N：没有订单
		 *   "succMoney" => "0", // 成功金额（元）
		 *   "SuccTime" => "20141021113841" // 订单成功时间
		 * )
		 */
		function query($TransID) {
		
			if (empty($TransID)) {
				return array( "code" => "0012", "message" => "查询订单号不能为空" );
			}

			$postData = array(
				"MemberID" => $this->MemberId,
				"TerminalID" => $this->TerminalId,
				"TransID" => $TransID
			);
		
			$Md5Sign = md5(
				$postData["MemberID"].BAOFOO_FI_MARK_PAY
				.$postData["TerminalID"].BAOFOO_FI_MARK_PAY
				.$postData["TransID"].BAOFOO_FI_MARK_PAY
				.$this->SignKey);
				
			$postData["Md5Sign"] = $Md5Sign;
			
			$response = $this->postHttpRequest(BAOFOO_FI_QUERY, $postData);

			$result = explode(BAOFOO_FI_MARK_PAY, $response);
			
			if (sizeof($result) < 2) {
				return array( "code" => "999", "message" => $result[0] );
			} else {
				$idx = 2;
				$orderInfo =  array(
					"TransID" => $result[$idx++],
					"CheckResult" => $result[$idx++],
					"succMoney" => $result[$idx++] / 100,
					"SuccTime" => $result[$idx++],
					"Md5Sign" => $result[$idx++]
				);
				
				$CalcSign = md5(
					$this->MemberId.BAOFOO_FI_MARK_PAY
					.$this->TerminalId.BAOFOO_FI_MARK_PAY
					.$orderInfo["TransID"].BAOFOO_FI_MARK_PAY
					.$orderInfo["CheckResult"].BAOFOO_FI_MARK_PAY
					.$orderInfo["succMoney"].BAOFOO_FI_MARK_PAY
					.$orderInfo["SuccTime"].BAOFOO_FI_MARK_PAY
					.$this->SignKey
				);
				
				if ($CalcSign == $orderInfo["Md5Sign"]) {
					$orderInfo["code"] == "01";
					return $orderInfo;
				} else {
					return array( "code" => "999", "message" => "查询结果MD5校验失败" );
				}
			}
		}
		
		/*
		 * 模拟POST请求
		 * @Param $sendUrl 请求地址
		 * @Param $transerXml 需要发送的参数
		 * 返回 xml形式的数据
		 */
		private function postHttpRequest($sendUrl, $postData) {

			if (empty($this->MemberId)) {
				return array( "code" => "0012", "message" => "会员号不能为空" );
			}

			if (empty($this->TerminalId)) {
				return array( "code" => "0012", "message" => "终端号无效" );
			}

			if (empty($this->SignKey)) {
				return array( "code" => "0012", "message" => "秘钥不能为空" );
			}
				//var_dump($postData);

			$context = array(
				'http' => array(
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($postData)
				)
			);

			try {
				$streamPostData = stream_context_create($context);
				$httpResult = file_get_contents((($this->TestMode?BAOFOO_FI_URL_TEST:BAOFOO_FI_URL_REAL).$sendUrl), false, $streamPostData);
				return $httpResult;
			} catch(Exception $e) {
				return "远程请求故障";
			}
		}
	}
?>