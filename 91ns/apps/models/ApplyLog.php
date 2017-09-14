<?php

namespace Micro\Models;

class ApplyLog extends \Phalcon\Mvc\Model {

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
    public $targetId;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $reason;

    /**
     *
     * @var string
     */
    public $auditUser;
    
        /**
     *
     * @var integer
     */
    public $auditTime;
    
   /**
     *
     * @var integer
     */
    public $isRead;

    public function getSource() {
        return 'pre_apply_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'targetId' => 'targetId',
            'description' => 'description',
            'type' => 'type',
            'createTime' => 'createTime',
            'status' => 'status',
            'auditUser' => 'auditUser',
            'auditTime' => 'auditTime',
            'reason' => 'reason',
            'isRead' => 'isRead',
        );
    }

}
