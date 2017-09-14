<?php

namespace Micro\Models;

class SignLog extends \Phalcon\Mvc\Model
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
    public $createTime;

    /**
     *
     * @var integer
     */
    public $conTimes;

    public function getSource()
    {
        return 'pre_sign_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid',
            'createTime' => 'createTime', 
            'conTimes' => 'conTimes'
        );
    }

}
