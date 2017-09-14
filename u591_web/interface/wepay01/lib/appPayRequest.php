<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 自定义app支付类
 * ==============================================
 * @date: 2016-11-24
 * @author: luoxue
 * @version:
 */
include_once 'WxPayDataBase.php';
class appPayRequest extends WxPayDataBase {
    //应用appid
    public $appId;
    //商户支付密钥
    public $appKey;
    //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载
    public $sslcertPath;
    public $sslkeyPath;
    //默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
    public $curlProxyHost;
    public $curlProxyPort;
    //上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
    public $reportLevel;
    //trade_type为JSAPI时，openid为必填参数
    public $openId;
    //trade_type为JSAPI时，product_id为必填参数！
    public $productId;
    //应用商户号
    public $MCHID;
    public $body;
    public $attach;
    public $outTradeNo;
    public $amont; //单位分
    public $timeStart; //YmdHis
    public $timeExpire; //YmdHis
    public $goodsTag;
    //public $notifyUrl;
    public $tradeType;
    /**
     * 设置微信应用appid
     * @param $appid
     **/
    public function setAppId($appid){
        $this->appId = $appid;
    }

    public function setAppKey($appKey){
        $this->appKey = $appKey;
    }

    public function setSslcertPath($sslcertpath){
        $this->sslcertPath = $sslcertpath;
    }
    public function setSslkeyPath($sslkeyPath){
        $this->sslkeyPath = $sslkeyPath;
    }

    public function setCurlProxyHost($host='0.0.0.0'){
        $this->curlProxyHost = $host;
    }

    public function setCurlProxyPort($port = 0){
        $this->curlProxyPort = $port;
    }
    public function setReportLevel($level = 1){
        $this->reportLevel = $level;
    }


    public function setOpenId($openId){
        $this->openId = $openId;
    }

    public function setProductId($productId){
        $this->productId = $productId;
    }
    public function setMCHID($mchid){
        $this->MCHID = $mchid;
    }

    public function setBody($body){
        $this->body = $body;
    }

    public function setAttach($attach){
        $this->attach = $attach;
    }

    public function setOutTradeNo($outTradeNo){
        $this->outTradeNo = $outTradeNo;
    }

    public function setAmont($amont){
        $this->amont = $amont;
    }
    public function setTimeStart($timeStart = null){
        if($timeStart === null)
            $this->timeStart = date("YmdHis");
        else
            $this->timeStart = $timeStart;
    }
    public function setTimeExpire($timeExpire = 600){
        $start = $this->timeStart ? $this->timeStart : date('YmdHis');
        $t = strtotime($start) + $timeExpire;
        $this->timeExpire = date('YmdHis', $t);
    }

    public function setGoodsTag($tag = '充值'){
        $this->goodsTag = $tag;
    }

    public function setNotifyUrl(){
        return 'http://gunweb.u591.com:83/interface/wepay01/callback.php';
    }

    public function setTradeType($tradeType = 'APP'){
        $this->tradeType = $tradeType;
    }

    public function getvalues(){
        $this->values['appid']          = $this->appId;
        $this->values['mch_id']         = $this->MCHID;
        $this->values['body']           = $this->body;
        $this->values['attach']         = $this->attach;
        $this->values['out_trade_no']   = $this->outTradeNo;
        $this->values['total_fee']      = $this->amont;
        $this->values['time_start']     = $this->timeStart;
        $this->values['time_expire']    = $this->timeExpire;
        $this->values['goods_tag']      = $this->goodsTag;
        $this->values['notify_url']     = $this->setNotifyUrl();
        $this->values['trade_type']     = $this->tradeType;
        if($this->tradeType == 'JSAPI'){
            $this->values['openid'] = $this->openId;
        } elseif ($this->tradeType == 'NATIVE'){
            $this->values['product_id'] = $this->productId;
        }
        return $this->values;
    }

