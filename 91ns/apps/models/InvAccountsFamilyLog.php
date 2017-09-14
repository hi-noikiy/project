<?php

namespace Micro\Models;

//客服后台--家族结算日志表
class InvAccountsFamilyLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $logId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $cash;
	
	 /**
     *
     * @var integer
     */
    public $stype;
	
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

    public function getSource() {
        return 'inv_accounts_family_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'logId' => 'logId',
            'uid' => 'uid',
            'cash' => 'cash',
			'stype' => 'stype',
			'basicSalary' => 'basicSalary',
			'rmb' => 'rmb'
        );
    }

}
