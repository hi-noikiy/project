<?php

namespace Micro\Models;

class RoomPrivilege extends \Phalcon\Mvc\Model {

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
    public $useRole;

    /**
     *
     * @var integer
     */
    public $isAnchor;

    /**
     *
     * @var integer
     */
    public $isFamily;

    /**
     *
     * @var integer
     */
    public $isManage;

    /**
     *
     * @var integer
     */
    public $minRicherRank;

    /**
     *
     * @var integer
     */
    public $usePwd;

    /**
     *
     * @var integer
     */
    public $roomPwd;

    public function getSource() {
        return 'pre_room_privilege';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'roomId' => 'roomId',
            'uid' => 'uid',
            'useRole' => 'useRole',
            'isAnchor' => 'isAnchor',
            'isFamily' => 'isFamily',
            'isManage' => 'isManage',
            'minRicherRank' => 'minRicherRank',
            'usePwd' => 'usePwd',
            'roomPwd' => 'roomPwd',
        );
    }

}
