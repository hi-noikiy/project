<?php
namespace Micro\Controllers;
class LoginController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
       
    }

    public function loginAction() {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // 临时做法
            if ($username != 'admin') {
                $this->status->ajaxReturn($this->status->getCode('USER_NOT_EXIST'));
            }

            $pass = 'admin821225';
            if ($password != md5($pass)) {
                $this->status->ajaxReturn($this->status->getCode('USER_NOT_EXIST'));
            }

            $this->session->set($this->config->backend->authkey, 'has login');
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        $this->proxyError();
    }

    public function logoutAction() {
        $this->session->remove($this->config->backend->authkey);
    }
}