<?php

namespace Micro\Models;

class RedPackage extends \Phalcon\Mvc\Model {

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
    public $roomId;

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
    public $initTime;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $limit;

    /**
     *
     * @var integer
     */
    public $sumMoney;

    /**
     *
     * @var integer
     */
    public $num;

    /**
     *
     * @var integer
     */
    public $returnTime;

    /**
     *
     * @var integer
     */
    public $returnMoney;

    public function getSource() {
        return 'pre_red_packet';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'roomId' => 'roomId',
            'createTime' => 'createTime',
            'status' => 'status',
            'initTime' => 'initTime',
            'type' => 'type',
            'sumMoney' => 'sumMoney',
            'limit' => 'limit',
            'num' => 'num',
            'returnMoney' => 'returnMoney',
            'returnTime' => 'returnTime',
        );
    }

}
