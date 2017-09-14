<?php

namespace Micro\Models;

class Rooms extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $roomId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $isRecommend;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $announcement;

    /**
     *
     * @var integer
     */
    public $publicTime;

    /**
     *
     * @var integer
     */
    public $syncTime;

    /**
     *
     * @var integer
     */
    public $totalNum;

    /**
     *
     * @var integer
     */
    public $liveStatus;

    /**
     *
     * @var string
     */
    public $poster;

    /**
     *
     * @var integer
     */
    public $onlineNum;
    
    /**
     *
     * @var integer
     */
    public $showStatus;

    /**
     *
     * @var integer
     */
    public $robotNum;
    
    /**
     *
     * @var integer
     */
    public $roomType;

    /**
     *
     * @var integer
     */
    public $publishRoute;

    /**
     *
     * @var integer
     */
    public $nextTime;

    /**
     *
     * @var integer
     */
    public $pushTime;

    /**
     *
     * @var integer
     */
    public $useAccelarate;

    /**
     *
     * @var string
     */
    public $streamName;

    /**
     *
     * @var integer
     */
    public $isOpenVideo;

    /**
     *
     * @var integer
     */
    public $rType;

    public function getSource()
    {
        return 'pre_rooms';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'roomId' => 'roomId', 
            'uid' => 'uid', 
            'isRecommend' => 'isRecommend', 
            'title' => 'title', 
            'announcement' => 'announcement', 
            'publicTime' => 'publicTime', 
            'syncTime' => 'syncTime', 
            'liveStatus' => 'liveStatus',
            'poster' => 'poster',
            'onlineNum' => 'onlineNum',
            'showStatus' => 'showStatus',
            'robotNum' => 'robotNum',
            'roomType' => 'roomType',
            'publishRoute' => 'publishRoute',
            'useAccelarate' => 'useAccelarate',
            'nextTime' => 'nextTime',
            'pushTime' => 'pushTime',
            'totalNum' => 'totalNum',
            'streamName' => 'streamName',
            'isOpenVideo' => 'isOpenVideo',
            'rType' => 'rType',
        );
    }

}
