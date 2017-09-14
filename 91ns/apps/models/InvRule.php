<?php

namespace Micro\Models;

//客服后台--规则表
class InvRule extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $rids;

    /**
     *
     * @var integer
     */
    public $conditions;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $value;

    /**
     *
     * @var integer
     */
    public $conType;

    /**
     *
     * @var integer
     */
    public $conValue;

    /**
     *
     * @var integer
     */
    public $sort;

    public function getSource() {
        return 'inv_rule';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'rids' => 'rids',
            'conditions' => 'conditions',
            'type' => 'type',
            'value' => 'value',
            'conType' => 'conType',
            'conValue' => 'conValue',
            'sort' => 'sort',
        );
    }

}
