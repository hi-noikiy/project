<?php

namespace Micro\Models;

class Users extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $accountId;

    /**
     *
     * @var string
     */
    public $userName;
    
        /**
     *
     * @var string
     */
    public $canSetUserName;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $updateTime;

    /**
     *
     * @var integer
     */
    public $userType;

    /**
     *
     * @var integer
     */
    public $internalType;

    /**
     *
     * @var integer
     */
    public $isChatRecord;
    
    /**
     *
     * @var integer
     */
    public $manageType;

    /**
     *
     * @var integer
     */
    public $password;
    
    /**
     *
     * @var integer
     */
    public $canSetPassword;
    
    /**
     *
     * @var integer
     */
    public $openId;
    
    /**
     *
     * @var integer
     */
    public $key;

    public function getSource() {
        return 'pre_users';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'uid' => 'uid',
            'accountId' => 'accountId',
            'userName' => 'userName',
            'canSetUserName' => 'canSetUserName',
            'password' => 'password',
            'key' => 'key',
            'canSetPassword' => 'canSetPassword',
            'status' => 'status',
            'createTime' => 'createTime',
            'updateTime' => 'updateTime',
            'userType' => 'userType',
            'internalType' => 'internalType',
            'isChatRecord' => 'isChatRecord',
            'manageType' => 'manageType',
            'openId' => 'openId',
        );
    }

}
