<?php

namespace Micro\Models;

//用户聊币来源记录表
class CashLog extends \Phalcon\Mvc\Model
{

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
     * @var integer
     */
    public $num;

    /**
     *
     * @var integer
     */
    public $source;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $orderId;

    public function getSource()
    {
        return 'pre_cash_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'num' => 'num',
            'source' => 'source',
            'createTime' => 'createTime',
            'orderId' => 'orderId',
        );
    }

}
