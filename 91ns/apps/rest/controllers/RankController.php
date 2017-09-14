<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class RankController extends ControllerBase
{
    /*
     * 获取排行
     * */
    public function getRank(){
        if($this->request->isGet()){
            $type = $this->request->get('type');
            $timeType = $this->request->get('dtype');
            switch($type){
                case 0:
                    $result = $this->rankMgr->getStarRank($timeType);
                    break;
                case 1:
                    $result = $this->rankMgr->getRichRank($timeType);
                    break;
                case 2:
                    $result = $this->rankMgr->getCharmRank($timeType);
                    break;
                case 3:
                    $result = $this->rankMgr->getFamilysRank($timeType);
                    break;
                default:
                    return $this->proxyError();
                    break;
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * 获取礼物之星
     * */
    public function getGiftRank(){
        if($this->request->isGet()){
            $timeType = $this->request->get('dtype');
            $result = $this->rankMgr->getFirstGiftRank($timeType);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}