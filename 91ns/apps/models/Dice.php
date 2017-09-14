<?php

namespace Micro\Models;

class Dice extends \Phalcon\Mvc\Model
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


    public function getSource()
    {
        return 'pre_dice';
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
        );
    }

}
