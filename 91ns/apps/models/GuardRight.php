<?php

namespace Micro\Models;

class GuardRight extends \Phalcon\Mvc\Model {

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
    public $des;

    /**
     *
     * @var string
     */
    public $img;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $lasttime;

    public function getSource() {
        return 'pre_guard_right';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'name' => 'name',
            'des' => 'des',
            'type' => 'type',
            'img' => 'img',
            'lasttime' => 'lasttime',
        );
    }

}
