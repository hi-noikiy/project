<?php

namespace Micro\Models;

class Inform extends \Phalcon\Mvc\Model
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
    public $targetId;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $addTime;

    /**
     *
     * @var string
     */
    public $pic1;

    /**
     *
     * @var string
     */
    public $pic2;

    /**
     *
     * @var string
     */
    public $pic3;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource()
    {
        return 'inv_inform';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'targetId' => 'targetId', 
            'type' => 'type', 
            'content' => 'content', 
            'addTime' => 'addTime', 
            'pic1' => 'pic1',
            'pic2' => 'pic2',
            'pic3' => 'pic3',
            'status' => 'status'
        );
    }

}
