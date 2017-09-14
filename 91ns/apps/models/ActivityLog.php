<?php

namespace Micro\Models;

//活动表
class ActivityLog extends \Phalcon\Mvc\Model {

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
    public $activityId;

    /**
     *
     * @var integer
     */
    public $expireTime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_activities_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'activityId' => 'activityId',
            'expireTime' => 'expireTime',
            'status' => 'status',
        );
    }

}
