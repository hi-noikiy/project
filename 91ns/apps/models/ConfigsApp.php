<?php

namespace Micro\Models;

class ConfigsApp extends \Phalcon\Mvc\Model
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

    /**
     *
     * @var string
     */
    public $remark;

    public function getSource()
    {
        return 'pre_configs_app';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'key' => 'key', 
            'value' => 'value',
            'remark' => 'remark',
        );
    }

}
