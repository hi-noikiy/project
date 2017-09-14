<?php

namespace Micro\Models;

class RoomAdminLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $operateUid;

    /**
     *
     * @var integer
     */
    public $roomId;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $beOperateUid;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource() {
        return 'pre_room_admin_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'operateUid' => 'operateUid',
            'roomId' => 'roomId',
            'type' => 'type',
            'beOperateUid' => 'beOperateUid',
            'createTime' => 'createTime',
        );
    }

}
