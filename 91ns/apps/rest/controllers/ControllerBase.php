<?php

namespace Micro\Controllers;


class ControllerBase extends \Phalcon\Mvc\Controller
{

    public function onConstruct() {
          
    }

    protected function proxyError() {
        return $this->status->mobileReturn($this->status->getCode('PROXY_ERROR'));
    }
    
    protected function redirect($uri) {
        $this->response->redirect($uri);
    }
}
