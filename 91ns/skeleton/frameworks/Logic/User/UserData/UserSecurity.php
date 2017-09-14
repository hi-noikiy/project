<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Activation\Activator;
use \Micro\Models\UserProfiles;

class UserSecurity extends UserDataBase {

    protected $request;
    protected $storage;

    public function __construct($uid) {
        parent::__construct($uid);

        // 该两个请求看下是否是放在这里合适？
        $this->request = $this->di->get('request');
        $this->storage = $this->di->get('storage');
    }

    /*
     * 获取用户安全集
     * */

    public function getSecures() {
        $result = array();
        $issues = $this->getSecurityQuestionId();
        if ($issues) {
            $result['issues'] = $issues;
            $quesResult = \Micro\Models\QuestionConfigs::findfirst($issues);
            $result['question'] = $quesResult->name;
        }
        try {
            $userData = \Micro\Models\UserInfo::findFirst($this->uid);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

        if (!empty($userData)) {
            $userData = $userData->toArray();
            if (!empty($userData['telephone'])) {
                $result['telephone'] = $userData['telephone'];
            }
            if (!empty($userData['email'])) {
                $result['email'] = $userData['email'];
            }
        }

        $result['uid'] = $this->uid;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    /*
     * 发送邮件：验证码
     * */

    static public function sendEmailVerifyCode($email) {
        $di = FactoryDefault::getDefault();
        $session = $di->get('session');
        $config = $di->get('config');
        $activator = new Activator();
        $emailCode = $activator->genSMSCode();
        $session->set($config->websiteinfo->emailcodekey, $emailCode);
        $activator->sendCodeMail($email, $emailCode);
    }

    /*
     * 邮件验证码验证
     * */

    static public function emailCodeVerify($emailCode) {

        $di = FactoryDefault::getDefault();
        $session = $di->get('session');
        $config = $di->get('config');
        $sessionEmailCode = $session->get($config->websiteinfo->emailcodekey);
        if ($sessionEmailCode == $emailCode) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 是否设置过安全问题
     * 返回原问题id
     */
    public function getSecurityQuestionId() {
        $userInfo = UserProfiles::findFirst("uid=" . $this->uid);
        if ($userInfo->questionId) {
            return $userInfo->questionId;
        }
        return 0;
    }

    /**
     * 设置安全问题
     */
    public function editSecurityQuestion($questionId, $answer) {
        try {
            $userInfo = UserProfiles::findFirst("uid=" . $this->uid);
            if (!$userInfo) {
                return FALSE;
            }
            $userInfo->questionId = $questionId;
            $userInfo->answer = $answer;
            $result = $userInfo->save();
            if (!$result) {
                return FALSE;
            }
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('editSecurityQuestion error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /*
     * 更新安全问题
     * */

    public function updateIssues($oldId=0, $oldAnswer='', $id, $answer) {

        //如果是通过手机验证重置问题
        if ($this->session->get($this->config->websiteinfo->unset_question)) {
            $userInfo = UserProfiles::findFirst("uid=" . $this->uid);
            $userInfo->questionId = $id;
            $userInfo->answer = $answer;
            $result = $userInfo->save();
            $this->session->remove($this->config->websiteinfo->unset_question);
            return TRUE;
        }

        $postData['questionId'] = $oldId;
        $postData['answer'] = $oldAnswer;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $postData['questionId'] = $id;
        $postData['answer'] = $answer;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            if (!$this->session->get($this->config->websiteinfo->user_update_answer)) {
                return FALSE;
            }
            $userInfo = UserProfiles::findFirst("uid=" . $this->uid . " AND questionId = " . $oldId . " AND answer = '".$oldAnswer."'");
            if (!$userInfo) {
                return FALSE;
            }
            $userInfo->questionId = $id;
            $userInfo->answer = $answer;
            $result = $userInfo->save();
            if (!$result) {
                return FALSE;
            }
            $this->session->remove($this->config->websiteinfo->user_update_answer);
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('updateIssues error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /*
     * 更新安全问题(回答问题)
     * */

    public function answerIssues($id, $answer) {
        $postData['questionId'] = $id;
        $postData['answer'] = $answer;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $userInfo = UserProfiles::findFirst("uid=" . $this->uid . " AND questionId = " . $id . " AND answer = '".$answer."'");
            if (!$userInfo) {
                return FALSE;
            }
            $this->session->set($this->config->websiteinfo->user_update_answer, TRUE);
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('answerIssues error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /*
     * 设置安全问题
     * */

    public function setIssues($id, $answer) {

        $postData['questionId'] = $id;
        $postData['answer'] = $answer;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return false;
        }

        try {
            $userInfo = UserProfiles::findFirst("uid=" . $this->uid);
            if (!$userInfo || !empty($userInfo->questionId)) {
                return FALSE;
            }
            $userInfo->questionId = $id;
            $userInfo->answer = $answer;
            $result = $userInfo->save();
            if (!$result) {
                return FALSE;
            }
            //设置成功，判断是否符合新手任务
            //$taskMgr = $this->di->get('taskMgr');
            //$taskMgr->setUserTask($this->uid, $this->config->taskIds->setQuestion);
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('setIssues error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * 查询原安全问题答案是否正确
     */
    public function checkSecurityAnswer($questionId, $answer) {
        $userInfo = UserProfiles::findFirst("uid=" . $this->uid . " AND questionId=" . $questionId . " AND answer='" . $answer . "'");
        if ($userInfo) {
            return TRUE;
        }
        return FALSE;
    }

}
