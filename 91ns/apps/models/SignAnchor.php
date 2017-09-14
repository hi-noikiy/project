<?php

namespace Micro\Models;

class SignAnchor extends \Phalcon\Mvc\Model {

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
    public $familyId;

    /**
     *
     * @var string
     */
    public $realName;

    /**
     *
     * @var integer
     */
    public $gender;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $bank;

    /**
     *
     * @var string
     */
    public $birth;

    /**
     *
     * @var string
     */
    public $cardNumber;

    /**
     *
     * @var string
     */
    public $accountName;

    /**
     *
     * @var string
     */
    public $idCard;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $qq;

    /**
     *
     * @var integer
     */
    public $birthday;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $location;
    
   /**
     *
     * @var string
     */
    public $constellation;


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
    public $money;

    /**
     *
     * @var string
     */
    public $email;

    public function getSource() {
        return 'pre_sign_anchor';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'familyId' => 'familyId',
            'isFamilyCreator' => 'isFamilyCreator',
            'realName' => 'realName',
            'gender' => 'gender',
            'photo' => 'photo',
            'bank' => 'bank',
            'birth' => 'birth',
            'cardNumber' => 'cardNumber',
            'accountName' => 'accountName',
            'idCard' => 'idCard',
            'telephone' => 'telephone',
            'qq' => 'qq',
            'birthday' => 'birthday',
            'address' => 'address',
            'location' => 'location',
            'constellation' => 'constellation',
            'status' => 'status',
            'createTime' => 'createTime',
            'money' => 'money',
            'email' => 'email'
        );
    }

}
