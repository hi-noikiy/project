<?php

namespace Micro\Models;

//活动表
class MonthIncomeLog extends \Phalcon\Mvc\Model {

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
     * @var float
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_month_income_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'money' => 'money',
            'createTime' => 'createTime',
            'type' => 'type'
        );
    }

}
