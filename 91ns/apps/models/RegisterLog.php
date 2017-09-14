<?php

namespace Micro\Models;

class RegisterLog extends \Phalcon\Mvc\Model {

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
    public $uid;

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

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var integer
     */
    public $platform;

    public function getSource() {
        return 'pre_register_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uuid' => 'uuid',
            'uid' => 'uid',
            'parentType' => 'parentType',
            'subType' => 'subType',
            'createTime' => 'createTime',
            'ip' => 'ip',
            'platform' => 'platform',
        );
    }

}
