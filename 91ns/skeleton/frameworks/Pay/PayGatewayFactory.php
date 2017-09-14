<?php

namespace Micro\Frameworks\Pay;

use Phalcon\DI\FactoryDefault;

use Omnipay\Common\GatewayFactory;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;


class PayGatewayFactory extends GatewayFactory
{
	protected $httpClient;
    protected $httpRequest;
    protected $di;
    protected $config;

    protected $alipayExpress;
    protected $alipayBank;
    protected $alipayQrcode;


	public function __construct() 
	{
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
	}

    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient;
        }

        return $this->httpClient;
    }

    public function getHttpRequest()
    {
        if (null === $this->httpRequest) {
            $this->httpRequest = new HttpRequest;
        }

        return $this->httpRequest;
    }

    public function getGateway($type)
    {
        switch ($type) {
            case 'AlipayExpress':
                if(null === $this->alipayExpress){
                    $this->alipayExpress = new \Micro\Frameworks\Pay\Alipay\ExpressGateway(NULL, NULL);
                    $this->alipayExpress->setPartner($this->config->pay->alipay->partner);
                    $this->alipayExpress->setKey($this->config->pay->alipay->key);
                    $this->alipayExpress->setSellerEmail($this->config->pay->alipay->seller);
                    $this->alipayExpress->setNotifyUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->notify);
                    $this->alipayExpress->setReturnUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->return); 
                    $this->alipayExpress->setCacert($this->config->pay->alipay->cacert); 
                }
                return $this->alipayExpress;    
             case 'AlipayBank':
                if(null === $this->alipayBank){
                    $this->alipayBank = new \Micro\Frameworks\Pay\Alipay\ExpressGateway(NULL, NULL);
                    $this->alipayBank->setPartner($this->config->pay->alipay->partner);
                    $this->alipayBank->setKey($this->config->pay->alipay->key);
                    $this->alipayBank->setSellerEmail($this->config->pay->alipay->seller);
                    $this->alipayBank->setNotifyUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->notify);
                    $this->alipayBank->setReturnUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->return); 
                    $this->alipayBank->setCacert($this->config->pay->alipay->cacert); 
                    $this->alipayBank->setPayMethod('bankPay'); 
                }
                return $this->alipayBank;   
             case 'AlipayQrcode':
                if(null === $this->alipayQrcode){
                    $this->alipayQrcode = new \Micro\Frameworks\Pay\Alipay\ExpressGateway(NULL, NULL);
                    $this->alipayQrcode->setPartner($this->config->pay->alipay->partner);
                    $this->alipayQrcode->setKey($this->config->pay->alipay->key);
                    $this->alipayQrcode->setSellerEmail($this->config->pay->alipay->seller);
                    $this->alipayQrcode->setNotifyUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->notify);
                    $this->alipayQrcode->setReturnUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->return); 
                    $this->alipayQrcode->setCacert($this->config->pay->alipay->cacert); 
                    $this->alipayQrcode->setQrPayMode('1'); 
                }
                return $this->alipayQrcode;

            case 'AlipayMobile':
                if(null === $this->alipayQrcode){
                    $this->alipayQrcode = new \Micro\Frameworks\Pay\Alipay\ExpressGateway(NULL, NULL);
                    $this->alipayQrcode->setPartner($this->config->pay->alipay->partner);
                    $this->alipayQrcode->setKey($this->config->pay->alipay->key);
                    $this->alipayQrcode->setSellerEmail($this->config->pay->alipay->seller);
                    $this->alipayQrcode->setNotifyUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->notify);
                    $this->alipayQrcode->setReturnUrl($this->config->webType[$this->config->channelType]['domain'].$this->config->pay->alipay->return);
                    $this->alipayQrcode->setCacert($this->config->pay->alipay->cacert);
                }

                return $this->alipayQrcode;
                break;
            default:
                return null;
        }
        return null;
    }

    public function getCompleteGateway($type)
    {
        switch ($type) {
            case 'Alipay':
                return $this->getGateway('AlipayExpress');              
            default:
                return null;
        }
        return null;
    }

    public function getBankCode($bank)
    {
        return $this->config->banckcode[$bank];
    }

}