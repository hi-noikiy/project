<?php

namespace Micro\Models;

class ItemConfigs extends \Phalcon\Mvc\Model {

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
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $cash;

    /**
     *
     * @var integer
     */
    public $configName;

    public function getSource() {
        return 'pre_item_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'type' => 'type',
            'name' => 'name',
            'description' => 'description',
            'cash' => 'cash',
            'configName' => 'configName',
        );
    }

}
