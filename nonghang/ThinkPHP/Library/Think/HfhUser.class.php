<?php
/**
 * 火凤凰接口 数据处理代码
 * 
 * @version V5.1
 */
namespace Think;
class HfhUser{
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
		/*$this->userId = $config['appCode'];
		$this->userPass = $config['appPwd'];*/
	}
	
	/**
	 * 获得token
	 */
	function getToken(){
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('getToken', $param);
		return $ret['card']['token'];
	}
	
	/**
	 * 4.2.1 verifyMemberLogin会员卡登录接口-----------辰星
	 */
	public function verifyMemberLogin($arr) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['cinemaId'] = $arr['cinemaCode'];
		$param['cinemaLinkId'] = $arr['link'];
		$param['cardFacadeCd'] = $arr['loginNum'];
		$param['cardPass'] = $arr['password'];
		$param['randKey'] = 	random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('qryMemberCardInfo', $param);
		if(empty($ret['result'])){
			$ret=$ret['card'];
			$ret['ResultCode']='0';
		}else{
			$ret['ResultCode']=$ret['result'];
			$ret['Message']=$ret['message'];
		}
		if($ret['ResultCode']==0){
			$ret['CardNum']=$ret['cardFacadeCd'];
			$ret['LevelCode']=$ret['gradeId'];
			$ret['LevelName']=$ret['gradeDesc'];
			if($ret['state']=='N'){
				$ret['CardStatus']=0;
			}else{
				$ret['CardStatus']=$ret['state'];
			}
			$ret['BasicBalance']=$ret['balance'];
			$ret['DonateBalance']=0;
			$ret['IntegralBalance']=$ret['tradeMarking'];
			$ret['BusinessCode']=$arr['cinemaCode'];
			$ret['BusinessName']=$arr['cinemaName'];
			$ret['CreditNum']=$ret['idCard'];
			$ret['ExpirationTime']=strtotime($ret['invalidationDate']);
			$ret['MobileNum']=$ret['mobilePhone'];
			$ret['Birthday']=$ret['birthdate'];
			$ret['UserName']=$ret['userId'];
			$ret['Sex']=$ret['sex'];
		}
		return $ret;
	}
	/**
	 * 充值
	 * @param unknown $para_list
	 * @return Ambigous <number, multitype:>
	 */
	public function memberCharge($para_list = array()) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['cinemaLinkId'] = $para_list['link'];
		$param['cardFacadeCd'] = $para_list['loginNum'];
		$param['cardPass'] = $para_list['passWord'];
		$param['token'] = $this->getToken();
		$param['balance'] = $para_list['chargeAmount'];
		$param['payment'] = $para_list['payment'];
		$param['aliOrderNo'] = $para_list['aliOrderNo'];  //支付宝订单号
		$param['randKey'] = 	random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('modifyMemberCardBalance', $param);
		$ret['ResultCode']=0;
		$ret['BasicBalance']=$ret['card']['balance'];
		$ret['DonateBalance']=0;
		$ret['TransactionNo']=0;
		unset($ret['card']);
		return $ret;
	}
	
	/**
	 * 修改密码
	 * @param unknown $para_list
	 * @return multitype:
	 */
	public function modifyMemberPassword($para_list = array()) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['cinemaId'] = $para_list['cinemaCode'];
		$param['cinemaLinkId'] = $para_list['link'];
		$param['cardFacadeCd'] = $para_list['loginNum'];
		$param['token'] = $this->getToken();
		$param['oldPass'] = $para_list['oldPassword'];
		$param['newPass'] = $para_list['newPassword'];
		$param['randKey'] = 	random(8);
		$param['checkValue'] = $this->getMd5Str($param);
		$ret =  $this->post('modifyMemberCardPassword', $param);
		return $ret;
	}
	
	
	/**
	 *获取会员卡类型
	 */
	public function getCardType($para_list) {
		$param['userId'] = $this->userId;
		$param['userPass'] = $this->userPass;
		$param['cinemaId'] = $para_list['cinemaCode'];
		$param['cinemaLinkId'] = $para_list['link'];	
		$ret =  $this->post('qryGradeList', $param);

		$ret['ResultCode']=$ret['result'];
		if($ret['ResultCode'] == 0){
			$newArray = '';
			foreach ($ret['cardGrades']['cardGrade'] as $key => $value) {
				$newArray[$key]['MemberType'] = $value['gradeId'];
				$newArray[$key]['MemberTypeName'] = $value['gradeDesc'];
			}
			$ret['MemberTypes'] = $newArray;
			unset($ret['cardGrades']);
		}
		return $ret;
	}
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
	
	
	
	
	# ---- EndRegion - 辰星会员接口 ----#
}