<?php

namespace Micro\Models;

//客服后台--底薪分成表
class BasicSalary extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer		自增ID
     */
    public $id;

    /**
     *
     * @var string	底薪类型
     */
    public $type;

    /**
     *
     * @var integer  底薪到期时间
     */
    public $expirationTime;

    /**
     *
     * @var string  底薪变更信息
     */
    public $changeInfo;

    /**
     *
     * @var integer  底薪变更生效时间
     */
    public $affectTime;
    
    /**
     *
     * @var float  金额
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer  底薪是否为长期有效
     */
    public $status;

   

    public function getSource() {
        return 'inv_basic_salary';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'type' => 'type',
            'expirationTime' => 'expirationTime',
            'changeInfo' => 'changeInfo',
            'affectTime' => 'affectTime',
            'money' => 'money',
            'uid' => 'uid',
            'status' => 'status',
        );
    }

}
