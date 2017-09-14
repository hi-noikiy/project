<?php

namespace Micro\Models;

class FoodConfigs extends \Phalcon\Mvc\Model
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
    public $typeId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $orderType;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource()
    {
        return 'pre_food_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'typeId' => 'typeId', 
            'name' => 'name', 
            'description' => 'description', 
            'price' => 'price', 
            'orderType' => 'orderType', 
            'status' => 'status'
        );
    }

}
