<?php

namespace Micro\Models;

class DiceLog extends \Phalcon\Mvc\Model
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
    public $gameId;

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
    public $cash;

    /**
     *
     * @var integer
     */
    public $createTime;


    /**
     *
     * @var integer
     */
    public $result;


    /**
     *
     * @var integer
     */
    public $fax;


    /**
     *
     * @var integer
     */
    public $resultTime;



    public function getSource()
    {
        return 'pre_dice_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'gameId' => 'gameId',
            'uid' => 'uid',
            'type' => 'type',
            'cash' => 'cash',
            'createTime' => 'createTime',
            'result' => 'result',
            'resultTime' => 'resultTime',
            'fax' => 'fax',
         );
    }

}
