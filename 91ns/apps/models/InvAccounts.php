<?php

namespace Micro\Models;

//客服后台--主播/家族结算表
class InvAccounts extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $applyUser;

    /**
     *
     * @var string
     */
    public $auditUser;

    /**
     *
     * @var integer
     */
    public $peoleNum;

    /**
     *
     * @var integer
     */
    public $auditTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $lockUser;

    public function getSource() {
        return 'inv_accounts';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'createTime' => 'createTime',
            'applyUser' => 'applyUser',
            'auditUser' => 'auditUser',
            'peopleNum' => 'peopleNum',
            'auditTime' => 'auditTime',
            'status' => 'status',
            'lockUser' => 'lockUser',
        );
    }

}
