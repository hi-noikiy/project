<?php
namespace Soap\Controller;
use Think\Controller;
class ClientController extends Controller {

	private $pTokenID = '1829';
	private $Token = 'abcdef';
	private $pAppCode = 'fzzr';
	private $pAppPwd = 'cmts20140428fzzr';

    public function index(){
    	$this->apiUrl = "http://wap.zmaxfilm.net:8181/Soap/Mtx?wsdl";
    	$this->xml = new \Think\SimpleXML();
        $param['pAppCode'] = 'fzzr';
        $param['pTokenID'] = '1829';

		$param['pVerifyInfo'] = $this->getMd5Str($param);

        $reversed = $this->post('GetCinema', $param); 
    }



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
		print_r($ret);
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
				// print_r($client->__getFunctions());
				// die();
				$client->soap_defencoding = 'utf-8';
				$client->decode_utf8      = false;
				$client->xml_encoding     = 'utf-8';
			
				$result = $client->$methon($para);
				// dump($result);
				return $result->return;
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
		}

		$str_long .= $this->Token . $this->pAppPwd;

		//  校验信息=转换成小写（MD5（转换成小写（应用编码+参数1+参数2+…..+验证密钥）））
		return strtolower(substr(md5(strtolower($str_long)), 8,16));
	}

}