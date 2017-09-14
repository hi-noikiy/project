<?php

namespace Micro\Controllers;

class LeoTestController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');  // use views/layouts/main.volt

        parent::initialize();
    }

    public function regAction(){
        if($this->request->isPost()){
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');

            $result = $this->userAuth->userReg($email, $telephone, $smsCode, $password);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function activeUserAction(){
        $tokenSec = $this->request->get('tokenSec');
        $token = $this->request->get('token');
        $result = $this->userMgr->activeUser($tokenSec, $token);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }

        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    public function resendEmailAction(){
        if($this->request->isPost()){
            $email = $this->request->getPost('email');
            $result = $this->userAuth->resendEmail($email);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }
}