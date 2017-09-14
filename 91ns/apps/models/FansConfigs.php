<?php

namespace Micro\Models;

class FansConfigs extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $higher;

    /**
     *
     * @var integer
     */
    public $lower;

    /**
     *
     * @var integer
     */
    public $level;

    public function getSource()
    {
        return 'pre_fans_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'higher' => 'higher', 
            'lower' => 'lower', 
            'level' => 'level'
        );
    }

}
