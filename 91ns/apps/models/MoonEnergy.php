<?php

namespace Micro\Models;

//中秋活动用户能量值数据表
class MoonEnergy extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $totalNum;

    /**
     *
     * @var integer
     */
    public $leftNum;

    /**
     *
     * @var integer
     */
    public $rank;

    /**
     *
     * @var string
     */
    public $reward;

    public function getSource() {
        return 'pre_moon_energy';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'type' => 'type',
            'totalNum' => 'totalNum',
            'leftNum' => 'leftNum',
            'rank' => 'rank',
            'reward' => 'reward',
        );
    }

}
