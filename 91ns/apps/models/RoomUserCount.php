<?php

namespace Micro\Models;

class RoomUserCount extends \Phalcon\Mvc\Model {

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
    public $count;

    public function getSource() {
        return 'pre_room_user_count';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'platform' => 'platform',
            'count' => 'count',
        );
    }

}
