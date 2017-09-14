<?php

namespace Micro\Models;

class Suggestions extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $content;

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
     * @var string
     */
    public $log;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $email;
    /**
     *
     * @var string
     */
    public $devInfo;
    /**
     *
     * @var string
     */
    public $qq;    

    /**
     *
     * @var integer
     */
    public $addTime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource()
    {
        return 'inv_suggestions';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'type' => 'type', 
            'content' => 'content', 
            'pic1' => 'pic1', 
            'pic2' => 'pic2', 
            'pic3' => 'pic3', 
            'log' => 'log', 
            'mobile' => 'mobile', 
            'email' => 'email', 
            'qq' => 'qq',
            'addTime' => 'addTime',
            'status' => 'status',
            'devInfo' => 'devInfo',
        );
    }

}
