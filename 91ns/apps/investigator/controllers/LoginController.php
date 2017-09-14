<?php

namespace Micro\Controllers;

class LoginController extends ControllerBase {

    public function initialize() {
        // parent::initialize();
        $this->tag->setTitle('91ns | 后台管理');
    }

    public function indexAction() {
        $result = $this->invMgrBase->checkLogin(1);
        if ($result) {//已登录
            $this->redirect('index');
        }
    }

    //退出登录
    public function loginoutAction() {
        $this->invMgrBase->loginOut();
        $this->redirect('login');
    }

}
