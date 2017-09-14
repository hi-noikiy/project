<?php

namespace Micro\Models;

class GrabLog extends \Phalcon\Mvc\Model
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
    public $seatPos;

    /**
     *
     * @var integer
     */
    public $count;


    /**
     *
     * @var integer
     */
    public $consumeLogId;

    public function getSource()
    {
        return 'pre_grab_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'seatPos' => 'seatPos',
            'count' => 'count',
            'consumeLogId' => 'consumeLogId'
        );
    }

}
