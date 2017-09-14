<?php

namespace Micro\Models;

class UserProfiles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $coin;

    /**
     *
     * @var integer
     */
    public $cash;

    /**
     *
     * @var integer
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $exp1;

    /**
     *
     * @var integer
     */
    public $exp2;

    /**
     *
     * @var integer
     */
    public $exp3;

    /**
     *
     * @var integer
     */
    public $exp4;

    /**
     *
     * @var integer
     */
    public $exp5;

    /**
     *
     * @var integer
     */
    public $level1;

    /**
     *
     * @var integer
     */
    public $level2;

    /**
     *
     * @var integer
     */
    public $level3;

    /**
     *
     * @var integer
     */
    public $level4;

    /**
     *
     * @var integer
     */
    public $level5;
	
    /**
     *
     * @var integer
     */
    public $level6;
    
    /**
     *
     * @var integer
     */
    public $vipExpireTime;
    
   /**
     *
     * @var integer
     */
    public $vipExpireTime2;

    /**
     *
     * @var integer
     */
    public $questionId;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var float
     */
    public $usefulMoney;

    /**
     *
     * @var integer
     */
    public $isOpenSign;
    
    
    /**
     *
     * @var integer
     */
    public $richRatio;

    /**
     *
     * @var integer
     */
    public $points;

    public function getSource()
    {
        return 'pre_user_profiles';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'uid' => 'uid', 
            'coin' => 'coin', 
            'cash' => 'cash', 
            'money' => 'money', 
            'exp1' => 'exp1', 
            'exp2' => 'exp2', 
            'exp3' => 'exp3', 
            'exp4' => 'exp4', 
            'exp5' => 'exp5', 
            'level1' => 'level1', 
            'level2' => 'level2', 
            'level3' => 'level3', 
            'level4' => 'level4', 
            'level5' => 'level5',
            'level6' => 'level6',
            'vipExpireTime' => 'vipExpireTime',   
            'vipExpireTime2' => 'vipExpireTime2', 
            'questionId' => 'questionId',
            'answer' => 'answer',
            'usefulMoney' => 'usefulMoney',
            'isOpenSign' => 'isOpenSign',
            'richRatio' => 'richRatio',
            'points' => 'points',
 
        );
    }

}
