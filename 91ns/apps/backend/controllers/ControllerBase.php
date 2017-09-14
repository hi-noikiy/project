<?php
namespace Micro\Controllers;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        //$this->tag->prependTitle('91ns | 后台管理');
        $this->tag->setTitle('91ns | 后台管理');
        $this->view->setTemplateAfter('main');  //use views/layouts/main.volt
        //$this->view->cleanTemplateAfter();
    }

    protected function forward($uri)
    {
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
        echo json_encode($result, JSON_UNESCAPED_UNICODE);die;
    }
}
