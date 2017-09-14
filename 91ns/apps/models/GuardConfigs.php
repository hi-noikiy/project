<?php

namespace Micro\Models;

class GuardConfigs extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $name;
    /**
     *
     * @var string
     */
    public $rightlist;
    public function getSource()
    {
        return 'pre_guard_configs';
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
            'name' => 'name',
            'rightlist' => 'rightlist',
        );
    }

}
