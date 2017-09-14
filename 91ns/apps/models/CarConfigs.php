<?php

namespace Micro\Models;

class CarConfigs extends \Phalcon\Mvc\Model
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

    /**
     *
     * @var integer
     */
    public $configName;

    /**
     *
     * @var integer
     */
    public $hasBigCar;

    /**
     *
     * @var integer
     */
    public $positionX1;

    /**
     *
     * @var integer
     */
    public $positionY1;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var integer
     */
    public $positionX2;

    /**
     *
     * @var integer
     */
    public $positionY2;

    /**
     *
     * @var integer
     */
    public $appSpecial;


    public function getSource()
    {
        return 'pre_car_configs';
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
            'status' => 'status',
            'configName' => 'configName',
            'hasBigCar' => 'hasBigCar',
            'positionX1' => 'positionX1',
            'positionY1' => 'positionY1',
            'sort' => 'sort',
            'positionX2' => 'positionX2',
            'positionY2' => 'positionY2',
            'appSpecial' => 'appSpecial',
        );
    }

}
