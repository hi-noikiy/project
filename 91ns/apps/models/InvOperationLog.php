<?php

namespace Micro\Models;

//客服后台--操作日志表
class InvOperationLog extends \Phalcon\Mvc\Model {

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
    public $operaObject;

    /**
     *
     * @var string
     */
    public $operaType;

    /**
     *
     * @var string
     */
    public $operaDesc;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $log1;

    /**
     *
     * @var integer
     */
    public $log2;

    public function getSource() {
        return 'inv_operation_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'operaObject' => 'operaObject',
            'operaType' => 'operaType',
            'operaDesc' => 'operaDesc',
            'createTime' => 'createTime',
            'log1' => 'log1',
            'log2' => 'log2',
        );
    }

}
