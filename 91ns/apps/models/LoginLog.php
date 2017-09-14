<?php

namespace Micro\Models;

class LoginLog extends \Phalcon\Mvc\Model {

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
    public $parentType;

    /**
     *
     * @var integer
     */
    public $subType;

    public function getSource() {
        return 'pre_login_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'createTime' => 'createTime',
            'ip' => 'ip',
            'parentType' => 'parentType',
            'subType' => 'subType',
        );
    }

}
