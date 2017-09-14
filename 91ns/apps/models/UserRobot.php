<?php

namespace Micro\Models;

class UserRobot extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $nickName;

    /**
     *
     * @var string
     */
    public $platform;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var integer
     */
    public $richerLevel;


    public function getSource()
    {
        return 'pre_user_robot';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'uid' => 'uid',
            'nickName' => 'nickName',
            'platform' => 'platform',
            'avatar' => 'avatar',
            'richerLevel' => 'richerLevel',
        );
    }

}
