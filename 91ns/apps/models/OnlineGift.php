<?php

namespace Micro\Models;

class OnlineGift extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var integer
     */
    public $leftCount;

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
        return 'pre_online_gift';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'type' => 'type', 
            'leftCount' => 'leftCount', 
            'count' => 'count', 
            'updateTime' => 'updateTime',
        );
    }

}
