<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class RankController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '排行';
            $this->view->ns_active = 'rank';
        }
        parent::initialize();
    }

    public function indexAction()
    {
    }

    /*
     * 获取排行
     * */
    public function getRankAction(){
        if($this->request->isPost()){
            $type = $this->request->getPost('type');
            $timeType = $this->request->getPost('dtype');
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
                    $this->proxyError();
                    break;
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}