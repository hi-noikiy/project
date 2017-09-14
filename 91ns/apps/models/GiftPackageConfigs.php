<?php

namespace Micro\Models;

//礼包配置表
class GiftPackageConfigs extends \Phalcon\Mvc\Model {

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
    public $desc;

    /**
     *
     * @var string
     */
    public $items;

    public function getSource() {
        return 'pre_gift_package_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'name' => 'name',
            'desc' => 'desc',
            'items' => 'items',
        );
    }

}
