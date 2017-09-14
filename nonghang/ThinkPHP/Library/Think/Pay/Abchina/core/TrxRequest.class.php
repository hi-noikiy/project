<?php
namespace Think\Pay\Abchina\core;
// class_exists('$this->MerchantConfig') or require(dirname(__FILE__).'/$this->MerchantConfig.php');

abstract class TrxRequest
{ 
    private $iLogWriter = null;
    private $MerchantConfig = null;

    
    public function postRequest($config)
    {
        $this->MerchantConfig = new \Think\Pay\Abchina\core\MerchantConfig($config);
        return $this->extendPostRequest(1);
    }
    
    public function extendPostRequest($aMerchantNo)
    {
        try
        {
            // $this->iLogWriter = new LogWriter();
            // $this->iLogWriter->logNewLine("TrustPayClient V3.0.0 交易开始==========================");
            // $this->MerchantConfig->getLogWriterObject($this->iLogWriter);
            //0、检查传入参数是否合法
            if(($aMerchantNo <= 0) || ($aMerchantNo > $this->MerchantConfig->getMerchantNum()))
            {
                throw new \Think\Pay\Abchina\core\TrxException(TrxException::TRX_EXC_CODE_1008, TrxException::TRX_EXC_MSG_1008, 
                    '配置文件中商户数为'.$this->MerchantConfig->getMerchantNum().", 但是请求指定的商户配置编号为$aMerchantNo ！");
            }
            //1、检查交易请求是否合法
            // $this->iLogWriter->logNewLine('检查交易请求是否合法：');
            $this->checkRequest();
            // $this->iLogWriter->log('正确');
            //2、取得交易报文
            $tRequestMessage = $this->getRequestMessage();
            //3、组成完整交易报文
            // $this->iLogWriter->log("完整交易报文：");
            $tRequestMessage = $this->composeRequestMessage($aMerchantNo,$tRequestMessage);
            // $this->iLogWriter->log($tRequestMessage);
            //4、对交易报文进行签名
            $tRequestMessage = $this->MerchantConfig->signMessage($aMerchantNo, $tRequestMessage);
            //5、发送交易报文至网上支付平台
            $tResponseMessage = $this->sendMessage($tRequestMessage);
            
            //6、验证网上支付平台响应报文的签名
            // $this->iLogWriter->logNewLine('验证网上支付平台响应报文的签名：');
            
            $this->MerchantConfig->verifySign($tResponseMessage);
            // $this->iLogWriter->log('正确');
            //7、生成交易响应对象
            // $this->iLogWriter->logNewLine('生成交易响应对象：');
            // $this->iLogWriter->logNewLine('交易结果：['.$tResponseMessage->getReturnCode().']');
            // $this->iLogWriter->logNewLine('错误信息：['.$tResponseMessage->getErrorMessage().']');

        }
        catch (TrxException $e)
        {
            $tResponseMessage = new \Think\Json();
            $tResponseMessage->initWithCodeMsg($e->getCode(), $e->getMessage()." - ".$e->getDetailMessage());
            // if($this->iLogWriter != null)
            // {
            //     $this->iLogWriter->logNewLine('错误代码：[' + $tResponseMessage->getReturnCode().']    错误信息：['.
            //         $tResponseMessage->getErrorMessage().']');
            // }
        }
        catch (Exception $e)
        {
            $tResponseMessage = new \Think\Json();
            $tResponseMessage->initWithCodeMsg(TrxException::TRX_EXC_CODE_1999, TrxException::TRX_EXC_MSG_1999.
                ' - '.$e->getMessage());
            // if ($this->iLogWriter != null)
            // {
            //     $this->iLogWriter->logNewLine('错误代码：['.$tResponseMessage->getReturnCode().']    错误信息：['.
            //         $tResponseMessage->getErrorMessage().']');
            // }
        }

        // if ($this->iLogWriter != null)
        // {
        //     $this->iLogWriter->logNewLine("交易结束==================================================\n\n\n\n");
        //     $this->iLogWriter->closeWriter($this->MerchantConfig->getTrxLogFile());
        // }

        return $tResponseMessage;

    }
	public function genSignature($aMerchantNo)
    {
    	$tRequestMessage = null;
        try
        {
            // $this->iLogWriter = new LogWriter();
            // $this->iLogWriter->logNewLine("TrustPayClient V3.0.0 交易开始==========================");
            // $this->MerchantConfig->getLogWriterObject($this->iLogWriter);
            //0、检查传入参数是否合法
            if(($aMerchantNo <= 0) || ($aMerchantNo > $this->MerchantConfig->getMerchantNum()))
            {
                throw new \Think\Pay\Abchina\core\TrxException(TrxException::TRX_EXC_CODE_1008, TrxException::TRX_EXC_MSG_1008, 
                    '配置文件中商户数为'.$this->MerchantConfig->getMerchantNum().", 但是请求指定的商户配置编号为$aMerchantNo ！");
            }
            //1、检查交易请求是否合法
            // $this->iLogWriter->logNewLine('检查交易请求是否合法：');
            $this->checkRequest();
            // $this->iLogWriter->log('正确');
            //2、取得交易报文
            $tRequestMessage = $this->getRequestMessage();
           
            //3、组成完整交易报文
            // $this->iLogWriter->log("完整交易报文：");
            $tRequestMessage = $this->composeRequestMessage($aMerchantNo,$tRequestMessage);
            // $this->iLogWriter->log($tRequestMessage);
            //4、对交易报文进行签名
            $tRequestMessage = $this->MerchantConfig->signMessage($aMerchantNo, $tRequestMessage); 
            // $this->iLogWriter->log("签名后的完整报文：");          
			// $this->iLogWriter->log($tRequestMessage);
			$tRequestMessage = str_replace('"', "&quot;", $tRequestMessage);
			
			
        }
        catch (TrxException $e)
        {
            // if($this->iLogWriter != null)
            // {
            //     $this->iLogWriter->logNewLine('错误代码：[' . $e->getCode().']    错误信息：['.
            //         $e->getMessage().']');
            // }
            throw new TrxExCeption($e->getCode(), $e->getMessage()." - ".$e->getDetailMessage());
        }
        catch (Exception $e)
        {
            // if ($this->iLogWriter != null)
            // {
            //     $this->iLogWriter->logNewLine('错误代码：['.TrxException::TRX_EXC_CODE_1999.']    错误信息：['.
            //         TrxException::TRX_EXC_MSG_1999.' - '.$e->getMessage().']');
            // }
            throw new TrxExCeption(rxException::TRX_EXC_CODE_1999, TrxException::TRX_EXC_MSG_1999.' - '.$e->getMessage());
        }
        // if ($this->iLogWriter != null)
        // {
        //     $this->iLogWriter->logNewLine("交易结束==================================================\n\n\n\n");
        //     $this->iLogWriter->closeWriter($this->MerchantConfig->getTrxLogFile());
        // }

        return $tRequestMessage;

    }

/**
     * 组成完整交易报文
     * @param aMessage 交易报文
     * @throws TrxException：报文内容不合法
     * @return 完整交易报文
     */
    private function composeRequestMessage($aMerchantNo,$aMessage) {
        $tMessage = "{\"Version\":\"V3.0.0\",\"Format\":\"JSON\",\"Merchant\":" . "{\"ECMerchantType\":\"" . "EBUS" . "\",\"MerchantID\":\"" . $this->MerchantConfig->getMerchantID($aMerchantNo) . "\"}," . "\"TrxRequest\":" . $aMessage . "}";
        return $tMessage;
    }
    /// 检查交易报文是否合法。
    protected function checkRequest()
    {
    }

