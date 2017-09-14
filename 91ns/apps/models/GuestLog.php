<?php

namespace Micro\Models;

class GuestLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uuid;

    /**
     *
     * @var integer
     */
    public $parentType;

    /**
     *
     * @var integer
     */
    public $subType;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource() {
        return 'pre_guest_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uuid' => 'uuid',
            'parentType' => 'parentType',
            'subType' => 'subType',
            'createTime' => 'createTime',
        );
    }

}
