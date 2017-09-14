<?php

namespace Micro\Models;

class VipConfigs extends \Phalcon\Mvc\Model
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
    public $level;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $carId;

    /**
     *
     * @var integer
     */
    public $lower;

    /**
     *
     * @var integer
     */
    public $higher;

    /**
     *
     * @var string
     */
    public $rightlist;

    public function getSource()
    {
        return 'pre_vip_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'level' => 'level', 
            'description' => 'description', 
            'carId' => 'carId', 
            'lower' => 'lower',
            'higher' => 'higher',
            'rightlist' => 'rightlist',
        );
    }

}
