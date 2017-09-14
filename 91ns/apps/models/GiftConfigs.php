<?php

namespace Micro\Models;

class GiftConfigs extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $vipLevel;

    /**
     *
     * @var integer
     */
    public $richerLevel;

    /**
     *
     * @var integer
     */
    public $typeId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $coin;

    /**
     *
     * @var integer
     */
    public $cash;

    /**
     *
     * @var integer
     */
    public $recvCoin;

    /**
     *
     * @var integer
     */
    public $discount;

    /**
     *
     * @var integer
     */
    public $freeCount;

    /**
     *
     * @var integer
     */
    public $littleFlag;

    /**
     *
     * @var integer
     */
    public $orderType;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $configName;

    /**
     *
     * @var string
     */
    public $guardFlag;

     /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $tagPic;

    /**
     *
     * @var string
     */
    public $isDefault;

    /**
     *
     * @var string
     */
    public $tagDesc;
    /**
     *
     * @var string
     */
    public $littleSwf;
    
    public function getSource() {
        return 'pre_gift_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'vipLevel' => 'vipLevel',
            'richerLevel' => 'richerLevel',
            'typeId' => 'typeId',
            'name' => 'name',
            'coin' => 'coin',
            'cash' => 'cash',
            'recvCoin' => 'recvCoin',
            'discount' => 'discount',
            'freeCount' => 'freeCount',
            'littleFlag' => 'littleFlag',
            'orderType' => 'orderType',
            'createTime' => 'createTime',
            'configName' => 'configName',
            'guardFlag' => 'guardFlag',
            'description' => 'description',
            'tagPic' => 'tagPic',
            'isDefault' => 'isDefault',
            'tagDesc' => 'tagDesc',
            'littleSwf' => 'littleSwf',
         );
    }

}
