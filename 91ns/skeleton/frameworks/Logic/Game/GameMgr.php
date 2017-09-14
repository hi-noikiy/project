<?php

namespace Micro\Frameworks\Logic\Game;

use Phalcon\DI\FactoryDefault;

class GameMgr {

    protected $di;
    protected $userAuth;
    protected $taskData;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $roomModule;
    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->userAuth = $this->di->get('userAuth');
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->comm = $this->di->get('comm');
        $this->roomModule = $this->di->get('roomModule');
    }


    public function gamePush($roomId, $type, $content){
        $postData['id'] = $content;
        $postData['content'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $roomUid = 0;
        $roomData = \Micro\Models\Rooms::findFirst('roomId = ' . intval($roomId));
        if(!empty($roomData)){
            $roomUid = $roomData->uid;
        }

        $ArraySubData['controltype'] = "gameFace";
        $roomModule = $this->di->get('roomModule');
        $userData = $roomModule->getRoomOperObject()->setBroadcastParam($user, $roomUid);
        $data['type'] = $type;
        $data['userdata'] = $userData;
        $data['content'] = $content;
        $ArraySubData['data']=$data;
        $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }


}
