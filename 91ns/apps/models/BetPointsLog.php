<?php

namespace Micro\Models;

class BetPointsLog extends \Phalcon\Mvc\Model
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
    public $times;

    /**
     *
     * @var integer
     */
    public $nums;

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

    /**
     *
     * @var integer
     */
    public $platform;

    /**
     *
     * @var integer
     */
    public $kind;

   

    public function getSource()
    {
        return 'pre_bet_points_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'times' => 'times',
            'nums' => 'nums',
            'type' => 'type', 
            'createTime' => 'createTime', 
            'platform' => 'platform', 
            'kind' => 'kind', 
        );
    }

}
