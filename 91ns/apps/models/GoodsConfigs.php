<?php

namespace Micro\Models;

class GoodsConfigs extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $totalNums;

    /**
     *
     * @var integer
     */
    public $perPoint;

    /**
     *
     * @var integer
     */
    public $perCash;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $isShow;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $img;

    /**
     *
     * @var integer
     */
    public $orderType;

    public function getSource()
    {
        return 'pre_goods_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'price' => 'price', 
            'description' => 'description', 
            'totalNums' => 'totalNums', 
            'perPoint' => 'perPoint', 
            'perCash' => 'perCash', 
            'type' => 'type',
            'isShow' => 'isShow', 
            'createTime' => 'createTime', 
            'img' => 'img', 
            'orderType' => 'orderType', 
        );
    }

}
