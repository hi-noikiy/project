<?php
/**
 * 满天星接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
final class MtxUser {
	/** @var string $apiUrl 接口地址  */
	private $apiUrl = "http://member.mvtapi.com:8310/cmtspay/services/payapi?wsdl";
	/** @var string $partnerCode 合作商代码  */
	private $partnerCode  = '';
	/** @var string $partnerKey 合作商密码  */
	private $partnerKey   = '';
	
	/** @var simlpXML $xml simpleXML 解析类对象  */
	private $xml = null;   // simpleXML 解析类
	
	private $registry  = null;
	
	private $mtxLog = null;

	/**
	 * 构造函数
	 * 
	 * @param registry $registry 全局储存注册对象
	 */	
	public function __construct($config) {
		$this->xml = new SimpleXML();

		// print_r($config);
		$this->partnerCode = $config['appCode'];
		$this->partnerKey = $config['appPwd'];
	}

	/**
	 * 1.17修改会员卡密码接口
	 */
	public function updatePassWord($data){

		if ($data['newPassWord'] == '888888' || $data['newPassWord'] == '666666') {
			return array(
				'ResultCode' => '100500',
				'ResultMsg' => '特殊限制，666666或888888不能作为新密码'
			);
		}

		$result = $this->__updatePassWord($data);

		if (isset($result['UpdatePassWordReturn'])) {
			return $result['UpdatePassWordReturn'];
		}
		else {
			return array();
		}
	}
	
	
	/**
	 * 网络请求xml解析
	 * 
	 * @param string $methon 访问方法
	 * @param array $parameter 参数
	 * 
	 * @return array 数据
	 */
	private function post($methon, $parameter) {
		$xml_data = $this->getSoapRespone($this->apiUrl, $methon, $parameter);
		
		$ret = array();
		if ( !empty($xml_data) ) {
			if (substr($xml_data, 0, 5) != '<?xml') {
				$xml_data = '<?xml version="1.0" encoding="utf-8"?>
				<' . $methon . 'Result xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
				'.$xml_data.'</' . $methon . 'Result>';
			}
			$xml = \simplexml_load_string($xml_data);
			$ret =  $this->xml->xml2array($xml);
		}
		return $ret;
	}
	/**
	 * 网络通讯
	 * 
	 * @param string $url 地址
	 * @param string $methon 访问方法
	 * @param array $para 参数
	 * 
	 * @return object / null
	 */
	private function getSoapRespone($url,$methon,$para)
	{
		$client = new \SoapClient($url, array('connection_timeout' => 60));
	
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8      = false;
		$client->xml_encoding     = 'utf-8';
		
		try {
			$result = $client->$methon($para);
			if (is_soap_fault($result)) {
				return null;
			}
			else {
				# @todo debug
				if ($this->debug) {
					$resultString = $methon . 'Result';
					return $result->$resultString->any;
				}
				return $result->return;
			}
		}
		catch (Exception $e) {
			return null;
		}
	}
	
	/**
	 * 校验信息的生成方法
	 * 
	 * @param array $para_list 参数名 => 值
	 * 
	 * @return string 校验信息
	 */
	private function getMd5Str($param = array()) {
		$str_long = '';
		unset($param['validateKey']);
		
		if (is_array($param) && !empty($param))
		{
			foreach ($param as $k => $v) 
			{
				$str_long.=$v;				
			}
		}
		$str_long .= $this->partnerKey;
		
		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return strtolower(substr(md5(strtolower($str_long)), 8,16));
	}
	
	
	# ---- EndRegion - 基础函数 ---- #
	
	
	# ---- Region - 满天星会员接口 ---- #
	
  	/** 
  	 * 1.1 会员卡充值接口
	 */
	public function memberCharge($data) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['cinemaCode'];
		$param['cardId'] = $data['loginNum'];
		$param['passWord'] = $data['passWord'];
		$param['price'] = $data['chargeAmount'];
		$param['partnerId'] = '';
		$param['mobilePhone'] = '';
		$param['validateKey'] = $this->getMd5Str($param);
		$param['traceMemo'] = '充值'.$param['price'] ;
		$ret =  $this->post('cardRecharge', $param);
		$ret=$ret['CardRechargeReturn'];
		$ret['Message']=$ret['ResultMsg'];
		if($ret['ResultCode']==0){
			$ret['BasicBalance']=$ret['Balance'];
			$ret['DonateBalance']=0;
			$ret['IntegralBalance']=$ret['Score'];
		}
		return $ret;
	}
	
	/**
	 * 1.2 会员卡调积分接口
	 */
	private function __updateScore($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['passWord'] = $data['passWord'];
		$param['score'] = $data['score'];
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('updateScore', $param);
		return $ret;
	}

	/**
	 * 1.3 查询会员卡交易记录接口
	 */
	public function queryMemberFlowInfo($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['cinemaCode'];
		$param['cardId'] = $data['loginNum'];
		$param['mobilePhone'] = '';
		$param['passWord'] = $data['passWord'];
		$param['startDate'] = $data['startDate'];
		$param['endDate'] = $data['endDate'];	
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('getCardTraceRecord', $param);
		$ret=$ret['GetCardTraceRecordReturn'];
		$ret['Message']=$ret['ResultMsg'];
		if(!empty($ret['CardTraceRecords']['CardTraceRecord'])){
			foreach ($ret['CardTraceRecords']['CardTraceRecord'] as $k=>$v){
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['MerchantName']=$v['CinemaName'];
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['TransactionAmount']=$v['Price'];
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['TransactionTime']=$v['TraceDate'].':'.$v['TraceTime'];
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['FlowType']=$v['TraceTypeName'];
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['ChannelType']=$v['UserCode'];
				$ret['CardTraceRecords']['CardTraceRecord'][$k]['TransactionNo']=$v['TraceNo'];
			}
			$ret['TransFlowVOs']['TransFlowVO']=$ret['CardTraceRecords']['CardTraceRecord'];
			unset($ret['CardTraceRecords']['CardTraceRecord']);
		}
		return $ret;
	}
	
	/**
	 * 1.4 loginCard会员卡登录接口
	 */
	public function verifyMemberLogin($arr) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $arr['cinemaCode'];
		$param['cardId'] = $arr['loginNum'];
		$param['passWord'] = $arr['password'];
		$param['partnerId'] = '';
		$param['mobilePhone'] = '';
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('loginCard', $param);
		$ret=$ret['LoginCardReturn'];
		$ret['Message']=$ret['ResultMsg'];
		if($ret['ResultCode']==0){
			$ret['CardNum']=$ret['CardId'];
			$ret['LevelCode']=$ret['AccLevelCode'];
			$ret['LevelName']=$ret['AccLevelName'];
			$ret['CardStatus']=$ret['AccStatus'];
			$ret['BasicBalance']=$ret['AccBalance'];
			$ret['DonateBalance']=0;
			$ret['IntegralBalance']=$ret['AccIntegral'];
			$ret['BusinessCode']=$ret['PlaceNo'];
			$ret['BusinessName']=$ret['CinemaName'];
			$ret['CreditNum']=$ret['IdNnumber'];
			$ret['ExpirationTime']=strtotime($ret['ExpirationTime']);
			$ret['MobileNum']=$ret['PhoneNumber'];
			$ret['Birthday']=$ret['Birthday'];
			$ret['UserName']=$ret['ConnectName'];
			$ret['Sex']=$ret['Sex'];
		}
		return $ret;
	}
	
	/**
	 * 1.5会员卡支付、预算接口
	 */
	public function memberConsume($data) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['cinemaCode'];
		if (isset($data['partnerId'])) {
			$param['partnerId'] = $data['partnerId'];
		}
		else {
			$param['partnerId'] = '';
		}
		$param['cardId'] = $data['loginNum'];
		if (isset($data['mobilePhone'])) {
			$param['mobilePhone'] = $data['mobilePhone'];
		}
		else {
			$param['mobilePhone'] = '';
		}
		$param['passWord'] = $data['lmsPassword'];
		$name='cardPay';
		if(!empty($data['sellcinemaCode'])){
			$name='chainCardPay';
			$param['sellPlaceNo'] = $data['sellcinemaCode'];
		}
		$param['traceTypeNo'] = '01';
		$param['oldPrice'] = $data['oldPrice'];
		$param['tracePrice'] = $data['tracePrice'];
		$param['discount'] = 10;
		$param['featureNo'] = $data['featureNo'];
		$param['filmNo'] = $data['filmNo'];
		$param['ticketNum'] = $data['ticketNum'];
		
		$param['validateKey'] = $this->getMd5Str($param);
		$param['traceMemo'] = $data['traceMemo'];
		
		$ret =  $this->post($name, $param);
		$restr=ucfirst($name).'Return';
		$ret=$ret[$restr];
		$ret['Message']=$ret['ResultMsg'];
		$ret['TransactionNo']=$ret['GroundTradeNo'];
		return $ret;
	}
	/**
	 * 1.6会员卡退费、冲费接口
	 */
	public function memberTransactionCancel($data) {

		$name='cardPayBack';
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['cinemaCode'];
		$param['partnerId'] = rand(10000,99999) . time();
		$param['cardId'] = $data['cardId'];
		$param['mobilePhone'] = '';
		$param['passWord'] = $data['passWord'];
		if(!empty($data['sellcinemaCode'])){
			$name='chainCardPayBack';
			$param['sellPlaceNo'] = $data['sellcinemaCode'];
		}
		$param['traceType'] = '01';

		$arrayTraceNo = explode(',', $data['traceNo']);

		$param['traceNo'] = $arrayTraceNo[0];
		$param['tracePrice'] = $data['tracePrice'];
		$param['price'] = $data['price'];
		
		$param['validateKey'] = $this->getMd5Str($param);
		$param['traceMemo'] = $data['traceMemo'] ? $data['traceMemo'] :'会员卡退款'.$param['price'] ;

		// print_r($param);
		$ret =  $this->post($name, $param);
		$restr=ucfirst($name).'Return';
		$ret=$ret[$restr];

		$ret['Message'] = $ret['ResultMsg'];
		return $ret;
	}
	
	/**
	 * 1.7获取影院会员卡类型接口
	 */
	private function __getMemberType($data) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('getMemberType', $param);
		return $ret;
	}
	
	/**
	 * 1.8会员卡开户接口
	 */
	public function __registerMember($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['passWord'] = $data['passWord'];
		$param['mobilePhone'] = $data['mobilePhone'];
		$param['idNum'] = $data['idNum'];
		$param['validateKey'] = $this->getMd5Str($param);
		$param['memberName'] = $data['memberName'];
		$param['balance'] = $data['balance'];
		$param['score'] = $data['score'];
		$param['memberTypeNo'] = $data['memberTypeNo'];
		$ret =  $this->post('registerMember', $param);
		return $ret;
	}
	
	/**
	 * 1.9 获取影院会员卡级别接口
	 */
	public function getCardType($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['cinemaCode'];;
		$param['validateKey'] = $this->getMd5Str($param);	
		$ret =  $this->post('getCardType', $param);
		$ret = $ret['GetCardTypeReturn'];
		if($ret['ResultCode'] == 0){
			$newArray = '';
			foreach ($ret['MemberTypes']['MemberType'] as $key => $value) {
				$newArray[$key]['MemberType'] = $value['MemberType'][0];
				$newArray[$key]['MemberTypeName'] = $value['MemberTypeName'];
			}
			$ret['MemberTypes'] = $newArray;
		}
		// print_r($ret);
		return $ret;
	}
	
	/**
	 * 1.10修改会员卡信息接口
	 */
	private function __updateCardInfo($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['mobilePhone'] = $data['mobilePhone'];
		$param['passWord'] = $data['passWord'];
		$param['idNum'] = $data['idNum'];
		$param['birthday'] = $data['birthday'];
		$param['validateKey'] = $this->getMd5Str($param);
		$param['memberName'] = $data['memberName'];
		$param['connectName'] = $data['connectName'];
		$param['address'] = $data['address'];
		$param['sex'] = $data['sex'];
		$param['married'] = $data['married'];
		$param['kind'] = $data['kind'];
		$ret =  $this->post('updateCardInfo', $param);
		return $ret;
	}
	
	/**
	 * 1.11会员卡对账接口
	 */
	private function __cardAccount($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['accountType'] = $data['accountType'];
		$param['startDate'] = $data['startDate'];
		$param['endDate'] = $data['endDate'];
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('cardAccount', $param);
		return $ret;
	}
	
	/**
	 * 1.14跨影院折扣查询
	 */
	private function __chainGetDiscount($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['sellPlaceNo'] = $data['sellPlaceNo'];
		$param['featureNo'] = $data['featureNo'];
		$param['featureDate'] = $data['featureDate'];
		$param['featureTime'] = $data['featureTime'];		
		$param['validateKey'] = $this->getMd5Str($param);		
		$ret =  $this->post('chainGetDiscount', $param);
		return $ret;
	}
	
	/**
	 * 1.15折扣查询
	 */
	private function __getDiscount($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['featureNo'] = $data['featureNo'];
		$param['featureDate'] = $data['featureDate'];
		$param['featureTime'] = $data['featureTime'];	
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('getDiscount', $param);
		return $ret;
	}
	
	/**
	 * 1.17修改会员卡密码接口
	 */
	public function modifyMemberPassword($data) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['cinemaCode'];
		$param['cardId'] = $data['loginNum'];
		$param['passWord'] = $data['oldPassword'];
		$param['newPassWord'] = $data['newPassword'];
		$param['partnerId'] = '';
		$param['mobilePhone'] = '';
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('updatePassWord', $param);
		$ret=$ret['UpdatePassWordReturn'];
		$ret['Message']=$ret['ResultMsg'];
		return $ret;
	}
	
	/**
	 * 1.18会员卡支付、预算卖品接口
	 */
	private function __cardSPPay($data) {
		$param['partnerCode'] = $this->partnerCode;
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['passWord'] = $data['passWord'];
		$param['traceTypeNo'] = $data['traceTypeNo'];
		$param['oldPrice'] = $data['oldPrice'];
		$param['tracePrice'] = $data['tracePrice'];
		$param['discount'] = $data['discount'];
		$param['SPNo'] = $data['featureNo'];
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('cardSPPay', $param);
		return $ret;
	}
	
	/**
	 * 1.19会员卡卖品冲费接口
	 */
	private function __cardSPPayBack($data) {
		$param['partnerCode'] = $this->partnerCode;	
		$param['placeNo'] = $data['placeNo'];
		$param['cardId'] = $data['cardId'];
		$param['passWord'] = $data['passWord'];
		$param['traceNo'] = $data['traceNo'];
		$param['tracePrice'] = $data['tracePrice'];
		$param['price'] = $data['price'];	
		$param['validateKey'] = $this->getMd5Str($param);
		$ret =  $this->post('cardSPPayBack', $param);
		return $ret;
	}
	

}