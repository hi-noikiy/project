<?php
/**
 * 辰星接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
class CxMove{
	/** @var string $apiUrl 接口地址  */
	private $apiUrl = "http://tsp.oristartech.cn:8080/tsp-ws/services/tsp/cinema?wsdl";
	/** @var string $pAppCode 应用编码  */
	private $pAppCode  = '';
	/** @var string $pAppPwd 应用密码  */
	private $pAppPwd   = '';
	/*private $pAppCode  = 'ZMYSUSER';
	private $apiUrl = "http://121.14.3.105:8080/tsp-ws/services/tsp/cinema?wsdl";
	private $pAppPwd   = '880b76691a6fd7fd231ee3ae45a99d80';*/
	
	
	private $pCompress   = '0';
 	
	/** @var simlpXML $xml simpleXML 解析类对象  */
	private $xml = null;   // simpleXML 解析类
	
	private $mtxLog = null;

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
	 * 查询电影院列表--------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 * 
	 */
	public function queryCinemaList() {
		$param['pAppCode'] = $this->pAppCode;		
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryCinemaList', $param);
		return $ret;
	}

	/**
	 * 4.14  查询在售影片信息  --------辰星
	 * @param array [{
	 *     string   cinemaCode   电影院编号
	 *     string   planDate     放映日期，格式(yyyy-mm-dd)，以自然日为准
	 * 
	 * }]
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	array FilmInfoVOs {
	 * 		array FilmInfo [{
	 * 			string FilmCode 影片编号,
	 * 			string FilmName 影片名称
	 * 			string Version 巨幕立体
	 * 			string Duration 影片时长
	 * 			string PublishDate 上映时间
	 * 			string Publisher 发行商
	 * 			string Producer 制作人
	 * 			string Director 导演 
	 * 			string Cast 演员
	 * 			string Introduction 简介
	 * 		}]
	 * 	}
	 * }
	 */
	public function getQueryFilmInfo($cinemaCode, $planDate){

		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $cinemaCode;
		$param['pPlanDate'] = $planDate;
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryFilmInfo', $param);

		if (isset($ret['ResultCode']) && $ret['ResultCode'] == '0') {
			if($ret['FilmInfoVOs']['FilmInfoVO'][0]){
				
				$ret['FilmInfo'] = $ret['FilmInfoVOs']['FilmInfoVO'];
			}else{
				$ret['FilmInfo'][0] = $ret['FilmInfoVOs']['FilmInfoVO'];
			}
			unset($ret['FilmInfoVOs']);
		}

		return $ret;
	}
	/**
	 * 4.2.6 QueryMemberLevel查询会员等级信息--辰星
	 * 
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 * 
	 * @author ZhuZhenguo 2014/04/03 19:35
	 */
	public function queryMemberLevel($para_list){
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $para_list['cinemaCode'];
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryMemberLevel', $param);
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
	private function post($methon, $parameter){	
		
		$xml_data = $this->getSoapRespone($this->apiUrl, $methon, $parameter);
		$ret = array();
		if ( !empty($xml_data) ) {
			if (substr($xml_data, 0, 5) != '<?xml') {
				$xml_data = '<?xml version="1.0" encoding="utf-8"?>
				<' . $methon . 'Result xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
				'.$xml_data.'</' . $methon . 'Result>';
			}
			$xml = \simplexml_load_string($xml_data);
			/*wlog('方法：'.$methon,'movelog');
			wlog('参数：'.$this->xml->xml_encode($parameter),'movelog');
			wlog('返回：'.$xml_data,'movelog');*/
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

				$client = new \SoapClient($url, array('connection_timeout' => 120));
				
				$client->soap_defencoding = 'utf-8';
				$client->decode_utf8      = false;
				$client->xml_encoding     = 'utf-8';			
				$result = $client->$methon($para);
				if (is_soap_fault($result)) {
					delDirAndFile(SOAP_CACHE_PATH);
					$i++;
				}else{
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
		if (is_array($para_list) && !empty($para_list))
		{
			foreach ($para_list as $k => $v) 
			{
				$str_long.=$v;				
			}
			$str_long .= $this->pAppPwd;
		}		
		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return strtolower(md5(strtolower($str_long)));
	}
	
	
	# ---- EndRegion - 基础函数 ---- #
	
	# ---- Region - 辰星接口 ---- #
	
	
	/**
	 * 4.1.2 QueryCinemaInfo查询影院信息---------------辰星
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
	public function getHall($arr) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $arr['cinemaCode'];		
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);		
		$ret =  $this->post('QueryCinemaInfo', $param);
		$newHallArray = '';

		// print_r($ret);

		if($ret['ResultCode'] == 0){
			foreach ($ret['Cinema'][0]['Screens']['ScreenVO'] as $key => $value) {
				$newHallArray[$key]['HallNo'] = $value['ScreenCode'];
				$newHallArray[$key]['HallName'] = $value['ScreenName'];
			}
			$ret['Halls'] = $newHallArray;
			unset($ret['Cinema']);
		}

		return $ret;	
	}
	/**
	 * 4.1.3 QuerySeatInfo查询影厅座位信息---------------辰星
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
	public function getHallSite($arr) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $arr['cinemaCode'];
		$param['pScreenCode'] = $arr['hallNo'];
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('QuerySeatInfo', $param);
		return $ret;
	}
	/**
	 * 4.1.6 QueryPlanSeat查询放映计划座位售出状态---------------辰星
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
	public function getPlanSiteState($arr) {
		$param['pAppCode'] = $this->pAppCode;	
		$param['pCinemaCode'] = $arr['cinemaCode'];	
		$param['pFeatureAppNo'] = $arr['featureAppNo'];	
		$param['pStatus'] = 'All';		
		$param['pCompress'] = $this->pCompress;	
		$param['pVerifyInfo'] = $this->getMd5Str($param);		
		$ret =  $this->post('QueryPlanSeat', $param);
		$newPlanSiteArray = '';
		if($ret['ResultCode']  == 0){
			foreach ($ret['PlanSiteStates']['PlanSiteState'] as $key => $value) {
				$newPlanSiteArray[$key]['SeatNo'] = $value['SeatCode'];
				$newPlanSiteArray[$key]['SeatRow'] = $value['RowNum'];
				$newPlanSiteArray[$key]['SeatCol'] = $value['ColumnNum'];
				$newPlanSiteArray[$key]['SeatState'] = $value['Status'] == 'Available' ? 0 : -1;
			}
			unset($ret['PlanSiteStates']);
			$ret['PlanSiteState'] = $newPlanSiteArray;
		}


		// print_r($ret);

		return $ret;		
	}
	/**
	 * 4.1.11 CancelOrder取消交易订单-----------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function backTicket($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $para_list['cinemaCode'];
		$param['pPrintNo'] = $para_list['printNo'];
		$param['pVerifyCode'] = $para_list['verifyCode'];
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('CancelOrder', $param);
		return $ret;
	}
	/**
	 * 4.1.7 LockSeat锁定座位--------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function checkSeatState($arr) {
		$param['AppCode'] = $this->pAppCode;
		$param['CinemaCode'] = $arr['cinemaCode'];
		$param['FeatureAppNo'] = $arr['featureAppNo'];
		$param['SeatInfos'] = implode('' , $arr['seatInfos']);
		$param['Compress'] = $this->pCompress;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		$param['SeatInfos'] = $this->xml->xml_encode($arr['seatInfos'],'utf-8','SeatInfos','SeatCode',false);
		$param1['LockSeatXml'] = $this->xml->xml_encode($param,'utf-8','LockSeatParameter');
		$ret =  $this->post('LockSeat', $param1);
		$ret['interfaceType']='cx';
		return $ret;
	}
	/**
	 * 4.1.8 ReleaseSeat解锁座位------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function releaseSeat($para_list = array()) {
		$param['AppCode'] = $this->pAppCode;
		$param['CinemaCode'] = $para_list['cinemaCode'];
		$param['OrderCode'] = $para_list['orderCode'];
		$param['FeatureAppNo'] = $para_list['featureAppNo'];
		$param['SeatInfos'] = implode('' , $para_list['seatInfos']);
		$param['Compress'] = $this->pCompress;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		$param['SeatInfos'] = $this->xml->xml_encode($para_list['seatInfos'],'utf-8','SeatInfos','SeatCode',false);
	
		$param1['ReleaseSeatXml'] = $this->xml->xml_encode($param,'utf-8','ReleaseSeatParameter');
		$ret =  $this->post('ReleaseSeat', $param1);
		$ret['SerialNum']=0;
		return $ret;
	}
	/**
	 * 4.1.5 QueryPlanInfo查询电影院放映计划信息-------------辰星
	 * 
	 * @param 
	 * 	string pCinemaCode 电影院编号
	 * 	string pPlanDate 获取排期的日期 格式(yyyy-mm-dd)
	 *
	 * @return array {
	 * 	string ResultCode 返回结果号,
	 * 	string Message 返回信息，
	 * 	array CinemaPlans {
	 * 		array CinemaPlan [{
	 * 			string ScreenCode 影厅编码
	 * 			string FeatureAppNo 放映计划编码
	 * 			string StartTime 放映计划开始时间(YYYY-MM-DD hh:mm:ss)
	 * 			string PlaythroughFlag 连场标志：Yes，连场No，非连场
	 * 			string FeatureDate 连场标志：Yes，连场
	 * 			string FilmCode 影片编码
	 * 			string FilmName 影片名称
	 * 			string Lang 语种
	 * 			int Duration 影片时长，以分钟为单位
	 * 			int Sequence 连场中的序号
	 * 			Float LowestPrice 最低票价
	 * 			Float StandardPrice 标准票价
	 * 			Float ListingPrice 挂牌价
	 * 		}]
	 *  }
	 * } 
	 */
	public function getCinemaPlan($arr) {
		$param['pAppCode'] = $this->pAppCode;		
		$param['pCinemaCode'] = $arr['cinemaCode'];
		$param['pPlanDate']  = $arr['planDate'];		
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryPlanInfo', $param);
		$newPlanArray = '';

		// print_r($ret);

		if($ret['ResultCode'] == 0){
			foreach ($ret['CinemaPlans']['CinemaPlan'] as $key => $value) {
				$newPlanArray[$key]['FeatureAppNo'] = $value['FeatureAppNo'];
				$newPlanArray[$key]['FeatureNo'] = '';
				$newPlanArray[$key]['StartTime'] = $value['StartTime'];
				$newPlanArray[$key]['FilmNo'] = $value['Films']['Film']['FilmCode'];
				$newPlanArray[$key]['FilmName'] = $value['Films']['Film']['FilmName'];
				$newPlanArray[$key]['HallNo'] = $value['ScreenCode'];
				$newPlanArray[$key]['CopyType'] = '';
				$newPlanArray[$key]['CopyLanguage'] = $value['Films']['Film']['Lang'];
				$newPlanArray[$key]['TotalTime'] = $value['Films']['Film']['Duration'];
				$newPlanArray[$key]['LowestPrice'] = $value['Price']['LowestPrice'];
				$newPlanArray[$key]['StandardPrice'] = $value['Price']['StandardPrice'];
				$newPlanArray[$key]['ListingPrice'] = $value['Price']['ListingPrice'];
			}
			unset($ret['CinemaPlans']);
			$ret['CinemaPlans'] = $newPlanArray;
		}



		return $ret;
	}
	/**
	 * 4.1.9 SubmitOrder确认订单交易--------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function submitOrder($para_list = array()) {
		$param['AppCode'] = $this->pAppCode;
		$param['CinemaCode'] = $para_list['CinemaCode'];
		$param['OrderCode'] = $para_list['OrderCode'];
		$param['FeatureAppNo'] = $para_list['FeatureAppNo'];
		$param['MobilePhone'] = $para_list['MobilePhone'];
		$param['SeatInfos']='';
		foreach ($para_list['SeatInfos'] as $value) {
			$param['SeatInfos'].=implode('' , $value);
		}
		$param['Compress'] = $this->pCompress;
		$param['VerifyInfo'] = $this->getMd5Str($param);
		$param['SeatInfos'].= $this->xml->xml_encode($para_list['SeatInfos'],'utf-8','SeatInfos','SeatInfo',false);
		$param1['SubmitOrderXml'] = $this->xml->xml_encode($param,'utf-8','SubmitOrderParameter');
		$ret =  $this->post('SubmitOrder', $param1);
		return $ret;
	}
	
	/**
	 * 4.1.10 QueryOrderStatus查询订单交易状态--------辰星
	 * @param null
	 * @return array [{
	 * 	string	FilmNo	影片编码
	 * 	string	FilmName	影片名称
	 * }]
	 *
	 */
	public function queryOrderStatus($para_list = array()) {
		$param['pAppCode'] = $this->pAppCode;
		$param['pCinemaCode'] = $para_list['cinemaCode'];
		$param['pOrderCode'] = $para_list['orderCode'];
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		$ret =  $this->post('QueryOrderStatus', $param);
		return $ret;
	}
	/**
	 * 4.1.4 QueryFilmInfo查询在售影片信息---------辰星
	 * 
	 * @param array $para_list
	 * {
	 * 	string pOrderNO 订单号
	 * }
	 * @return array {
	 * 	string ResultCode 返回结果号
	 * }
	 */
	public function queryFilmInfo($pCinemaCode,$pPlanDate) {
		$param['pAppCode'] = $this->pAppCode;		
		$param['pCinemaCode'] = $pCinemaCode;
		$param['pPlanDate']  = $pPlanDate;		
		$param['pCompress'] = $this->pCompress;
		$param['pVerifyInfo'] = $this->getMd5Str($param);
		
		$ret =  $this->post('QueryFilmInfo', $param);
		return $ret;
	}

	

}