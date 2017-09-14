<?php
/**
 * 辰星接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
class HfhMove{
	/** @var string $apiUrl 接口地址  */
	private $apiUrl = "http://211.147.239.214:9600/DI_DataSender_For_BOM.asmx?wsdl";
	/** @var string $pAppCode 应用编码  */
	private $userId  = 'ZUIMEI';
	/** @var string $pAppPwd 应用密码  */
	private $userPass   = '2uiMei669';
	/** @var simlpXML $xml simpleXML 解析类对象  */
	private $xml = null;   // simpleXML 解析类

	/**
	 * 构造函数
	 * 
	 * @param registry $registry 全局储存注册对象
	 */	
	public function __construct($config) {
		$this->xml = new SimpleXML();
		$this->userId = $config['appCode'];
		$this->userPass = $config['appPwd'];
	}
	/**
	 * 根据影院查询影厅信息
	 * @param unknown $pCinemaCode
	 * @return Ambigous <unknown, multitype:>
	 */
	public function getHall($arr) {

		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['searchKey'] = 'C';
		$param['searchValue'] = $arr['link'];
		$ret =  $this->post('qrySearchCinema', $param);
		if(empty($ret['result'])){
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['result'];
			$ret['Message']=$ret['message'];
		}

		$ret['Halls']=array();
		foreach ($ret['cinemas']['cinema']['hall'] as $k=>$v){
			$ret['Halls'][$k]['HallNo']=$v['id'];
			$ret['Halls'][$k]['HallName'] = $v['name'];
		}

		unset($ret['cinemas']);
		return $ret;

	}


	/**
	 * 查询影院信息
	 * @param unknown $pCinemaCode
	 * @return Ambigous <unknown, multitype:>
	 */
	public function getCinema($arr) {

		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$ret =  $this->post('qryCinema', $param);
		return $ret;

	}
	/**
	 * 静态影厅座位信息
	 * @param unknown $arr
	 * @return multitype:
	 */
	public function getHallSite($arr) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['cinemaId'] = $arr['cinemaCode'];
		$param['cinemaLinkId'] = $arr['link'];
		$param['hallId'] = $arr['hallNo'];
		$ret =  $this->post('qrySeat', $param);



		if(empty($ret['seatPlan']['result'])){
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['seatPlan']['result'];
			$ret['Message']=$ret['seatPlan']['message'];
		}
		$ret['ScreenSites']['ScreenSite']=array();
		$i=0;

		if (empty($ret['seatPlan']['effective']['section'][0])) {
			$newSeatArray = $ret['seatPlan']['effective']['section'];
			unset($ret['seatPlan']['effective']['section']);
			$ret['seatPlan']['effective']['section'][0] = $newSeatArray;
		}

		foreach ($ret['seatPlan']['effective']['section'] as $value){
			foreach ($value['seat'] as $val) {

				$ret['ScreenSites']['ScreenSite'][$i]['SeatCode']=$arr['cinemaCode'].$ret['seatPlan']['hallId'].($i+1);
				$ret['ScreenSites']['ScreenSite'][$i]['RowNum']=$val['rowId'];
				$ret['ScreenSites']['ScreenSite'][$i]['ColumnNum']=$val['columnId'];
				$ret['ScreenSites']['ScreenSite'][$i]['YCoord']=$val['rowNum'];
				$ret['ScreenSites']['ScreenSite'][$i]['XCoord']=$val['columnNum'];
				$ret['ScreenSites']['ScreenSite'][$i]['Status']=0;
				$ret['ScreenSites']['ScreenSite'][$i]['sectionId']=$value['id'];
				$ret['ScreenSites']['ScreenSite'][$i]['sectionName']=$value['name'];
				$ret['ScreenSites']['ScreenSite'][$i]['typeInd']=$val['typeInd'];
				if($val['typeInd']=='L'){
					$ret['ScreenSites']['ScreenSite'][$i]['GroupCode']=$ret['seatPlan']['hallId'].$i;
				}elseif($val['typeInd']=='R'){
					$ret['ScreenSites']['ScreenSite'][$i]['GroupCode']=$ret['seatPlan']['hallId'].($i-1);
				}else{
					$ret['ScreenSites']['ScreenSite'][$i]['GroupCode']=0;
				}
				$i++;
			}
		}
		unset($ret['seatPlan']);

		// wlog('接口信息-影院编号:' .$arr['cinemaCode'] . ';影厅：' . $arr['HallNo'] . json_encode($ret), 'hallSite');

		return $ret;
	}
	/**
	 * 查询影院排期
	 * @param unknown $pCinemaID
	 * @param unknown $pPlanDate
	 * @return Ambigous <unknown, multitype:>
	 */
	public function getCinemaPlan($arr) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['updateLevel'] = 0;
		$param['showDate'] = $arr['planDate'];
		$param['cinemaId'] = $arr['cinemaCode'];
		$param['cinemaLinkId'] = $arr['link'];
		$ret =  $this->post('qryShow', $param);
		if(empty($ret['result'])){
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['result'];
			$ret['Message']=$ret['message'];
		}
		$ret['CinemaPlans']=array();
		$i =0;
		if($ret['ResultCode'] == 0){

			if (empty($ret['shows']['show'][0])) {
				$newShowArray = $ret['shows']['show'];
				unset($ret['shows']['show']);
				$ret['shows']['show'][0] = $newShowArray;
			}
			foreach ($ret['shows']['show'] as  $value) {
				$str='';
				$ret['CinemaPlans'][$i]['FeatureAppNo'] = $value['seqNo'];
				$ret['CinemaPlans'][$i]['FeatureNo'] = $value['showSeqNo'];
				if(strlen($value['time'])<3){
					if(strlen($value['time'])=='1'){
						$str1='0:0'.$value['time'];
					}else{
						$str1='0:'.$value['time'];
					}
				}else{
					if(strlen($value['time'])==3){
						$g=1;
					}else{
						$g=2;
					}
					$str1=substr($value['time'], 0,$g).':'.substr($value['time'], $g);
				}
				$ret['CinemaPlans'][$i]['StartTime'] = $value['date'].' '.$str1;
				$ret['CinemaPlans'][$i]['FilmNo'] = $value['film']['code'];
				$ret['CinemaPlans'][$i]['otherfilmNo'] = $value['film']['id'];
				$ret['CinemaPlans'][$i]['FilmName'] = $value['film']['name'];
				$ret['CinemaPlans'][$i]['HallNo'] = $value['hallId'];
				if($value['film']['imax']=='1'){
					$str='MAX';
				}
				$ret['CinemaPlans'][$i]['CopyType'] = $str.$value['film']['dimensional'];
				$ret['CinemaPlans'][$i]['CopyLanguage'] = $value['film']['language'];
				$ret['CinemaPlans'][$i]['TotalTime'] =  $value['film']['duration'];
				$ret['CinemaPlans'][$i]['LowestPrice'] = $value['price']['lowest'];
				if(!empty($value['price']['section'][0])){
					$price=$value['price']['section'][0]['standard'];
				}else{
					$price=$value['price']['section']['standard'];
				}
				$ret['CinemaPlans'][$i]['StandardPrice'] = $price;
				$ret['CinemaPlans'][$i]['ListingPrice'] = $price;
				$i++;
			}
			unset($ret['shows']);
		}
		return $ret;
	}
	/**
	 * 获取座位状态
	 * @param unknown $arr
	 * @return Ambigous <number, multitype:>
	 */
	public function getPlanSiteState($arr) {
		$i = 0;
		foreach ($arr['sectionIds'] as $k=>$v){
			$param['userId'] = $this->userId;
			$param['userPass'] = $this->userPass;
			$param['cinemaId'] = $arr['cinemaCode'];
			$param['cinemaLinkId'] = $arr['link'];
			$param['hallId'] = $arr['hallNo'];
			$param['sectionId'] = $v['sectionId'];
			$param['filmId'] = $arr['filmNo'];
			$param['showSeqNo'] = $arr['showSeqNo'];
			$param['showDate'] = date('Y-m-d',$arr['planDate']);
			$param['showTime'] = date('Gi',$arr['planDate']);
			
			$ret =  $this->post('qryTicket', $param);

			if(empty($ret['showSeats']['result'])){
				$ret['ResultCode']='0';
			}else{
				$ret['ResultCode']=$ret['seatPlan']['result'];
				$ret['Message']=$ret['showSeats']['message'];
			}
			$ret['PlanSiteState']=array();
			if($ret['ResultCode']  == 0){

				if (empty($ret['showSeats']['section']['seat'][0])) {
					$newSeatArray = $ret['showSeats']['section']['seat'];
					unset($ret['showSeats']['section']['seat']);
					$ret['showSeats']['section']['seat'][0] = $newSeatArray;
				}


				foreach ($ret['showSeats']['section']['seat'] as  $value) {
					$newRet['PlanSiteState'][$i]['SeatRow'] = $value['rowId'];
					$newRet['PlanSiteState'][$i]['SeatCol'] = $value['columnId'];
					$newRet['PlanSiteState'][$i]['sectionId'] = $v['sectionId'];
					$i++;
				}

				unset($ret['showSeats']);
			}
		}
		return $newRet;		
	}
	/**
	 * 锁座
	 * @param unknown $arr
	 * @return multitype:
	 */
	public function checkSeatState($arr) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['orderNo'] = time().random(3);
		$param['ticketCount'] = $arr['ticketCount'];
		$param['cinemaId'] = $arr['cinemaCode'];
		$param['cinemaLinkId'] = $arr['link'];
		$param['hallId'] = $arr['hallNo'];
		$param['sectionId'] = $arr['sectionId'];
		$param['filmId'] = $arr['filmNo'];
		$param['showSeqNo'] = $arr['featureNo'];
		$param['showDate'] = date('Y-m-d',$arr['planDate']);
		$param['showTime'] = date('Gi',$arr['planDate']);
		$param['seatId'] = $arr['seatInfos'];
		$param['lockMinuteTime'] = 15;
		$param['randKey'] = random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('webLockSeat', $param);
		if(empty($ret['seatLock']['result'])){
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['seatLock']['result'];
			$ret['Message']=$ret['seatLock']['messages']['message'];
		}
		if($ret['ResultCode']  == 0){
			$ret['OrderCode']=$param['orderNo'];
			$ret['interfaceType']='hfh';
			$ret['AutoUnlockDatetime']=date('Y-m-d H:i:s',time()+$param['lockMinuteTime']*60);
		}
		unset($ret['seatLock']);
		return $ret;
	}
	/**
	 * 解锁座位
	 * @param unknown $para_list
	 * @return multitype:
	 */
	public function releaseSeat($para_list = array()) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['orderNo'] = $para_list['orderCode'];
		$param['ticketCount'] = $para_list['ticketCount'];
		$param['cinemaId'] = $para_list['cinemaCode'];
		$param['cinemaLinkId'] = $para_list['link'];
		$param['randKey'] = random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('webUnlockSeat', $param);
		if(empty($ret['seatRelease']['result'])){
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['seatRelease']['result'];
			$ret['Message']=$ret['seatRelease']['messages']['message'];
		}
		unset($ret['seatRelease']);
		return $ret;
	}
	/**
	 * 退票
	 * @param unknown $para_list
	 * @return multitype:
	 */
	public function backTicket($para_list = array()) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['bookingId'] = $para_list['orderCode'];
		$param['ticketCount'] = $para_list['seatCount'];
		$param['cinemaId'] = $para_list['cinemaCode'];
		$param['cinemaLinkId'] = $para_list['link'];
		$name='returnMemberCardOrder';
		if(empty($para_list['cardId'])){
			$param['hallId'] = $para_list['hallNo'];
			$param['filmId'] = $para_list['otherfilmNo'];
			$param['showSeqNo'] = $para_list['featureNo'];
			$param['showDate'] = date('Y-m-d',$para_list['startTime']);
			$param['showTime'] = intval(date('Gi',$para_list['startTime']));
			$param['sectionId'] = $para_list['sectionId'];
			$name='cancelOrder';
		}
		$param['randKey'] = random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post($name, $param);
		if(!empty($ret['bookingCancel'])){
			$ret=$ret['bookingCancel'];
		}
		$ret['ResultCode']=$ret['result'];
		$ret['Message']=$ret['message'];
		return $ret;
	}
	
	/**
	 * 查询订单状态
	 * @param unknown $para_list
	 * @return multitype:
	 */
	public function queryOrderStatus($para_list = array()) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['orderNo'] = $para_list['orderCode'];
		$param['ticketCount'] = $para_list['ticketCount'];
		$param['cinemaId'] = $para_list['cinemaCode'];
		$param['cinemaLinkId'] = $para_list['link'];
		$param['randKey'] = random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('qryOrder', $param);
		$ret=$ret['orderDetail'];
		$ret['ResultCode']=0;
		if($ret['statusInd']=='1'){
			$ret['OrderStatus']=0;
			$ret['PrintNo']=0;
			$ret['orderNo']=$ret['bookingId'];
			$ret['VerifyCode']=$ret['confirmationId'];
		}elseif($ret['statusInd']=='2'){
			$ret['OrderStatus']=2;
		}

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
				if (is_soap_fault($result)) {
					delDirAndFile(SOAP_CACHE_PATH);
					$i++;
				}
				else
				{
					
					$name=$methon.'Result';
					$Tip = false;
					return $result->$name;
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
		if (is_array($para_list) && !empty($para_list))
		{
			foreach ($para_list as $k => $v) 
			{
				$str_long.=$k.'='.$v.'&';				
			}
			$str_long=substr($str_long,0, -1);
		}		
		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return md5($str_long);
	}


}