    /**
     * 设置APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @param string $value
     **/
    public function setSpbillCreateIp($value){
        $this->values['spbill_create_ip'] = $value;
    }
    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function setNonceStr($value){
        $this->values['nonce_str'] = $value;
    }
    /**
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string $str 产生的随机字符串
     */
    public function getNonceStr($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 设置代理host
     */
    public function getProxyHost(){
        if(!isset($this->curlProxyHost))
            return '0.0.0.0';
        return $this->curlProxyHost;
    }
    /**
     * 设置代理port
     */
    public function getProxyPort(){
        if(!isset($this->curlProxyPort))
            return 0;
        return $this->curlProxyPort;
    }
    /**
     *cert证书
     */
    public function getSslcertPath(){
        if(!isset($this->sslcertPath))
            return '../cert/apiclient_cert.pem';
        return $this->sslcertPath;
    }
    public function getSslkeyPath(){
        if(!isset($this->sslkeyPath))
            return '../cert/apiclient_key.pem';
        return $this->sslkeyPath;
    }
    /**
     * 判断trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 是否存在
     * @return true 或 false
     **/
    public function isOpenidSet() {
        return array_key_exists('openid', $this->values);
    }

    /**
     * 判断trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。是否存在
     * @return true 或 false
     **/
    public function isProductidSet() {
        return array_key_exists('product_id', $this->values);
    }

    public function unifiedOrder($timeOut = 1){
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //检测必填参数
        if(!$this->outTradeNo) {
            return '缺少统一支付接口必填参数out_trade_no.';
        }else if(!$this->body){
            return '缺少统一支付接口必填参数body.';
        }else if(!$this->amont) {
            return '缺少统一支付接口必填参数total_fee.';
        }else if(!$this->tradeType) {
            return '缺少统一支付接口必填参数trade_type.';
        }
        //关联参数
        if($this->tradeType == "JSAPI" && !$this->isOpenidSet()){
            return 'trade_type为JSAPI时，openid为必填参数.';
        }
        if($this->tradeType == "NATIVE" && !$this->isProductidSet()){
            return 'trade_type为JSAPI时，product_id为必填参数.';
        }
        $this->setSpbillCreateIp($_SERVER['REMOTE_ADDR']);//终端ip
        $this->setNonceStr($this->getNonceStr());//随机字符串
        $this->getvalues();
        //签名
        $this->SetSign($this->appKey);
        $xml = $this->ToXml();
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        WxPayResults::setKey($this->appKey);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayOrderQuery $inputObj
     * @param string $appkey
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function orderQuery($inputObj,$appkey, $timeOut = 6) {
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串
        $inputObj->SetSign($appkey);//签名

        $xml = $inputObj->ToXml();
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        WxPayResults::setKey($appkey);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间
        return $result;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond(){
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }

    /**
     *
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $url
     * @param int $startTimeStamp
     * @param array $data
     */
    private static function reportCostTime($url, $startTimeStamp, $data){
        $ob = new self();
        //如果不需要上报数据
        if($ob->reportLevel == 0){
            return;
        }
        //如果仅失败上报
        if($ob->reportLevel == 1 &&
            array_key_exists("return_code", $data) &&
            $data["return_code"] == "SUCCESS" &&
            array_key_exists("result_code", $data) &&
            $data["result_code"] == "SUCCESS")
        {
            return;
        }

        //上报逻辑
        $endTimeStamp = self::getMillisecond();

        $objInput = new WxPayReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        //返回状态码
        if(array_key_exists("return_code", $data)){
            $objInput->SetReturn_code($data["return_code"]);
        }
        //返回信息
        if(array_key_exists("return_msg", $data)){
            $objInput->SetReturn_msg($data["return_msg"]);
        }
        //业务结果
        if(array_key_exists("result_code", $data)){
            $objInput->SetResult_code($data["result_code"]);
        }
        //错误代码
        if(array_key_exists("err_code", $data)){
            $objInput->SetErr_code($data["err_code"]);
        }
        //错误代码描述
        if(array_key_exists("err_code_des", $data)){
            $objInput->SetErr_code_des($data["err_code_des"]);
        }
        //商户订单号
        if(array_key_exists("out_trade_no", $data)){
            $objInput->SetOut_trade_no($data["out_trade_no"]);
        }
        //设备号
        if(array_key_exists("device_info", $data)){
            $objInput->SetDevice_info($data["device_info"]);
        }

        try{
            self::report($objInput);
        } catch (WxPayException $e){
            //不做任何处理
        }
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     * @return json $data;
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ob = new self();
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if($ob->getProxyHost() != "0.0.0.0"
            && $ob->getProxyPort() != 0){
            curl_setopt($ch,CURLOPT_PROXY, $ob->getProxyHost());
            curl_setopt($ch,CURLOPT_PROXYPORT, $ob->getProxyPort());
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $ob->getSslcertPath());
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $ob->getSslkeyPath());
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);


        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
}