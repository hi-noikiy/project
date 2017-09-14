<?php

namespace Micro\Models;

class NoticeConfigs extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $contents;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource()
    {
        return 'pre_notice_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'title' => 'title', 
            'contents' => 'contents', 
            'image' => 'image', 
            'createTime' => 'createTime'
        );
    }

}
