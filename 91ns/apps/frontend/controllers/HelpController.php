<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class HelpController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '帮助';
            $this->view->ns_name = 'help';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
    }
    public function helpConfigureAction()
    {
        
    }
    public function helpVideoAction()
    {
        
    }
    public function helpLiveAction()
    {
        
    }
    public function helpVLevelAction()
    {
        
    }
    public function helpZFLevelAction()
    {
        
    }


    public function helpAnchorLevelAction()
    {
        
    }
    public function helpUserNameAction()
    {
        
    }
    public function helpSigingAction()
    {
        
    }
    public function helpMotoringAction()
    {
        
    }
    public function helpGfitAction()
    {
        
    }
    public function helpUserPwdAction()
    {
        
    }
    public function helpPictureAction()
    {
        
    }
    public function helpGuardianAction()
    {
        
    }
    public function helpBindAction()
    {
        
    }
    public function helpVipAction()
    {
        
    }
    public function helpFAQAction()
    {
        
    }
    public function helpEnterAction()
    {
        
    }
    public function helpCharmAction()
    {
        
    }

}