<?php

namespace Micro\Models;

class MobileToken extends \Phalcon\Mvc\Model {

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
    public $token;

    /**
     *
     * @var integer
     */
    public $device;

    /**
     *
     * @var integer
     */
    public $expireTime;

    public function getSource() {
        return 'pre_mobile_token';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'token' => 'token',
            'device' => 'device',
            'expireTime' => 'expireTime',
        );
    }

}
