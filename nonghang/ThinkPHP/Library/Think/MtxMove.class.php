<?php
/**
 * 满天星接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
class MtxMove{
	/** @var string $apiUrl 接口地址  */
	private $apiUrl = "http://ticket.mvtapi.com:8760/ticketapi/services/ticketapi?wsdl";
	// private $apiUrl = "http://wap.zmaxfilm.net:8181/Soap/mtx?wsdl";
	/** @var string $pAppCode 应用编码  */
	private $pAppCode  = '';
	/** @var string $pAppPwd 应用密码  */
	private $pAppPwd   = '';
	/** @todo debug  */
	private $debug   = false;
	
	/** @var string $pTokenID 令牌ID  */
	private $pTokenID = ''; // TokenID
	/** @var string $Token 令牌  */
	private $Token = ''; // Token
	
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
		$this->pAppCode = $config['appCode'];
		$this->pAppPwd = $config['appPwd'];
		$this->xml = new \Think\SimpleXML();
		$this->pTokenID = 1829;
		$this->Token = 'abcdef';
	}
	
	
	/**
	 * 实时解锁座位
	 * 
	 * @param string orderNo 订单号
	 * 
	 * @return string ResultCode 返回结果号
	 * 
	 * @author ZhuZhenguo 2014/04/11 16:38
	 */
	public function unLock($orderNo) {
		$result = $this->__unLockOrderCenCin(array('pOrderNO' => $orderNo));
		
		return $result['ResultCode'];
	}
	/**
	 * 4.11 查询定单的售票结果
	 *
	 * @param array $para_list
	 * {
	 * 	string pSerialNum 合作商方定单号（流水号）
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编号,
	 * 	string ValidCode 订单验证码,
	 * 	string OrderStatus 订单状态[4.已支付,6.支付失败,7.已退票,8.已打票,9.地面售票成功]
	 * }
	 */
	public function queryOrderStatus($para_list = array()) {

		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $para_list['cinemaCode'];
	
		$param['pSerialNum']       = $para_list['serialNum'];
	
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetOrderStatus', $param);
		//print_r($ret);
		$ret = $ret['GetOrderStatusResult'];
		$ret['VerifyCode']=$ret['ValidCode'];
		$ret['PrintNo']=0;
		if($ret['OrderStatus']==9||$ret['OrderStatus']==8){ //成功或出票
			$ret['OrderStatus']=0;
		}elseif($ret['OrderStatus'] == 4 || $ret['OrderStatus']==0 || $ret['OrderStatus']==6){ //已支付或支付失败
			$ret['OrderStatus']=1;
		}
		elseif($ret['OrderStatus']==7){ //已退票
			$ret['OrderStatus']=2;
		}
		return $ret;
	}
	/**
	 * 卖常规票(带座位票)
	 *
	 * @param array $para{
	 * 	string FeatureAppNo 排期编号,
	 * 	string SerialNum 合作商方定单号（流水号）,
	 * 	string Printpassword 取票密码,
	 * 	float Balance 要支付的金额,
	 * 	string PayType 付费类型[0.其他 70.会员卡支付],
	 * 	string RecvMobilePhone接收二维码手机号码,
	 * 	string SendType 接收二维码方式[移动号码 100.短信,300.彩信二维码,301.NOKIA长短信二维码; 非移动号码 400.信息机发送短信],
	 * 	string PayResult 支付结果 [0.成功,1.失败],
	 * 	bool IsCmtsPay 漫天星负责扣款,
	 * 	bool IsCmtsSendCode 满天星负责发送二维码,
	 * 	string PayMobile 支付手机号[如需满天星支付],
	 * 	string BookSign [0.全额支付,1.预定金支付],
	 * 	float Payed 商城已支付金额 Balance+Payed=票价,
	 * 	string SendModeID 漫天星二维码模板编号,
	 * 	string PaySeqNo 影院会员卡支付交易流水号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编码,
	 * 	string ValidCode 订单验证码
	 * }
	 */
	public function sellTicket($para) {
		return $this->__sellMtxTicket($para);
	}
	
	/**
	 * 退票
	 *
	 * @param array $para_list {
	 * 	string pOrderNo 订单号
	 * 	string pDesc 退票原因
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	public function backTicket($para) {
		return $this->__backTicket($para);
	}
	
	/**
	 * 4.11 查询定单的售票结果
	 *
	 * @param array $para
	 * {
	 * 	string pSerialNum 合作商方定单号（流水号）
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编号,
	 * 	string ValidCode 订单验证码,
	 * 	string OrderStatus 订单状态[4.已支付,6.支付失败,7.已退票,8.已打票,9.地面售票成功]
	 * }
	 */
	public function getOrderStatus($serialNum) {
		return $this->__getOrderStatus(array('pSerialNum' => $serialNum));
	}
	
	/**
	 * 4.20 合作商打票
	 *
	 * @param array $para_list {
	 * 	string pOrderNo 订单号,
	 * 	string pValidCode 验证码,
	 * 	string pRequestType 请求类型[0.查询订单信息,1.通知打票]
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单号,
	 * 	string OrderStatus 订单状态,
	 * 	string OrderDate 订单日期,
	 * 	string OrderTime 订单时间,
	 * 	string FeatureDate 订单日期,
	 * 	string FeatureTime 订单时间,
	 * 	string FilmName 影片名称,
	 * 	string HallName 影厅名,
	 * 	string PrintType 打印类型[0.满天星打票, 1.合作商打票, 2.未打票],
	 * 	array SeatInfos {
	 * 		array SeatInfo [{
	 * 			string SeatRow 座位行,
	 * 			string SeatCol 座位列,
	 * 			string SeatPieceName 座区名,
	 * 			string TicketNo 票号
	 * 		}]
	 *  }
	 * }
	 */
	public function appPrintTicket($para) {
		return $this->__appPrintTicket(array('pOrderNO' => $para['pOrderNo'], 'pValidCode' => $para['pValidCode'], 'pRequestType' => $para['pRequestType']));
	}
	

	
	/**
	 * 应用商按照协议，设置排期结算价格
	 *
	 * @param array $data {
	 * 	string pFeatureAppNo 排期编号,
	 * 	string pAppPric 票面价,
	 *  string pBalancePric 确认票面价
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	public function setFeaturePrice($data = array()) {
		return $this->__setFeaturePrice($data);
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
	private function post($methon, $parameter)
	{
		# @todo debug
		if ($this->debug) {
			unset($parameter['pTokenID']);
		}
		
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
		$Tip = true;
		$i = 0;
		while ($Tip && $i<=3) {
			try {

				$client = new \SoapClient($url, array('connection_timeout' => 60));
			
				$client->soap_defencoding = 'utf-8';
				$client->decode_utf8      = false;
				$client->xml_encoding     = 'utf-8';
			
				$result = $client->$methon($para);
				//dump($result);
				if (is_soap_fault($result)) {
					delDirAndFile(SOAP_CACHE_PATH);
					$i++;
					//trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
				}
				else
				{
					# @todo debug
					if ($this->debug) {
						$resultString = $methon . 'Result';
						return $result->$resultString->any;
					}
					return $result->return;
					$Tip = false;
				}
			}
			catch (Exception $e) {
				delDirAndFile(SOAP_CACHE_PATH);
				$i++;
			}
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
		unset($para_list['pVerifyInfo']);
		# @todo debug
		if ($this->debug) {
			unset($para_list['pTokenID']);
		}
		
		if (is_array($para_list) && !empty($para_list))
		{
			foreach ($para_list as $k => $v) 
			{
				$str_long.=$v;				
			}
		}
		# @todo debug
		if ($this->debug) {
			$str_long .= $this->pAppPwd;
		}
		else {
			$str_long .= $this->Token . $this->pAppPwd;
		}
		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return strtolower(substr(md5(strtolower($str_long)), 8,16));
	}
	
	
	# ---- EndRegion - 基础函数 ---- #
	
	# ---- Region - 满天星接口 ---- #
	
	/**
	 * 4.0 获取令牌
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string TokenID 令牌Id,
	 * 	string Token 令牌,
	 * 	string TimeOut 令牌生命周期（单位：秒）
	 * }
	 */
	
	private function __getToken() {
		$param = array('pAppCode' => $this->pAppCode);
		
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetToken', $param);
		
		return $ret['TokenResult'];
	}
	
	/**
	 * 4.1  获取电影院信息
	 *  
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array Cinemas {
	 * 		array Cinema [{
	 * 			string PlaceNo 影院编号,
	 * 			string PlaceName 影院名称,
	 * 			string CityNo 影院所在地区编号,
	 * 			string CreateDate 电影院授权日期,
	 * 			string State 影院状态[1 正常, 其他为异常]
	 * 		}]
	 * 	}
	 * }
	 */
	public function getCinema() {
		$param = array('pAppCode' => $this->pAppCode);
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetCinema', $param);
		return $ret['GetCinemaResult'];
		
	}
	
	/**
	 * 4.2  获取对应影院对应日期的排期
	 * 
	 * @param 
	 * 	string pCinemaID 电影院编号
	 * 	string pPlanDate 获取排期的日期 格式(yyyy-mm-dd)
	 *
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array CinemaPlans {
	 * 		array CinemaPlan [{
	 * 			string FeatureNo 排期号，应用于会员卡接口,
	 * 			string HallName 影厅名称,
	 * 			string PlaceName 影院名称,
	 * 			string FeatureAppNo 排期编码,
	 * 			string FeatureDate 排期日期,
	 * 			string FeatureTime 排期时间,
	 * 			string TotalTime 排期结束时间,
	 * 			string PlaceNo 影院编号,
	 * 			string HallNo 影厅编号,
	 * 			string FilmNo 影片编号,
	 * 			string FilmName 影片名字,
	 * 			string AppPric 票价,
	 * 			string StandPric 标准价,
	 * 			string UseSign 可用性[0.可用,1.不可用,3.待审核],
	 * 			string SetClose 计划状态[0.未售,1.开售,2.截止,3.停售,5.统计,9.注销],
	 * 			string CopyType 拷贝制式,
	 * 			string CopyLanguage 拷贝语言,
	 * 			string HallSeats 影厅座位数,
	 * 			string AvailableSeats 剩余座位数
	 * 		}]
	 *  }
	 * } 
	 */
	public function getCinemaPlan($arr) {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pCinemaID'] = $arr['cinemaCode'];
		$param['pPlanDate']  = $arr['planDate'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetCinemaPlan', $param);
		$ret = $ret['GetCinemaPlanResult'];

		$newPlanArray = '';
		$newKey = 0;
		if($ret['ResultCode'] == 0){
			foreach ($ret['CinemaPlans']['CinemaPlan'] as $key => $value) {
				
				if($value['UseSign'] == 0 && $value['SetClose'] == 1){
					$newPlanArray[$newKey]['IsClose'] = 0;
				}else{
					$newPlanArray[$newKey]['IsClose'] = 1;
					// print_r($value);
				}

				$ret['CinemaPlans']['CinemaPlan'][$key]['StartTime'] = $value['FeatureDate'] . ' ' . $value['FeatureTime'] . ':00';

				$startTime = strtotime($value['FeatureDate'] . ' ' . $value['FeatureTime'] . ':00');
				$endTime = strtotime($value['FeatureDate'] . ' ' . $value['TotalTime'] . ':00');

				if($startTime > $endTime){
					$endTime = $endTime + 3600 * 24;
				}

				$newPlanArray[$newKey]['FeatureAppNo'] = $value['FeatureAppNo'];
				$newPlanArray[$newKey]['FeatureNo'] = $value['FeatureNo'];
				$newPlanArray[$newKey]['StartTime'] = $value['FeatureDate'] . ' ' . $value['FeatureTime'] . ':00';
				$newPlanArray[$newKey]['TotalTime'] = ($endTime - $startTime) / 60;
				$newPlanArray[$newKey]['FilmNo'] = $value['FilmNo'];
				$newPlanArray[$newKey]['FilmName'] = $value['FilmName'];
				$newPlanArray[$newKey]['HallNo'] = $value['HallNo'];
				$newPlanArray[$newKey]['CopyType'] = $value['CopyType'];
				$newPlanArray[$newKey]['CopyLanguage'] = $value['CopyLanguage'];
				$newPlanArray[$newKey]['LowestPrice'] = $value['ProtectPrice'];
				$newPlanArray[$newKey]['StandardPrice'] = $value['StandPric'];
				$newPlanArray[$newKey]['ListingPrice'] = $value['StandPric'];

				$newKey++;

			}
			unset($ret['CinemaPlans']);
			$ret['CinemaPlans'] = $newPlanArray;
		}


		return $ret;
	}
	
	/**
	 * 4.3  获取对应影院所有可读排期
	 * 
	 * @param array $para_list
	 * {
	 * 	string pCinemaID 电影院编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array CinemaPlans {
	 * 		array CinemaPlan [{
	 * 			string FeatureNo 排期号，应用于会员卡接口,
	 * 			string HallName 影厅名称,
	 * 			string PlaceName 影院名称,
	 * 			string FeatureAppNo 排期编码,
	 * 			string FeatureDate 排期日期,
	 * 			string FeatureTime 排期时间,
	 * 			string TotalTime 排期结束时间,
	 * 			string PlaceNo 影院编号,
	 * 			string HallNo 影厅编号,
	 * 			string FilmNo 影片编号,
	 * 			string FilmName 影片名字,
	 * 			string AppPric 票价,
	 * 			string StandPric 标准价,
	 * 			string UseSign 可用性[0.可用,1.不可用,3.待审核],
	 * 			string SetClose 计划状态[0.未售,1.开售,2.截止,3.停售,5.统计,9.注销],
	 * 			string CopyType 拷贝制式,
	 * 			string CopyLanguage 拷贝语言,
	 * 			string HallSeats 影厅座位数,
	 * 			string AvailableSeats 剩余座位数
	 * 		}]
	 *  }
	 * }
	 */
	public function getCinemaAllPlan($pCinemaID) {
		$param = array('pAppCode' => $this->pAppCode);
		
		$param['pCinemaID'] = $pCinemaID;
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetCinemaAllPlan', $param);

		return $ret;
	}
	
	/**
	 * 4.4  获取影院对应的影厅信息
	 * 
	 * @param array $para_list
	 * {
	 * 	string pCinemaID 电影院编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array Halls {
	 * 		array Hall [{
	 * 			string HallNo 影厅编号,
	 * 			string HallName 影厅名称
	 * 		}]
	 * 	}
	 * }
	 */
	public function getHall($arr) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $arr['cinemaCode'];
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('GetHall', $param);
		$ret = $ret['GetHallResult'];
		// print_r($ret);

		$ret['Halls'] = $ret['Halls']['Hall'];
		unset($ret['Halls']['Hall']);
		
		//exit;
		return $ret;
	}
	
	/**
	 * 4.5  获取影厅对应的所有座位信息
	 * 
	 * @param array $para_list
	 * {
	 * 	string pCinemaID 电影院编号
	 * 	string pHallID 影厅编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array HallSites {
	 * 		array HallSite [{
	 * 			string SeatNo 座位编号,
	 * 			string SeatPieceNo 座区号,
	 * 			string GraphRow 屏幕行,
	 * 			string GraphCol 屏幕列,
	 * 			string SeatRow 坐行,
	 * 			string SeatCol 坐列
	 * 		}]
	 * 	}
	 * }
	 */
	public function getHallSite($data) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $data['cinemaCode'];
		$param['pHallID']   = $data['hallNo'];
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('GetHallSite', $param);

		$sectionInfo = S('GetHallSiteArea' . $param['pCinemaID'] . '_' . $param['pHallID']);
		if (empty($sectionInfo)) {
			$sectionInfo = $this->GetHallSiteArea($data);
			S('GetHallSiteArea' . $param['pCinemaID'] . '_' . $param['pHallID'], $sectionInfo, 7200);
		}

		if ($sectionInfo['ResultCode'] == 0) {
			foreach ($sectionInfo['HallSiteAreas']['HallSiteArea'] as $key => $value) {
				$sectionInfoArray[$value['SeatPieceNo']] = $value['SeatPieceName'];
			}
		}

		$newArray['ResultCode'] = $ret['GetHallSiteResult']['ResultCode'];
		foreach ($ret['GetHallSiteResult']['HallSites']['HallSite'] as $key => $value) {
			$newArray['ScreenSites']['ScreenSite'][$key]['SeatCode'] = $value['SeatNo'];
			$newArray['ScreenSites']['ScreenSite'][$key]['RowNum'] = $value['SeatRow'];
			$newArray['ScreenSites']['ScreenSite'][$key]['ColumnNum'] = $value['SeatCol'];
			$newArray['ScreenSites']['ScreenSite'][$key]['XCoord'] = $value['GraphRow'];
			$newArray['ScreenSites']['ScreenSite'][$key]['YCoord'] = $value['GraphCol'];
			$newArray['ScreenSites']['ScreenSite'][$key]['Status'] = 0;
			$newArray['ScreenSites']['ScreenSite'][$key]['HallNo'] = $data['hallNo'];
			$newArray['ScreenSites']['ScreenSite'][$key]['sectionId'] = $value['SeatPieceNo'];
			$newArray['ScreenSites']['ScreenSite'][$key]['sectionName'] = $sectionInfoArray[$value['SeatPieceNo']];
		}
		
		return $newArray;
	}


	public function GetHallSiteArea($data)
	{
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $data['cinemaCode'];
		$param['pHallID']   = $data['hallNo'];
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);


		$ret =  $this->post('GetHallSiteArea', $param);
		$ret = $ret['GetHallSiteAreaResult'];

		if ($ret['ResultCode'] == 0) {
			if (empty($ret['HallSiteAreas']['HallSiteArea'][0])) {
				$newArray = $ret['HallSiteAreas']['HallSiteArea'];
				unset($ret['HallSiteAreas']['HallSiteArea']);
				$ret['HallSiteAreas']['HallSiteArea'][0] = $newArray;
			}

		}
		
		return $ret;
	}
	
	/**
	 * 4.6  获取影厅对应的所有座区信息
	 * 
	 * @param array $para_list
	 * {
	 * 	string pCinemaID 电影院编号,
	 * 	string pHallID 影厅编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array HallSiteAreas {
	 * 		array HallSiteArea [{
	 * 			string SeatPieceNo 座区编号,
	 * 			string SeatPieceName 座区名称
	 * 		}]
	 * 	}
	 * }
	 */
	private function __getHallSiteArea($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pCinemaID'] = $para_list['pCinemaID'];
		$param['pHallID']       = $para_list['pHallID'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetHallSiteArea', $param);
		return $ret;
	}
	
	/**
	 * 4.7  获取对应排期的座位图的状态
	 * 
	 * @param array $para_list
	 * {
	 * 	string pFeatureAppNo 排期编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array PlanSiteStates {
	 * 		array PlanSiteState [{
	 * 			string SeatNo 座位编号,
	 * 			string SeatPieceNo 座区号,
	 * 			string GraphRow 屏幕行,
	 * 			string GraphCol 屏幕列,
	 * 			string SeatRow 坐行,
	 * 			string SeatCol 坐列,
	 * 			string SeatState 状态[-1.不可售,0.未售,1.售出,3.预定,4.选中,7.已锁定,9.验收],
	 * 			string SeatPieceName 座区名称
	 * 		}]
	 * 	}
	 * }
	 */
	public function getPlanSiteState($arr) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $arr['cinemaCode'];
		$param['pFeatureAppNo'] = $arr['featureAppNo'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetPlanSiteState', $param);
		// print_r($ret);
		$ret = $ret['GetPlanSiteStateResult'];

		foreach ($ret['PlanSiteStates']['PlanSiteState'] as $key => $value) {
			// $ret['PlanSiteStates']['PlanSiteState'][$key]['groupCode'] = 0;
			// $ret['PlanSiteStates']['PlanSiteState'][$key]['SeatNo'] = $value['SeatNo'] . '#' . $value['GraphRow'] . '#' . $value['GraphCol'];
		}

		if($ret['ResultCode'] == 0){
			$ret['PlanSiteState'] = $ret['PlanSiteStates']['PlanSiteState'];
		}

		unset($ret['PlanSiteStates']);
		// print_r($ret);
		return $ret;
	}
	
	/**
	 * 4.8  检查需要定票的座位状态情况，并定票锁定座位
	 * 
	 * @param array $para_list {
	 * 	string FeatureAppNo 排期编号,
	 * 	string SerialNum 合作商方定单号（流水号）,
	 * 	string PayType 付费类型[0.其他 70.会员卡支付],
	 * 	string RecvMobilePhone 接收二维码手机号码,
	 * 	array SeatInfos {
	 * 		array SeatInfo[{
	 * 			string SeatNo 影厅座位号[-1.自动选座,其他座位号],
	 * 			string TicketPrice 显示票价,
	 * 			string Handlingfee 服务费
	 * 		}]
	 * 	} 
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编号,
	 * 	array SeatInfos {
	 * 		array SeatInfo [{
	 * 			string SeatNo 锁定的座位编号,
	 * 			string TicketPrice 锁定的座位价格,
	 * 			string SeatRow 锁定的座位行,
	 * 			string SeatCol 锁定的座位列,
	 * 			string SeatPieceNo 锁定的座位座区号,
	 * 		}]
	 * 	}
	 * }
	 */
	private function __realCheckSeatState($para_list = array()) {
		$param['AppCode'] = $this->pAppCode;
		
		$param['FeatureAppNo'] = $para_list['FeatureAppNo'];
		$param['SerialNum'] = $para_list['SerialNum'];
		$param['SeatInfos'] = strval(count($para_list['SeatInfos']));
		$param['PayType'] = $para_list['PayType'];
		$param['RecvMobilePhone'] = strval($para_list['RecvMobilePhone']);
		
		$param['TokenID'] = $this->pTokenID;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		
		$param['SeatInfos'] = $this->xml->xml_encode($para_list['SeatInfos'],'utf-8','root','SeatInfo',false); 
		
		$param1['pXmlString'] = $this->xml->xml_encode($param,'utf-8','RealCheckSeatStateParameter');
		
		$ret =  $this->post('RealCheckSeatState', $param1);

		print_r($ret);
		return $ret;
	}
	
	/**
	 * 4.9   卖常规票(带座位票)
	 * 
	 * @param array $para_list
	 * {
	 * 	string FeatureAppNo 排期编号,
	 * 	string SerialNum 合作商方定单号（流水号）,
	 * 	string Printpassword 取票密码,
	 * 	float Balance 要支付的金额,
	 * 	string PayType 付费类型[0.其他 70.会员卡支付],
	 * 	string RecvMobilePhone接收二维码手机号码,
	 * 	string SendType 接收二维码方式[移动号码 100.短信,300.彩信二维码,301.NOKIA长短信二维码; 非移动号码 400.信息机发送短信],
	 * 	string PayResult 支付结果 [0.成功,1.失败],
	 * 	bool IsCmtsPay 漫天星负责扣款,
	 * 	bool IsCmtsSendCode 满天星负责发送二维码,
	 * 	string PayMobile 支付手机号[如需满天星支付],
	 * 	string BookSign [0.全额支付,1.预定金支付],
	 * 	float Payed 商城已支付金额 Balance+Payed=票价,
	 * 	string SendModeID 漫天星二维码模板编号,
	 * 	string PaySeqNo 影院会员卡支付交易流水号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编码,
	 * 	string ValidCode 订单验证码
	 * }
	 */
	private function __sellMtxTicket($para_list = array()) {
		
		$param['AppCode']         	    = $this->pAppCode;
		
		$param['FeatureAppNo'] 		    = $para_list['FeatureAppNo'];
		$param['SerialNum']      		    = $para_list['SerialNum'];
		$param['Printpassword']      	    = $para_list['Printpassword'];
		$param['Balance']      		   	= $para_list['Balance'];
		$param['PayType']      		    = $para_list['PayType'];
		$param['RecvMobilePhone']         = $para_list['RecvMobilePhone'];
		$param['SendType']      		    = $para_list['SendType'];
		$param['PayResult']      		    = $para_list['PayResult'];
		$param['IsCmtsPay']      		    = $para_list['IsCmtsPay'];
		$param['IsCmtsSendCode']          = $para_list['IsCmtsSendCode'];
		$param['PayMobile']      		    = $para_list['PayMobile'];
		$param['BookSign']      		   	= $para_list['BookSign'];
		$param['Payed']      		    	= $para_list['Payed'];
		$param['SendModeID']      		= $para_list['SendModeID'];
		$param['PaySeqNo']      		    = $para_list['PaySeqNo'];
		
		$param['TokenID'] = $this->pTokenID;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		
		$param1['pXmlString'] = $this->xml->xml_encode($param,'utf-8','SellTicketParameter');
		
		$ret =  $this->post('SellTicket', $param1);
				
		return $ret;
		
	}
	
	/**
	 * 4.10  获取排期座位统计数据
	 * 
	 * @param array $para_list
	 * {
	 * 	string pFeatureAppNo 排期编号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string TotalSeatAmount 排期座位总数量,
	 * 	string SeatToSellAmount 排期座位可售数量
	 * }
	 */
	private function __getPlanSiteStatistic($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;

		$param['pFeatureAppNo']       = $para_list['pFeatureAppNo'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetPlanSiteStatistic', $param);
		return $ret;
	}
	

	/**
	 * 4.12  应用商按照协议，设置排期结算价格
	 * 
	 * @param array $para_list {
	 * 	string pFeatureAppNo 排期编号,
	 * 	string pAppPric 票面价,
	 *  string pBalancePric 确认票面价
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	private function __setFeaturePrice($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pFeatureAppNo'] = $para_list['pFeatureAppNo'];
		$param['pAppPric']      = $para_list['pAppPric'];
		$param['pBalancePric']  = $para_list['pBalancePric'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('SetFeaturePrice', $param);
		return $ret;
	}
	
	/**
	 * 4.13  解锁订单座位
	 * 
	 * @param array $para_list
	 * {
	 * 	string pOrderNO 订单号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	public function releaseSeat($para_list = array()) {

		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $para_list['cinemaCode'];
		$param['pOrderNO'] = $para_list['orderCode'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('UnLockOrderCenCin', $param);
		$ret = $ret['UnLockOrderCenCinResult'];
		return $ret;
	}
	 
	/**
	 * 4.14  查询所有影院当前可售排期上映影片
	 * 
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array Films {
	 * 		array Film [{
	 * 			string FilmNo 影片编号,
	 * 			string FilmName 影片名称
	 * 		}]
	 * 	}
	 * }
	 */
	public  function getFeatureFilm() {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetFeatureFilm', $param);
		return $ret;
	}
	
	/**
	 * 4.15  平台手工工单明细查询接口
	 * 
	 * @param array $para_list
	 * {
	 * 	string pBeginDate 开始日期(yyyy-MM-dd)
	 * 	string pEndDate 结束日期(yyyy-MM-dd)
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array OrderInfos {
	 * 		array OrderInfo [{
	 * 			string OrderNo 订单编号,
	 * 			string SerialNumber 合作商方定单号（流水号）,
	 * 			string MobileNo 手机号码,
	 * 			string BuyDate 订购日期,
	 * 			string FeatureDate 放映日期,
	 * 			string TicketSum 订票数量,
	 * 			string OrderStatus 订单状态,
	 * 			string PlaceName 影院名称,
	 * 			string FilmName 影片名称,
	 * 			string HallName 影厅名称,
	 * 			array SeatInfos {
	 * 				array SeatInfo [{
	 * 					string SeatRow 座位行,
	 * 					string SeatCol 座位列,
	 * 					string SeatPieceNo 座区号
	 * 				}]
	 * 			}
	 * 		}]
	 * 	}
	 * }
	 */
	public function getOrderInfo($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pBeginDate']  = $para_list['pBeginDate'];
		$param['pEndDate']    = $para_list['pEndDate'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetOrderInfo', $param);
		return $ret;
	}

	/**
	 * 4.16  修改订单价格 [已弃用接口]
	 * 
	 * @param array $para_list
	 * {
	 * 	string pOrderNO 订单号,
	 * 	string pAppPric 票面价,
	 * 	string pBalancePric 确认票面价
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 * 
	 * @deprecated
	 */
	private function __modifyOrderPrice($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID']     = $para_list['cinemaCode'];
		$param['pOrderNO']     = $para_list['pOrderNO'];
		$param['pAppPric']     = $para_list['pAppPric'];		
		$param['pBalancePric'] = $para_list['pAppPric'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('ModifyOrderPayPrice', $param);
		return $ret;
	}
	
	/**
	 * 4.17  根据影片编码读取当前上映影院
	 * 
	 * @param array $para_list
	 * {
	 * 	string pFilmNo 影片编码
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array Cinemas {
	 * 		array Cinema [{
	 * 			string PlaceNo 影院编号,
	 * 			string PlaceName 影院名称,
	 * 			string CityNo 影院所在地区编号,
	 * 			string CreateDate 电影院授权日期,
	 * 			string PlanData 上映日期
	 * 		}]
	 * 	}
	 * }
	 */
	private function __getFilmCinema($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
	
		$param['pFilmNo']  = $para_list['pFilmNo'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetFilmCinema', $param);
		return $ret;
	}
	
	/**
	 * 4.18  根据影片编码,影院编码读取上映场次排期
	 * 
	 * @param array $para_list {
	 * 	string pCinemaID 影院编码
	 * 	string pFilmNo 影片编码
	 *  string pPlanDate 获取排期的日期 格式(yyyy-mm-dd)
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array CinemaPlans {
	 * 		array CinemaPlan [{
	 * 			string FeatureNo 未知,
	 * 			string HallName 影厅名称,
	 * 			string PlaceName 影院名称,
	 * 			string FeatureAppNo 排期编码,
	 * 			string FeatureDate 排期日期,
	 * 			string FeatureTime 排期时间,
	 * 			string TotalTime 排期结束时间,
	 * 			string PlaceNo 影院编号,
	 * 			string HallNo 影厅编号,
	 * 			string FilmNo 影片编号,
	 * 			string FilmName 影片名字,
	 * 			string AppPric 票价,
	 * 			string StandPric 标准价,
	 * 			string UseSign 可用性[0.可用,1.不可用,3.待审核],
	 * 			string SetClose 计划状态[0.未售,1.开售,2.截止,3.停售,5.统计,9.注销],
	 * 			string CopyType 拷贝制式,
	 * 			string CopyLanguage 拷贝语言,
	 * 			string HallSeats 影厅座位数,
	 * 			string AvailableSeats 剩余座位数
	 * 		}]
	 *  }
	 * }
	 */
	private function __getFeatureInfo($para_list = array()) {
		$param = array('pAppCode' => $this->pAppCode);
		
		$param['pCinemaID'] = $para_list['pCinemaID'];
		$param['pFilmNo'] = $para_list['pFilmNo'];
		$param['pPlanDate'] = $para_list['pPlanDate'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetFeatureInfo', $param);
		return $ret;
	}
	
	/**
	 * 4.19 退票
	 * 
	 * @param array $para_list {
	 * 	string pOrderNo 订单号
	 * 	string pDesc 退票原因
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	private function __backTicket($para_list = array()) {

		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaID'] = $para_list['cinemaCode'];
		$param['pOrderNO'] = $para_list['orderCode'];
		
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$param['pDesc'] = $para_list['desc'];
		
		// print_r($param);
		$ret =  $this->post('BackTicket', $param);
		// print_r($ret);
		$ret = $ret['BackTicketResult'];
		return $ret;
	}
	
	/**
	 * 4.20 合作商打票
	 * 
	 * @param array $para_list {
	 * 	string pOrderNo 订单号,
	 * 	string pValidCode 验证码,
	 * 	string pRequestType 请求类型[0.查询订单信息,1.通知打票]
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单号,
	 * 	string OrderStatus 订单状态,
	 * 	string OrderDate 订单日期,
	 * 	string OrderTime 订单时间,
	 * 	string FeatureDate 订单日期,
	 * 	string FeatureTime 订单时间,
	 * 	string FilmName 影片名称,
	 * 	string HallName 影厅名,
	 * 	string PrintType 打印类型[0.满天星打票, 1.合作商打票, 2.未打票],
	 * 	array SeatInfos {
	 * 		array SeatInfo [{
	 * 			string SeatRow 座位行,
	 * 			string SeatCol 座位列,
	 * 			string SeatPieceName 座区名,
	 * 			string TicketNo 票号
	 * 		}]
	 *  }
	 * }
	 */
	private function __appPrintTicket($para_list) {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pOrderNO']     = $para_list['pOrderNO'];
		$param['pValidCode']   = $para_list['pValidCode'];
		$param['pRequestType'] = $para_list['pRequestType'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('AppPrintTicket', $param);
		return $ret;
	}
	
	/**
	 * 4.21 获取影院连接状态 [已弃用接口]
	 * 
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array StateInfos {
	 * 		array CinemaInfo [{
	 * 			string PlaceNo 影院编码,
	 * 			string CinemaName 影院名称,
	 * 			string State 连接状态[1.正常,其他为异常],
	 * 			string LastvDateTime 最后握手时间
	 * 		}]
	 *  }
	 * }
	 * 
	 * @deprecated
	 */
	private function __getCinemaStatus() {
		$param['pAppCode'] = $this->pAppCode;
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('GetCinemaStatus', $param);
		return $ret;
	}
	
	/**
	 * 4.24  检查需要定票的座位状态情况，并定票锁定实时座位
	 *
	 * @param array $para_list
	 * {
	 * 	string FeatureAppNo 排期编号
	 * 	string SerialNum 合作商方定单号（流水号）,
	 * 	string PayType 付费类型[0.其他 70.会员卡支付],
	 * 	string RecvMobilePhone 接收二维码手机号码,
	 * 	array SeatInfos {
	 * 		array SeatInfo[{
	 * 			string SeatNo 影厅座位号[-1.自动选座,其他座位号],
	 * 			string TicketPrice 显示票价,
	 * 			string Handlingfee 服务费
	 * 		}]
	 * 	}
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编号,
	 * 	array SeatInfos {
	 * 		array SeatInfo [{
	 * 			string SeatNo 锁定的座位编号,
	 * 			string TicketPrice 锁定的座位价格,
	 * 			string SeatRow 锁定的座位行,
	 * 			string SeatCol 锁定的座位列,
	 * 			string SeatPieceNo 锁定的座位座区号,
	 * 		}]
	 * 	}
	 * }
	 */
	public function checkSeatState($arr) {
		$param['AppCode'] = $this->pAppCode;
		$param['CinemaId'] = $arr['cinemaCode'];
		$param['FeatureAppNo'] = $arr['featureAppNo'];
		$param['SerialNum'] = $arr['serialNum'];
		$param['SeatInfos'] = count($arr['seatInfos']);
		$param['PayType'] = 0;
		$param['RecvMobilePhone'] = $arr['mobile'];
	
		$param['TokenID'] = $this->pTokenID;
		$param['VerifyInfo'] = $this->getMd5Str($param);


		//print_r($param);
	
		$param['SeatInfos'] = $this->xml->xml_encode($arr['seatInfos'],'utf-8','root','SeatInfo',false);
	
		$param1['pXmlString'] = $this->xml->xml_encode($param,'utf-8','RealCheckSeatStateParameter');
		//print_r($param);
		$ret =  $this->post('LiveRealCheckSeatState', $param1);
		//print_r($ret);
		$ret = $ret['RealCheckSeatStateResult'];

		$ret['OrderCode']=$ret['OrderNo'];
		$ret['interfaceType']='mtx';
		$ret['Message'] = getMtxError($ret['ResultCode']);
		$ret['AutoUnlockDatetime']=date('Y-m-d H:i:s',time()+15*60);
		return $ret;
	}
	
  	/** 
  	 * 4.25  修改订单价格
	 * 
	 * @param array $para_list
	 * {
	 * 	string pOrderNO 订单号,
	 * 	string pAppPric 票面价,
	 * 	string pBalancePric 确认票面价
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	private function __modifyOrderPayPrice($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		$param['CinemaId'] 		    = $para_list['CinemaId'];
		$param['pOrderNO']     = $para_list['pOrderNO'];
		$param['pAppPric']     = $para_list['pAppPric'];		
		$param['pBalancePric'] = $para_list['pBalancePric'];
		
		$param['pTokenID'] = $this->pTokenID;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('ModifyOrderPayPrice', $param);		
		return $ret;
	}
	
	
	# ---- EndRegion - 满天星接口 ----#
	
		/** 
  	 * 4.25  满天星日志
	 * 
	 * @param array $para_list
	 * {
	 * 	string pOrderNO 订单号,
	 * 	string pAppPric 票面价,
	 * 	string pBalancePric 确认票面价
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */


			/**
	 * 4.9   卖常规票(带座位票)
	 * 
	 * @param array $para_list
	 * {
	 * 	string FeatureAppNo 排期编号,
	 * 	string SerialNum 合作商方定单号（流水号）,
	 * 	string Printpassword 取票密码,
	 * 	float Balance 要支付的金额,
	 * 	string PayType 付费类型[0.其他 70.会员卡支付],
	 * 	string RecvMobilePhone接收二维码手机号码,
	 * 	string SendType 接收二维码方式[移动号码 100.短信,300.彩信二维码,301.NOKIA长短信二维码; 非移动号码 400.信息机发送短信],
	 * 	string PayResult 支付结果 [0.成功,1.失败],
	 * 	bool IsCmtsPay 漫天星负责扣款,
	 * 	bool IsCmtsSendCode 满天星负责发送二维码,
	 * 	string PayMobile 支付手机号[如需满天星支付],
	 * 	string BookSign [0.全额支付,1.预定金支付],
	 * 	float Payed 商城已支付金额 Balance+Payed=票价,
	 * 	string SendModeID 漫天星二维码模板编号,
	 * 	string PaySeqNo 影院会员卡支付交易流水号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string OrderNo 订单编码,
	 * 	string ValidCode 订单验证码
	 * }
	 */
	public function submitOrder($para_list = array()) {
		$this->__modifyOrderPrice(array('pOrderNO'=>$para_list['orderCode'],'cinemaCode'=>$para_list['cinemaCode'],'pAppPric'=>$para_list['seatInfos'][0]['Price']-$para_list['seatInfos'][0]['ServiceCharge']));
		$param['AppCode']         	    = $this->pAppCode;
		$param['CinemaId'] 		    = $para_list['cinemaCode'];
		$param['FeatureAppNo'] 		    = $para_list['featureAppNo'];
		$param['SerialNum']      		    = $para_list['serialNum'];
		$param['Printpassword']      	    = $para_list['printpassword'];
		$param['Balance']      		   	= 0;
		$param['PayType']      		    =  $para_list['payType'];
		$param['RecvMobilePhone']         = $para_list['mobilePhone'];
		$param['SendType']      		    = 100;
		$param['PayResult']      		    = 0;
		$param['IsCmtsPay']      		    = false;
		$param['IsCmtsSendCode']          = true;
		$param['PayMobile']      		    = 0;
		$param['BookSign']      		   	= 0;
		$param['Payed']      		    	= $para_list['amount'];
		$param['SendModeID']      		= 0;
		$param['PaySeqNo']      		    = $para_list['transactionNo'];
		
		$param['TokenID'] = $this->pTokenID;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		
		$param1['pXmlString'] = $this->xml->xml_encode($param,'utf-8','SellTicketParameter');
		
		$ret =  $this->post('SellTicket', $param1);

		$ret = $ret['SellTicketResult'];
		$ret['VerifyCode']=$ret['ValidCode'];
		$ret['PrintNo']=0;
		
		return $ret;
		
	}
	
	

}