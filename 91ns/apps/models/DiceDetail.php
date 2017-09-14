<?php

namespace Micro\Models;

class DiceDetail extends \Phalcon\Mvc\Model
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
    public $round;

    /**
     *
     * @var integer
     */
    public $declarer;


    /**
     *
     * @var integer
     */
    public $declareTime;

    /**
     *
     * @var integer
     */
    public $cash;

    /**
     *
     * @var integer
     */
    public $status;


    /**
     *
     * @var integer
     */
    public $result;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $startTime;

    /**
     *
     * @var integer
     */
    public $resultTime;

    /**
     *
     * @var integer
     */
    public $times;

    public function getSource()
    {
        return 'pre_dice_detail';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'roomId' => 'roomId',
            'round' => 'round',
            'declarer' => 'declarer',
            'declareTime' => 'declareTime',
            'cash' => 'cash',
            'status' => 'status',
            'createTime' => 'createTime',
            'startTime' => 'startTime',
            'result' => 'result',
            'resultTime' => 'resultTime',
            'times' => 'times',
         );
    }

}
