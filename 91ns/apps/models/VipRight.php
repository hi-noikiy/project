<?php

namespace Micro\Models;

class VipRight extends \Phalcon\Mvc\Model {

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
    public $lasttime;

    public function getSource() {
        return 'inv_vip_right';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'name' => 'name',
            'des' => 'des',
            'img' => 'img',
            'lasttime' => 'lasttime',
        );
    }

}
