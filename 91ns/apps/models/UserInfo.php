<?php

namespace Micro\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class UserInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $nickName;

    /**
     *
     * @var string
     */
    public $realName;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $signature;
    /**
     *
     * @var integer
     */
    public $gender;

    /**
     *
     * @var integer
     */
    public $birthday;
    /**
     *
     * @var integer
     */
    public $seclevel;
    /**
     *
     * @var string
     */
    public $bank;

    /**
     *
     * @var string
     */
    public $cardNumber;

    /**
     *
     * @var string
     */
    public $ID;
    
    /**
     *
     * @var string
     */
    public $city;

    /**
     * Validations and business logic
     */
    /*public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => false,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }*/

    public function getSource()
    {
        return 'pre_user_info';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'uid' => 'uid',
            'realName' => 'realName',
            'nickName' => 'nickName', 
            'avatar' => 'avatar',
            'email' => 'email', 
            'telephone' => 'telephone', 
            'gender' => 'gender', 
            'birthday' => 'birthday',
            'signature' => 'signature',
            'seclevel' => 'seclevel',
            'cardNumber' => 'cardNumber',
            'bank' => 'bank',
            'ID' => 'ID',
            'city' => 'city',
        );
    }

}
