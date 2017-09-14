<?php

/**
 * 查询订单时作为参数传入的实体
 * User: pan
 * Date: 15/9/15
 * Time: 下午12:44
 */

require_once dirname(__FILE__) . '/KSBaseEntity.php';

class KSQueryPayEntity extends KSBaseEntity
{
    private $appId="null";// 应用ID，在nox 平台上申请获取的
    private $goodsOrderId="null";// 商品订单
    private $orderId="null";

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
    }// nox订单

    public function toString()
    {
        return "KSQueryPayEntity [appId=" . $this->appId . ", goodsOrderId=" . $this->goodsOrderId . ", orderId=" . $this->orderId . "]";
    }

}