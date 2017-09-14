<?php

namespace Micro\Models;

class RoomGiftLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $roomId;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $giftId;

    /**
     *
     * @var string
     */
    public $giftNum;

    /**
     *
     * @var string
     */
    public $giftName;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $priceType;

    /**
     *
     * @var string
     */
    public $configName;

    /**
     *
     * @var integer
     */
    public $createTime;

    public function getSource() {
        return 'pre_room_gift_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'roomId' => 'roomId',
            'type' => 'type',
            'uid' => 'uid',
            'giftId' => 'giftId',
            'configName' => 'configName',
            'giftNum' => 'giftNum',
            'giftName' => 'giftName',
            'price' => 'price',
            'priceType' => 'priceType',
            'createTime' => 'createTime',
        );
    }

}
