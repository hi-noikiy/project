<?php

namespace Micro\Models;

class TypeConfig extends \Phalcon\Mvc\Model
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
    public $typeId;

    /**
     *
     * @var integer
     */
    public $parentTypeId;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $roomAnimate;

    /**
     *
     * @var integer
     */
    public $showStatus;

    /**
     *
     * @var integer
     */
    public $sellStatus;

    public function getSource()
    {
        return 'pre_type_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'typeId' => 'typeId', 
            'parentTypeId' => 'parentTypeId', 
            'createTime' => 'createTime',
            'description' => 'description',
            'roomAnimate' => 'roomAnimate',
            'showStatus' => 'showStatus',
            'sellStatus' => 'sellStatus'
        );
    }

}
