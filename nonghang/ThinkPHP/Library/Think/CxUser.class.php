<?php
/**
 * 辰星接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
class CxUser{
	private $apiUrl = "http://tsp.oristartech.cn:8080/tsp-ws/services/tsp/cinema?wsdl";
	/** @var string $pAppCode 应用编码  */
	private $pAppCode  = '';
	/** @var string $pAppPwd 应用密码  */
	private $pAppPwd   = '';
	/** 是否压缩*/
	private $pCompress   = '0';
	
	/** @var simlpXML $xml simpleXML 解析类对象  */
	private $xml = null;   // simpleXML 解析类

	/**
	 * 构造函数
	 * 
	 * @param registry $registry 全局储存注册对象
	 */	
	public function __construct($config) {
		$this->xml = new SimpleXML();
		$this->pAppCode = $config['appCode'];
		$this->pAppPwd = $config['appPwd'];
	}
	/**
	 * 4.2.4 QueryAvailCinema查询会员卡可使用影院--------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 * 
	 */
	public function queryAvailCinema($pCinemaCode,$pLoginNum) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $pCinemaCode;	
		$param['pLoginNum'] = $pLoginNum;		
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryAvailCinema', $param);
		return $ret;
	}
	
	
	/**
	 *4.2.5 QueryMemberFlowInfo查询会员卡交易记录------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function queryMemberFlowInfo($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $para_list['cinemaCode'];
		$param['pLoginNum'] = $para_list['loginNum'];
		$param['pStartDate'] = $para_list['startDate'];
		$param['pEndDate'] = $para_list['endDate'];
		$param['pPageSize'] = $para_list['pageSize'];
		$param['pPageNum'] = $para_list['pageNum'];
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('QueryMemberFlowInfo', $param);
		return $ret;
	}
	
	/**
	 * 4.2.8 MemberConsume会员消费--------辰星
	 */
	public function memberConsume($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $para_list['cinemaCode'];	
		$param['pLoginNum'] = $para_list['loginNum'];	
		$param['pLmsPassword'] = $para_list['lmsPassword'];
		$param['pChannelType'] = $para_list['channelType'];
		$param['pAmount'] = $para_list['amount'];
		$param['pOrderCode'] = $para_list['orderCode'];
		$param['pTransactionNo'] = $para_list['transactionNo'];		
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('MemberConsume', $param);
		return $ret;
	}
	/**
	 * 4.2.7 MemberCharge会员充值-------辰星
	 */
	public function memberCharge($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $para_list['cinemaCode'];	
		$param['pLoginNum'] = $para_list['loginNum'];	
		$param['pChargeType'] = $para_list['chargeType'];;
		$param['pChannelType'] = $para_list['channelType'];
		$param['pChargeAmount'] = $para_list['chargeAmount'];
		$param['pOrderCode'] = $para_list['orderCode'];
		$param['pTransactionNo'] = $para_list['transactionNo'];		
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('MemberCharge', $param);
		if($ret['ResultCode']==0){
			$member=$this->queryMemberInfo($para_list['cinemaCode'],$para_list['loginNum']);
			if($member['ResultCode']=0){
				$ret['BasicBalance']=$member['BasicBalance'];
				$ret['DonateBalance']=$member['DonateBalance'];
				$ret['IntegralBalance']=$member['IntegralBalance'];
			}
		}
		return $ret;
	}
	/**
	 * 4.2.3 ModifyMemberPassword会员密码修改-------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 * 
	 */
	public function modifyMemberPassword($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $para_list['cinemaCode'];	
		$param['pLoginNum'] = $para_list['loginNum'];	
		$param['pOldPassword'] = $para_list['oldPassword'];
		$param['pNewPassword'] = $para_list['newPassword'];
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('ModifyMemberPassword', $param);
		return $ret;
	}
	
	/**
	 * 4.2.9 MemberTransactionCancel会员交易撤销--------辰星
	 */
	public function memberTransactionCancel($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $para_list['cinemaCode'];	
		$param['pOldTransactionNo'] = $para_list['oldTransactionNo'];
		$param['pTransactionNo'] = $para_list['transactionNo'];
		$param['pChannelType'] = $para_list['channelType'];	
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('MemberTransactionCancel', $param);
		return $ret;
	}


	/**
	 *4.2.6 QueryMemberLevel--------辰星
	 */
	public function getCardType($data) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $data['cinemaCode'];	
		$param['pCompress'] = $this->pCompress;		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('QueryMemberLevel', $param);


		if($ret['ResultCode'] == 0){
			$newArray = '';
			foreach ($ret['MemberLevels']['MemberLevel'] as $key => $value) {
				$newArray[$key]['MemberType'] = $value['LevelCode'];
				$newArray[$key]['MemberTypeName'] = $value['LevelName'];
			}
			$ret['MemberTypes'] = $newArray;
			unset($ret['MemberLevels']);
		}

		// print_r($ret);

		return $ret;
	}
	
	# ---- Region - 基础函数 ---- #
	
	/**
	 * 网络请求xml解析
	 * 
	 * @param string $methon 访问方法
	 * @param array $parameter 参数
	 * 
	 * @return array 数据
	 */
	private function post($methon, $parameter) {
		/*wlog('方法：'.$methon,'userlog');
		wlog('参数：'.$this->xml->xml_encode($parameter),'userlog');*/
		$xml_data = $this->getSoapRespone($this->apiUrl, $methon, $parameter);		
		$ret = array();
		if ( !empty($xml_data) ) {
			if (substr($xml_data, 0, 5) != '<?xml') {
				$xml_data = '<?xml version="1.0" encoding="utf-8"?>
				<' . $methon . 'Result xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
				'.$xml_data.'</' . $methon . 'Result>';
			}
			$xml = @simplexml_load_string($xml_data);
			//wlog('返回：'.$xml_data,'userlog');
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
		$client = new \SoapClient($url);
	
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
			$this->log->write($e->getMessage());
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
	private function getMd5Str($para_list = array())
	{
		$str_long = '';
		unset($para_list['VerifyInfo']);
		if (is_array($para_list) && !empty($para_list))
		{
			foreach ($para_list as $k => $v) 
			{
				$str_long.=$v;				
			}
			$str_long .= $this->pAppPwd;
		}		
		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return md5(strtolower($str_long));
	}
	
	
	/**
	 * 4.2.1 verifyMemberLogin会员卡登录接口-----------辰星
	 */
	public function verifyMemberLogin($arr) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $arr['cinemaCode'];
		$param['pLoginNum'] = $arr['loginNum'];
		$param['pLmsPassword'] = md5( $arr['password']);
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);		
		$ret =  $this->post('VerifyMemberLogin', $param);		
		if($ret['ResultCode']==0){
			$member=$this->queryMemberInfo($arr['cinemaCode'],$arr['loginNum']);
			if($member['ResultCode']==0){
				$ret['CardNum']=$member['CardNum'];
				$ret['LevelCode']=$member['LevelCode'];
				$ret['LevelName']=$member['LevelName'];
				$ret['CardStatus']=$member['CardStatus'];
				$ret['BasicBalance']=$member['BasicBalance'];
				$ret['DonateBalance']=$member['DonateBalance'];
				$ret['IntegralBalance']=$member['IntegralBalance'];
				$ret['BusinessCode']=$member['BusinessCode'];
				$ret['BusinessName']=$member['BusinessName'];
				$ret['CreditNum']=$member['CreditNum'];
				$ret['ExpirationTime']=0;
				$ret['MobileNum']=$member['MobileNum'];
				$ret['Birthday']=$member['Birthday'];
				$ret['UserName']=$member['UserName'];
				$ret['Sex']=$member['Sex'];
			}
		}
		return $ret;
	}
	
	/**
	 * 4.2.2 queryMemberInfo会员资料查询-------------------辰星
	 */
	public function queryMemberInfo($pCinemaCode,$pLoginNum) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $pCinemaCode;
		$param['pLoginNum'] = $pLoginNum;
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);		
		$ret =  $this->post('QueryMemberInfo', $param);
		return $ret;
	}
	
	
	# ---- EndRegion - 辰星会员接口 ----#
}