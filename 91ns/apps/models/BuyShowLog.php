<?php

namespace Micro\Models;

class BuyShowLog extends \Phalcon\Mvc\Model
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
    public $buyUid;

    /**
     *
     * @var integer
     */
    public $showId;

    /**
     *
     * @var string
     */
    public $showName;

    /**
     *
     * @var integer
     */
    public $showPrice;

    /**
     *
     * @var integer
     */
    public $showType;

    /**
     *
     * @var integer
     */
    public $buyMethod;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $isDelete;

   

    public function getSource()
    {
        return 'pre_buy_show_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'buyUid' => 'buyUid',
            'showId' => 'showId',
            'showName' => 'showName',
            'showPrice' => 'showPrice',
            'showType' => 'showType',
            'buyMethod' => 'buyMethod',
            'createTime' => 'createTime',
            'status' => 'status',
            'isDelete' => 'isDelete',
        );
    }

}
