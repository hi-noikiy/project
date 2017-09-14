<?php

namespace Micro\Models;

class RoomLog extends \Phalcon\Mvc\Model
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
    public $publicTime;

    /**
     *
     * @var integer
     */
    public $endTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $rType;


    public function getSource()
    {
        return 'pre_room_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'roomId' => 'roomId',
            'count' => 'count',
            'publicTime' => 'publicTime',
            'endTime' => 'endTime',
            'status' => 'status',
            'rType' => 'rType',
        );
    }

}