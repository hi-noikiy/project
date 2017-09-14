<?php

namespace Micro\Models;

class GroupMember extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $groupId;

    /**
     *
     * @var string
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $createTime;

    public function getSource() {
        return 'pre_group_member';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'groupId' => 'groupId',
            'uid' => 'uid',
            'createTime' => 'createTime',
        );
    }

}
