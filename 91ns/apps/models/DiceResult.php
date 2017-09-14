<?php

namespace Micro\Models;

class DiceResult extends \Phalcon\Mvc\Model
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
    public $isDeclarer;

    /**
     *
     * @var integer
     */
    public $createTime;


    /**
     *
     * @var integer
     */
    public $stakeCash;


    /**
     *
     * @var integer
     */
    public $finalCash;


    /**
     *
     * @var integer
     */
    public $resultCash;

    /**
     *
     * @var integer
     */
    public $fax;

    /**
     *
     * @var integer
     */
    public $anchorUid;


    public function getSource()
    {
        return 'pre_dice_result';
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
            'isDeclarer' => 'isDeclarer',
            'createTime' => 'createTime',
            'stakeCash' => 'stakeCash',
            'finalCash' => 'finalCash',
            'resultCash' => 'resultCash',
            'fax' => 'fax',
            'anchorUid' => 'anchorUid',
        );
    }

}
