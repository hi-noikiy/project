<?php

namespace Micro\Models;

class PointsLog extends \Phalcon\Mvc\Model
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
    public $points;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $createTime;

   

    public function getSource()
    {
        return 'pre_points_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'points' => 'points', 
            'type' => 'type', 
            'createTime' => 'createTime', 
        );
    }

}
