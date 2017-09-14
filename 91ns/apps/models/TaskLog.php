<?php

namespace Micro\Models;

//用户任务记录表
class TaskLog extends \Phalcon\Mvc\Model {

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
    public $taskId;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $finishTime;

    /**
     *
     * @var integer
     */
    public $finishRate;

    /**
     *
     * @var integer
     */
    public $receiveTime;

    public function getSource() {
        return 'pre_task_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'taskId' => 'taskId',
            'status' => 'status',
            'receiveTime' => 'receiveTime',
            'finishTime' => 'finishTime',
            'finishRate' => 'finishRate',
        );
    }

}
