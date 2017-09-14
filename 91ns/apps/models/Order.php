<?php

namespace Micro\Models;

//用户下单记录表
class Order extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $orderId;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $cashNum;

    /**
     *
     * @var decimal
     */
    public $totalFee;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $payType;

    /**
     *
     * @var integer
     */
    public $payTime;

    /**
     *
     * @var string
     */
    public $tradeNo;

    /**
     *
     * @var integer
     */
    public $orderType;

    /**
     *
     * @var integer
     */
    public $receiveUid;

    /**
     *
     * @var integer
     */
    public $isDelete;

    public function getSource() {
        return 'pre_order';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'orderId' => 'orderId',
            'createTime' => 'createTime',
            'cashNum' => 'cashNum',
            'totalFee' => 'totalFee',
            'status' => 'status',
            'payType' => 'payType',
            'payTime' => 'payTime',
            'tradeNo' => 'tradeNo',
            'orderType' => 'orderType',
            'receiveUid' => 'receiveUid',
            'isDelete' => 'isDelete'
        );
    }

}
