<?php

namespace Micro\Models;

class Videos extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $streamName;

    /**
     *
     * @var integer
     */
    public $isUsing;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $publicTime;

    /**
     *
     * @var integer
     */
    public $videoPic;

    public function getSource()
    {
        return 'pre_videos';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'streamName' => 'streamName', 
            'isUsing' => 'isUsing', 
            'createTime' => 'createTime', 
            'status' => 'status', 
            'publicTime' => 'publicTime', 
            'videoPic' => 'videoPic', 
        );
    }

}
