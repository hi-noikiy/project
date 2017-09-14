<?php

class Notify {
    /**
     * @var string 接入SDK生成的app_key
     */
    private $appKey;
    /**
     * @var string 订单号
     */
    private $orderId;

    /**
     * @var string 用户ID
     */
    private $userId;

    /**
     * @var string coins
     */
    private $coins;

    /**
     * @var string 金额
     */
    private $money;

    /**
     * @var string gameCode
     */
    private $gameCode;

    /**
     * @var string serverId
     */
    private $serverId;

    /**
     * @var string gameOrderId
     */
    private $gameOrderId;

    /**
     * @var string 扩展字段
     */
    private $ext;

    /**
     * @var string 签名
     */
    private $sign;

    /**
     * @var string 时间
     */
    private $time;

    public function setAppKey($appKey){
        $this->appKey = $appKey;
    }

    public function getAppKey(){
        return $this->appKey;
    }

    public function getOrderId(){
        return $this->orderId;
    }

    public function setOrderId($orderId){
        $this->orderId = $orderId;
    }

    public function getUserId(){
  		return $this->userId;
    }

    public function setUserId($userId){
  		$this->userId = $userId;
    }

    public function getCoins(){
  		return $this->coins;
    }

    public function setCoins($coins){
  		$this->coins = $coins;
    }

	public function getMoney(){
		  return $this->money;
	}

    public function setMoney($money){
  		$this->money = $money;
    }

    public function getGameCode(){
      return $this->gameCode;
    }

    public function setGameCode($gameCode){
        $this->gameCode = $gameCode;
    }

    public function getServerId(){
        return $this->serverId;
    }

    public function setServerId($serverId){
        $this->serverId = $serverId;
    }

    public function getGameOderId(){
        return $this->gameOrderId;
    }

    public function setGameOrderId($gameOrderId){
        $this->gameOrderId = $gameOrderId;
    }

    public function setExt($ext){
        $this->ext = $ext;
    }

    public function getTime(){
  		return $this->time;
    }

    public function setTime($time){
  		$this->time = $time;
    }

    public function getSign(){
  		return $this->sign;
    }

    public function setSign($sign){
  		$this->sign= $sign;
    }

    /**
     * 核对签名
     *
     * @return boolean
     */
    public function checkSign(){
        return ($this->getSign() == $this->makeSign());
    }
    /**
     * 生成签名
     *
     * @return string
     */
    public function makeSign(){
        return md5($this->getSignStr());
    }
    /**
     * 获取签名前的字符串, 请按照示例提供的顺序拼接字符串
     *
     * @return string
     */
    public function getSignStr(){
        $str = "";
        $str .= $this->getGameCode();

        $gameOrderId = $this->getGameOderId();
        if (!empty($gameOrderId)) {
            $str .= $gameOrderId;
        }
        $str .= $this->getMoney();
        $str .= $this->getOrderId();
        $str .= $this->getServerId();
        $str .= $this->getTime();
        $str .= $this->getUserId();

        $str .= $this->getAppKey();
        return $str;
    }
}
