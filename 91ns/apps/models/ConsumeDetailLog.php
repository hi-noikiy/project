<?php

namespace Micro\Models;

class ConsumeDetailLog extends \Phalcon\Mvc\Model
{

    // public $dateStr;

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
    public $nickName;

    /**
     *
     * @var integer
     */
    public $receiveUid;

    /**
     *
     * @var integer
     */
    public $familyId;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $itemId;

    /**
     *
     * @var integer
     */
    public $count;

    /**
     *
     * @var decimal
     */
    public $amount;

    /**
     *
     * @var decimal
     */
    public $income;

    /**
     *
     * @var string
     */
    public $remark;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $isTuo;

    public function getSource()
    {
        return 'pre_consume_detail_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'nickName' => 'nickName',
            'receiveUid' => 'receiveUid',
            'familyId' => 'familyId',
            'type' => 'type',
            'itemId' => 'itemId',
            'count' => 'count',
            'amount' => 'amount',
            'income' => 'income',
            'remark' => 'remark',
            'createTime' => 'createTime',
            'isTuo' => 'isTuo'
        );
    }

}
