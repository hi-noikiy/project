<?php

namespace Micro\Models;

//活动表
class ActivityRound extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $times;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $startTime;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_activity_round';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'times' => 'times',
            'createTime' => 'createTime',
            'startTime' => 'startTime',
            'type' => 'type',
        );
    }

}
