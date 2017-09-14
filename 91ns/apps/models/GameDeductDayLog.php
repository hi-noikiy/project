<?php

namespace Micro\Models;

//活动表
class GameDeductDayLog extends \Phalcon\Mvc\Model {

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
    public $cash;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $remark;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource() {
        return 'pre_game_deduct_day_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'cash' => 'cash',
            'type' => 'type',
            'remark' => 'remark',
            'createTime' => 'createTime',
        );
    }

}
