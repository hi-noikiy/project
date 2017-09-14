<?php

namespace Micro\Models;

//客服后台--结算日志表
class InvSettleLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $changeId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $rmb;

    /**
     *
     * @var string
     */
    public $auditUser;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $auditTime;

    /**
     *
     * @var string
     */
    public $auditImg;

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
     * @var string
     */
    public $remark;

    public function getSource() {
        return 'inv_settle_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'changeId' => 'changeId',
            'uid' => 'uid',
            'rmb' => 'rmb',
            'auditUser' => 'auditUser',
            'createTime' => 'createTime',
            'auditTime' => 'auditTime',
            'auditImg' => 'auditImg',
            'status' => 'status',
			'type' => 'type',
			'remark' => 'remark',
        );
    }

}
