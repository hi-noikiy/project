<?php

namespace Micro\Models;

//中秋活动博饼概率表
class MoonOdds extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_moon_odds';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'code' => 'code',
            'status' => 'status',
        );
    }

}
