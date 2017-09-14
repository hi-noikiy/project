<?php

namespace Micro\Models;

class UserActiveCount extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $platform;

    /**
     *
     * @var integer
     */
    public $roomId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $endTime;

    /**
     *
     * @var integer
     */
    public $timeCount;

    /**
     *
     * @var integer
     */
    public $tempCount;

    public function getSource() {
        return 'pre_user_active_count';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'platform' => 'platform',
            'createTime' => 'createTime',
            'roomId' => 'roomId',
            'uid' => 'uid',
            'timeCount' => 'timeCount',
            'endTime' => 'endTime',
            'tempCount' => 'tempCount',
        );
    }

}
