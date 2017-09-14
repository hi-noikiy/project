<?php

namespace Micro\Models;

//活动表
class ActivityIncomeLog extends \Phalcon\Mvc\Model {

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
     * @var decimal
     */
    public $money;

    /**
     *
     * @var string
     */
    public $remark;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $proportion;

    public function getSource() {
        return 'pre_activity_income_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'money' => 'money',
            'remark' => 'remark',
            'type' => 'type',
            'createTime' => 'createTime',
            'proportion' => 'proportion'
        );
    }

}
