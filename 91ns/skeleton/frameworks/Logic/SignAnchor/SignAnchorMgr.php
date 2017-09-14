<?php

namespace Micro\Frameworks\Logic\SignAnchor;

use Phalcon\DI\FactoryDefault;
use Micro\Models\Family;
use Micro\Models\SignAnchor;
use Micro\Frameworks\Logic\User\UserData\UserInfo;
class SignAnchorMgr{

    protected $di;
    protected $status;
    protected $validator;
    protected $logger;

    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->logger = $this->di->get('logger');
    }

    /**
     * 账号冻结
     *
     * @param $uid
     */
    public function accountFrozen($uid){
        $userInfo = new UserInfo($uid);
        return $userInfo->accountFrozen($uid);
    }

    /**
     * 添加签约主播
     *
     * @param $uid
     * @param $realName
     * @param $gender
     * @param $photo
     * @param $bank
     * @param $birth
     * @param $cardNumber
     * @param $accountName
     * @param $idCard
     * @param $telephone
     * @param $qq
     * @param $birthday
     * @param $address
     * @param $status
     * @return mixed
     */
    public function addSignAnchor($uid, $realName, $gender, $photo, $bank, $birth, $cardNumber,
                                  $accountName, $idCard, $telephone, $qq, $birthday, $address, $status) {
        try {
            $dbdata = new SignAnchor();
            $dbdata->uid = $uid;
            $dbdata->familyId = 0;
            $dbdata->realName = $realName;
            $dbdata->gender = $gender;
            $dbdata->photo = $photo;
            $dbdata->bank = $bank;
            $dbdata->birth = $birth;
            $dbdata->cardNumber = $cardNumber;
            $dbdata->accountName = $accountName;
            $dbdata->idCard = $idCard;
            $dbdata->telephone = $telephone;
            $dbdata->qq = $qq;
            $dbdata->birthday = $birthday;
            $dbdata->address = $address;
            $dbdata->status = $status;
            $dbdata->createTime = time();
            $dbdata->money = 0.000;
            $dbdata->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 是否签约
     */

    public function isSignAnchor($uid){
        $userInfo = new UserInfo($uid);
        return $userInfo->isSignAnchor($uid);
    }
}