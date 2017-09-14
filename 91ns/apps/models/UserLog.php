<?php

namespace Micro\Models;

class UserLog extends \Phalcon\Mvc\Model
{

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
    public $roomId;

    /**
     *
     * @var integer
     */
    public $count;

    /**
     *
     * @var integer
     */
    public $updateTime;


    public function getSource()
    {
        return 'pre_user_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid',
            'roomId' => 'roomId',
            'count' => 'count',
            'updateTime' => 'updateTime'
        );
    }

}
