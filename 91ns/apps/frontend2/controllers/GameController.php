<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;


class GameController extends ControllerBase{
    public function initialize(){
        parent::initialize();
    }

    public function sendGameFaceAction(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $type = $this->request->getPost('type');
            $content = intval($this->request->getPost('content'));
            $result = $this->gameMgr->gamePush($roomId, $type, $content);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


}
