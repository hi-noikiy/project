<?php

namespace Micro\Models;

//客服后台--结算日志表
class InvAccountsLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $accountId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $cash;

    /**
     *
     * @var integer
     */
    public $basicSalary;

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
    public $settleType;
	
	 /**
     *
     * @var string
     */
    public $remark;

    public function getSource() {
        return 'inv_accounts_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'accountId' => 'accountId',
            'uid' => 'uid',
            'type' => 'type',
            'cash' => 'cash',
            'basicSalary' => 'basicSalary',
            'rmb' => 'rmb',
            'auditUser' => 'auditUser',
            'auditTime' => 'auditTime',
            'auditImg' => 'auditImg',
            'status' => 'status',
			'settleType' => 'settleType',
			'remark' => 'remark',
        );
    }

}
