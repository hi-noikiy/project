<?php

/**
 * 支付查询和回调共用的结果实体，存储支付结果
 * User: panwanghai
 * Date: 15/9/15
 * Time: 下午12:41
 */

require_once dirname(__FILE__) . '/KSBaseResponseEntity.php';

class KSPayResponseEntity extends KSBaseResponseEntity
{


    private $appId="null";// 应用ID，在nox 平台上申请获取的
    private $uid="null";// 用户ID
    private $payStatus="null";// 支付状态1待支付 2成功 3失败
    private $goodsTitle="null";// 商品名称
    private $goodsOrderId="null";// 商品订单
    private $goodsDesc="null";// 商品描述
    private $orderMoney="null";// 订单金额
    private $orderId="null";// 订单号
    private $orderTime="null";// 订单时间
    private $privateInfo="null";// 用户私有信息
    private $appName="null";

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param mixed $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getPayStatus()
    {
        return $this->payStatus;
    }

    /**
     * @param mixed $payStatus
     */
    public function setPayStatus($payStatus)
    {
        $this->payStatus = $payStatus;
    }

    /**
     * @return mixed
     */
    public function getGoodsTitle()
    {
        return $this->goodsTitle;
    }

    /**
     * @param mixed $goodsTitle
     */
    public function setGoodsTitle($goodsTitle)
    {
        $this->goodsTitle = $goodsTitle;
    }

    /**
     * @return mixed
     */
    public function getGoodsOrderId()
    {
        return $this->goodsOrderId;
    }

    /**
     * @param mixed $goodsOrderId
     */
    public function setGoodsOrderId($goodsOrderId)
    {
        $this->goodsOrderId = $goodsOrderId;
    }

    /**
     * @return mixed
     */
    public function getGoodsDesc()
    {
        return $this->goodsDesc;
    }

    /**
     * @param mixed $goodsDesc
     */
    public function setGoodsDesc($goodsDesc)
    {
        $this->goodsDesc = $goodsDesc;
    }

    /**
     * @return mixed
     */
    public function getOrderMoney()
    {
        return $this->orderMoney;
    }

    /**
     * @param mixed $orderMoney
     */
    public function setOrderMoney($orderMoney)
    {
        $this->orderMoney = $orderMoney;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param mixed $orderTime
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
    }

    /**
     * @return mixed
     */
    public function getPrivateInfo()
    {
        return $this->privateInfo;
    }

    /**
     * @param mixed $privateInfo
     */
    public function setPrivateInfo($privateInfo)
    {
        $this->privateInfo = $privateInfo;
    }

    /**
     * @return mixed
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * @param mixed $appName
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
    }// 应用名称

    public function toString()
    {
        return "KSPayResponseEntity [appId=" . $this->appId . ", uid=" . $this->uid . ", payStatus=" . $this->payStatus . ", goodsTitle=" . $this->goodsTitle . ", goodsOrderId=" . $this->goodsOrderId . ", goodsDesc=" . $this->goodsDesc . ", orderMoney=" . $this->orderMoney . ", orderId=" . $this->orderId
        . ", orderTime=" . $this->orderTime . ", privateInfo=" . $this->privateInfo . ", appName=" . $this->appName . "]";
    }
}
