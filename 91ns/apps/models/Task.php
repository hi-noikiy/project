<?php

namespace Micro\Models;

//任务数据表
class Task extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $taskId;

    /**
     *
     * @var string
     */
    public $taskName;

    /**
     *
     * @var string
     */
    public $taskDes;

    /**
     *
     * @var integer
     */
    public $taskType;

    /**
     *
     * @var integer
     */
    public $taskReward;

    /**
     *
     * @var integer
     */
    public $taskSort;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $rewardType;

    /**
     *
     * @var string
     */
    public $sourceReward;

    /**
     *
     * @var integer
     */
    public $showStatus;

    public function getSource() {
        return 'pre_task';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'taskId' => 'taskId',
            'taskName' => 'taskName',
            'taskDes' => 'taskDes',
            'taskType' => 'taskType',
            'taskReward' => 'taskReward',
            'taskSort' => 'taskSort',
            'status' => 'status',
            'type' => 'type',
            'rewardType' => 'rewardType',
            'sourceReward' => 'sourceReward',
            'showStatus' => 'showStatus',
        );
    }

}
