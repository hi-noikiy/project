<?php

namespace Micro\Models;

//活动表
class ActivityResultLog extends \Phalcon\Mvc\Model {

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
    public $startTime;

    /**
     *
     * @var integer
     */
    public $endTime;

    /**
     *
     * @var integer
     */
    public $rankInfo;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_activity_result_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'times' => 'times',
            'startTime' => 'startTime',
            'endTime' => 'endTime',
            'rankInfo' => 'rankInfo',
            'createTime' => 'createTime',
            'type' => 'type',
        );
    }

}