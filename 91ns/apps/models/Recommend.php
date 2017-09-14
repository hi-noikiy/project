<?php

namespace Micro\Models;

class Recommend extends \Phalcon\Mvc\Model {

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
    public $url;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $proportion;

    /**
     *
     * @var integer
     */
    public $validity;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $remark;

    /**
     *
     * @var integer
     */
    public $utmSource;

    /**
     *
     * @var integer
     */
    public $utmMedium;

    /**
     *
     * @var integer
     */
    public $longUrl;

    /**
     *
     * @var integer
     */
    public $tinyUrl;

    /**
     *
     * @var integer
     */
    public $imgPath;
    
    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_recommend';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'url' => 'url',
            'createTime' => 'createTime',
            'proportion' => 'proportion',
            'validity' => 'validity',
            'status' => 'status',
            'remark' => 'remark',
            'utmSource' => 'utmSource',
            'utmMedium' => 'utmMedium',
            'longUrl' => 'longUrl',
            'tinyUrl' => 'tinyUrl',
            'imgPath' => 'imgPath',
            'type' => 'type',
        );
    }

}
