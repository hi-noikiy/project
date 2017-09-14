<?php

namespace Micro\Models;

class ShowRoomLog extends \Phalcon\Mvc\Model
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
    public $startTime;

    /**
     *
     * @var integer
     */
    public $endTime;


    public function getSource()
    {
        return 'pre_show_room_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'roomId' => 'roomId',
            'startTime' => 'startTime',
            'endTime' => 'endTime'
        );
    }

}