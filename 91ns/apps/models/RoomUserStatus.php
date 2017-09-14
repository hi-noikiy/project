<?php

namespace Micro\Models;

class RoomUserStatus extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

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
    public $level;

    /**
     *
     * @var integer
     */
    public $forbid;

    /**
     *
     * @var integer
     */
    public $kick;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $kickTimeLine;

    /**
     *
     * @var integer
     */
    public $kickLeftTime;

    /**
     *
     * @var string
     */
    public $hisRemarks;

    /**
     *
     * @var string
     */
    public $remarks;

    /**
     *
     * @var integer
     */
    public $levelTimeLine;

    public function getSource() {
        return 'pre_room_user_status';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'roomId' => 'roomId',
            'uid' => 'uid',
            'level' => 'level',
            'forbid' => 'forbid',
            'kick' => 'kick',
            'createTime' => 'createTime',
            'kickTimeLine' => 'kickTimeLine',
            'kickLeftTime' => 'kickLeftTime',
            'hisRemarks' => 'hisRemarks',
            'remarks' => 'remarks',
            'levelTimeLine' => 'levelTimeLine',
        );
    }

}
