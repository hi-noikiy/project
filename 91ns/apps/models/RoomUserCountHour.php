<?php

namespace Micro\Models;

class RoomUserCountHour extends \Phalcon\Mvc\Model {

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
    public $type;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $count;

    public function getSource() {
        return 'pre_room_user_count_hour';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'platform' => 'platform',
            'type' => 'type',
            'createTime' => 'createTime',
            'count' => 'count',
        );
    }

}
