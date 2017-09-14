<?php

namespace Micro\Models;

class GrabseatLog extends \Phalcon\Mvc\Model
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
    public $anchorUid;

    /**
     *
     * @var integer
     */
    public $seatUid;

    /**
     *
     * @var integer
     */
    public $seatPos;

    /**
     *
     * @var integer
     */
    public $seatCount;

    /**
     *
     * @var integer
     */
    public $updateTime;

    public function getSource()
    {
        return 'pre_grabseat_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'anchorUid' => 'anchorUid', 
            'seatUid' => 'seatUid', 
            'seatPos' => 'seatPos', 
            'seatCount' => 'seatCount', 
            'updateTime' => 'updateTime'
        );
    }

}
