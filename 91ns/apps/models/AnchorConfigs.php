<?php

namespace Micro\Models;

class AnchorConfigs extends \Phalcon\Mvc\Model
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

    /**
     *
     * @var integer
     */
    public $roomLimitNum;

    public function getSource()
    {
        return 'pre_anchor_configs';
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
            'level' => 'level',
            'roomLimitNum' => 'roomLimitNum'
        );
    }

}
