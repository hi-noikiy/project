<?php

namespace Micro\Models;

class BaseConfigs extends \Phalcon\Mvc\Model
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
    public $key;

    /**
     *
     * @var string
     */
    public $value;

    public function getSource()
    {
        return 'pre_base_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'key' => 'key', 
            'value' => 'value'
        );
    }

}
