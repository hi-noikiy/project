<?php

namespace Micro\Models;

//客服后台--规则配置表
class InvRuleLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $symbol;

    /**
     *
     * @var integer
     */
    public $value;

    public function getSource() {
        return 'inv_rule_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'symbol' => 'symbol',
            'value' => 'value',
        );
    }

}
