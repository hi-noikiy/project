<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class RankController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '排行榜';
            $this->view->ns_name = 'rank';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $result = $this->rankMgr->getLastWeekVisitorRankAnchor();
        $this->view->lastWeekVisitorAnchor = $result['data'];

        $result = $this->rankMgr->getLastWeekCashRankFamily();
        $this->view->lastWeekCashFamily = $result['data'];

        $result = $this->rankMgr->getLastWeekFollowRankAnchor();
        $this->view->lastWeekFansAnchor = $result['data'];

        $this->view->time = strtotime(date('Y-m-d H').':59:59') - time() + 1;
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

    /*
     * 获取礼物之星
     * */
    public function getGiftRankAction(){
        if($this->request->isPost()){
            $timeType = $this->request->getPost('dtype');
            $result = $this->rankMgr->getFirstGiftRank($timeType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}