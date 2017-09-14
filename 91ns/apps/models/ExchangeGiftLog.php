<?php

namespace Micro\Models;

class ExchangeGiftLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $code;

    /**
     *
     * @var string
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $expireTime;

    /**
     *
     * @var int
     */
    public $giftPackageId;

    /**
     *
     * @var int
     */
    public $uid;

    /**
     *
     * @var int
     */
    public $getTime;

    public function getSource() {
        return 'pre_exchange_gift_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'code' => 'code',
            'createTime' => 'createTime',
            'expireTime' => 'expireTime',
            'giftPackageId' => 'giftPackageId',
            'uid' => 'uid',
            'getTime' => 'getTime',
        );
    }

}
