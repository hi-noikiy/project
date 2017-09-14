<?php

namespace Micro\Models;

class VideoReview extends \Phalcon\Mvc\Model
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
    public $createTime;

    /**
     *
     * @var integer
     */
    public $publicTime;

    /**
     *
     * @var integer
     */
    public $remark;

    public function getSource()
    {
        return 'pre_video_review';
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
            'createTime' => 'createTime', 
            'publicTime' => 'publicTime', 
            'remark' => 'remark', 
        );
    }

}
