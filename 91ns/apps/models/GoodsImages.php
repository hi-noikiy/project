<?php

namespace Micro\Models;

class GoodsImages extends \Phalcon\Mvc\Model
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
    public $goodsId;

    /**
     *
     * @var integer
     */
    public $imgUrl;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $orderType;

   

    public function getSource()
    {
        return 'pre_goods_images';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'goodsId' => 'goodsId', 
            'imgUrl' => 'imgUrl', 
            'createTime' => 'createTime', 
            'status' => 'status', 
            'orderType' => 'orderType', 
        );
    }

}
