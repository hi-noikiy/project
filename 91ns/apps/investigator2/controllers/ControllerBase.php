<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {

    protected function initialize() {
        //$this->tag->prependTitle('91ns | 后台管理');
        $this->tag->setTitle('91ns | 后台管理');
        $this->view->setTemplateAfter("main");  //use views/layouts/main.volt
        //$this->view->cleanTemplateAfter();

        $this->userSessionInfo();
        $this->invMgrBase->checkLogin(0);

        if (!$this->request->isAjax() && $this->uid) {
            $this->view->setVar('moduleList', $this->getModule());
            $this->view->setVar('currentModule', $this->getCurrentAction());
        } 
    }

    protected function forward($uri) {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
                        array(
                            'controller' => $uriParts[0],
                            'action' => $uriParts[1],
                            'params' => $params
                        )
        );
    }

    protected function proxyError() {
        $this->status->ajaxReturn($this->status->getCode('PROXY_ERROR'));
    }

    public static function codeReturn($data, $info = '', $code = 0) {
        $result = array();
        $result['code'] = $code;
        $result['info'] = $info;
        $result['data'] = $data;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        die;
    }

    //当前模块
    private function getCurrentAction() {
        $control = '';
        $action = '';
        $_url = $this->request->get('_url');
        $urlArr = explode("/", $_url);
        $i = 0;
        foreach ($urlArr as $val) {
            $i == 1 && $control = $val;
            $i == 2 && $action = $val;
            $i++;
        }
        !$control && $control = 'index';
        !$action && $action = 'index';
        return array($control, $action);
    }

    //模块列表
    private function getModule() {
        return $this->invMgrBase->getModule();
    }

    //后台用户信息
    protected function userSessionInfo() {

        $this->uid = $this->session->get($this->config->userSession->invUid);

        if ($this->uid != NULL) {
            $this->view->uid = $this->uid;
            $this->view->username = $this->session->get($this->config->userSession->invUsername);
        } else {
            $this->view->uid = 0;
            $this->view->username = '';
        }
    }

    //页面重定向，url地址变了
    protected function redirect($uri) {
        $this->response->redirect($uri);
    }

}
