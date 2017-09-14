<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;

class DiceGameController extends ControllerBase
{

    /**
     * @desc:获取房间游戏信息
     * @return mixed
     */
    public function getInfo(){
        $roomId = $this->request->get('roomId');
        $result = $this->diceGameMgr->getDiceGameInfo($roomId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    /**
     * @desc:获取上一轮游戏信息
     * @return mixed
     */
    public function getLastInfo(){
        $roomId = $this->request->get('roomId');
        $result = $this->diceGameMgr->getLastDiceGameInfo($roomId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    /**
     * @desc:上庄
     * @return mixed
     */
    public function beDeclarer(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $cash = $this->request->getPost('cash');
            $result = $this->diceGameMgr->toBeDeclarer($roomId,$cash);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * @desc:下庄
     * @return mixed
     */
    public function cancelDeclare(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $result = $this->diceGameMgr->declarerLeave($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * @desc:开庄
     * @return mixed
     */
    public function startGame(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $result = $this->diceGameMgr->startDice($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * @desc:押注
     * @return mixed
     */
    public function stake(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $type = $this->request->getPost('type');
            $cash = $this->request->getPost('cash');
            $result = $this->diceGameMgr->playerStake($roomId,$type,$cash);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }


    /**
     * @desc:庄家开盅
     * @return mixed
     */
    public function openDice(){
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $result = $this->diceGameMgr->declarerOpenDice($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }


}