    /// 发送交易报文至网上支付平台
    private function sendMessage($aMessage)
    {
        //组成<MSG>段         
        $tMessage = strval($aMessage);
        // $this->iLogWriter->logNewLine("提交网上支付平台的报文：\n$tMessage");
        //生成URL
        $tURL = $this->MerchantConfig->getTrustPayConnectMethod().'://'.$this->MerchantConfig->getTrustPayServerName();
        if(($this->MerchantConfig->getTrustPayConnectMethod() == 'https' && ($this->MerchantConfig->getTrustPayServerPort() != 443)) ||
           ($this->MerchantConfig->getTrustPayConnectMethod() == 'http' && ($this->MerchantConfig->getTrustPayServerPort() !=  80)))
        {
            $tURL .= ':'.strval($this->MerchantConfig->getTrustPayServerPort());
        }
        $tURL .= $this->MerchantConfig->getTrustPayTrxURL();
        // $this->iLogWriter->logNewLine("网上支付平台URL：[$tURL] ");
        //生成报文
        // $this->iLogWriter->logNewLine('提交交易报文：');
        // $this->iLogWriter->logNewLine($tMessage);

        $opts = array(
                'http' => array(
                    'method' => 'POST',
                    'user_agent' => 'TrustPayClient V3.0.0',
                    'protocol_version' => 1.0,
                    'header' => array('Content-Type: text/html', 'Accept: */*'),
                    'content' => $tMessage
                ), 
                'ssl' => array(
                    'verify_peer' => false
                )
        );

        $context = stream_context_create($opts);
        $tResponseMessage = file_get_contents($tURL, false, $context);
        if(!$tResponseMessage)
        {
            throw new \Think\Pay\Abchina\core\TrxException(TrxException::TRX_EXC_CODE_1202, TrxException::TRX_EXC_MSG_1202);
        }
        // $this->iLogWriter->logNewLine('返回报文：');
        // $this->iLogWriter->log('\n'.iconv("GB2312","UTF-8",$tResponseMessage));
        $tTrxResponse = new \Think\Json($tResponseMessage);
        if(!$tTrxResponse)
        {
            throw new \Think\Pay\Abchina\core\TrxException(TrxException::TRX_EXC_CODE_1205, TrxException::TRX_EXC_MSG_1205, '返回报文为空！');
        }

        return $tTrxResponse;

    }  

}


?>