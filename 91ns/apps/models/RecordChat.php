<?php

namespace Micro\Models;

class RecordChat extends \Phalcon\Mvc\Model
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
    public $roomId;

    /**
     *
     * @var string
     */
    public $chatData;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource()
    {
        return 'pre_record_chat';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'roomId' => 'roomId',
            'chatData' => 'chatData',
            'createTime' => 'createTime'
        );
    }

}
