<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;

class UserDataBase{
    protected $uid;
    
    protected $di;
    protected $session;
    protected $config;
    protected $status;
    protected $pathGenerator;
    protected $validator;
    protected $userAuth;
    protected $request;
    protected $storage;
    protected $modelsManager;
    protected $taskMgr;
    protected $familyMgr;
    protected $roomModule;
    protected $userMgr;
    protected $baseCode;
    public function __construct($uid){
        $this->uid = $uid;
        $this->di = FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->comm = $this->di->get('comm');
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->request = $this->di->get('request');
        $this->storage = $this->di->get('storage');
        $this->userAuth = $this->di->get('userAuth');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->taskMgr = $this->di->get('taskMgr');
        $this->familyMgr=$this->di->get('familyMgr');
        $this->roomModule = $this->di->get('roomModule');
        $this->userMgr = $this->di->get('userMgr');
        $this->baseCode = $this->di->get('baseCode');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【UserData】 error : '.$errInfo);
    }
}