<?php

namespace Micro\Models;

//活动表
class ChangeLog extends \Phalcon\Mvc\Model {

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
     * @var string
     */
    public $orderNum;

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

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_change_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'orderNum' => 'orderNum',
            'money' => 'money',
            'createTime' => 'createTime',
            'type' => 'type',
            'status' => 'status'
        );
    }

}
