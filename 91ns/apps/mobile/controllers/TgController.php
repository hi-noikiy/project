<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class TgController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '推广';
            $this->view->ns_active = 'tg';
        }
        parent::initialize();
    }

    public function indexAction() {
        
        $str = $this->request->get('str');

        if($str){// 兼容旧的新用户推广链接
            $val = '?str=' . $str;
        }else{// 新的推广链接
            $uid = $this->request->get('uid');
            $utm_source = $this->request->get('utm_source');
            $utm_medium = $this->request->get('utm_medium');

            $res = \Micro\Models\Recommend::findfirst('uid = ' . $uid . ' and utmSource = "' . $utm_source . '" and utmMedium = "' . $utm_medium . '"');
            if(!empty($res)){
                $val = '?uid=' . $uid . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium;
            }else{
                $val = '';
            }
        }

        // 判断是否PC
        $isMobile = $this->normalLib->isMobile();
        if($isMobile){
            header("Location:http://m.91ns.com/activities/recommendReceive" . $val);
            exit;
        }

        $jumpUid = $this->normalLib->getHotRoom();

        $param = $jumpUid ? ($jumpUid . $val) :  $val;

        header('Location:http://www.91ns.com/' . $param);
        exit;
    }
}