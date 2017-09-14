<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class FriendsController extends ControllerBase
{
    //获取用户的好友列表
    public function getList() {
        $returnStatusCode = $this->status->getStatus('OK');
    }

    //添加好友
    public function add() {

    }

    //删除好友
    public function del() {
        
    }
}