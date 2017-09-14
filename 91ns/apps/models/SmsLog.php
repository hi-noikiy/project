<?php

namespace Micro\Models;

class SmsLog extends \Phalcon\Mvc\Model {

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
    public $telephone;

    /**
     *
     * @var integer
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $captcha;

    /**
     *
     * @var integer
     */
    public $resultcode;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $sidType;

    /**
     *
     * @var integer
     */
    public $expireTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $ip;

    public function getSource() {
        return 'pre_sms_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'telephone' => 'telephone',
            'content' => 'content',
            'type' => 'type',
            'resultcode' => 'resultcode',
            'createTime' => 'createTime',
            'expireTime' => 'expireTime',
            'sidType' => 'sidType',
            'captcha' => 'captcha',
            'status' => 'status',
            'ip' => 'ip',
        );
    }

}
