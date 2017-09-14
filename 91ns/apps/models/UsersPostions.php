<?php

namespace Micro\Models;

class UsersPostions extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var float
     */
    public $longitude;

    /**
     *
     * @var float
     */
    public $latitude;

    public function getSource()
    {
        return 'pre_users_postions';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'uid' => 'uid',
            'longitude' => 'longitude',
            'latitude' => 'latitude',
        );
    }

}
