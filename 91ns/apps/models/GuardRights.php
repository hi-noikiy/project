<?php

namespace Micro\Models;

class GuardRights extends \Phalcon\Mvc\Model {

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
    public $img;

    /**
     *
     * @var integer
     */
    public $orderType;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $lastTime;

    public function getSource() {
        return 'pre_guard_rights';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'name' => 'name',
            'desc' => 'desc',
            'img' => 'img',
            'orderType' => 'orderType',
            'type' => 'type',
            'lastTime' => 'lastTime',
        );
    }

}
