<?php

namespace Micro\Models;

//中秋活动掷骰子日志
class MoonBobingLog extends \Phalcon\Mvc\Model {

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
    public $moonValue;

    /**
     *
     * @var integer
     */
    public $points;

    /**
     *
     * @var integer
     */
    public $resultCode;

    /**
     *
     * @var integer
     */
    public $isChampion;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource() {
        return 'pre_moon_bobing_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'moonValue' => 'moonValue',
            'points' => 'points',
            'resultCode' => 'resultCode',
            'isChampion' => 'isChampion',
            'createTime' => 'createTime',
        );
    }

}
