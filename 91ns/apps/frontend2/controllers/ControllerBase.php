<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Micro\Frameworks\Logic\User;
use Micro\Frameworks\Logic\Base;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        //$this->tag->prependTitle('91ns | 后台管理');
        //$this->tag->setTitle('91ns');
        //$this->view->cleanTemplateAfter();
        //http header中设置 X-Requested-With = XMLHttpRequest,则会认为是ajax请求
        //$requestedWith = $this->request->getHeader("HTTP_X_REQUESTED_WITH");
        $this->generateChannelType();
        if(!$this->request->isAjax()){
            $this->blockInterceptor();
             $this->setGuestCookie();
        }
        
    }

    //页面内部跳转，url地址栏不变
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

    //页面重定向，url地址变了
    protected function redirect($uri)
    {
        $this->response->redirect($uri);
    }

    protected function proxyError() {
        $this->status->ajaxReturn($this->status->getCode('PROXY_ERROR'));
    }

    protected function pageError() {
        return $this->redirect('');
    }

    protected function pageCheckSuccess($res){
        if($res['code'] != $this->status->getCode('OK')){
            return $this->pageError();
        }
    }

    protected function generateChannelType() {
        $serverName = $this->request->getServerName();

        $nsName = "91ns";
        $douziName = "douzi";
        //判断是否存在   返回bool值
        if (stripos($serverName, $douziName) !== false) {
            //$newstr = str_replace($findme,' ',$mystring, );//执行替换操作
            $this->config->channelType = 2;
        }
        else if (stripos($serverName, $nsName) !== false) {
            $this->config->channelType = 1;
        }
        /*else {
            $this->config->channelType = 1;
        }*/
    }

    protected function blockInterceptor() {
        //临时添加。过后删除
        $serverName = $this->request->getServerName();
        if(stripos($serverName, '91ns.cn') !== false ){//|| stripos($serverName, 'putianmm.com') !== false
            $this->view->aboutUrl = '/about.cn.html';
            $this->view->ns_iscn = 1;
        }
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
        $this->view->jsURL = $this->config->application->assetsDebug?'web/js2/':$this->config->url->jsURL;
        $this->view->cssURL = $this->config->application->assetsDebug?'web/css2/':$this->config->url->cssURL;

        if (!$this->config->application->debug) {
            $this->view->isAddGoogleCode = true;
        }

        $user = $this->userAuth->getUser();
        if($user != NULL){
            $uid = $user->getUid();
            $this->view->ns_userUid = $uid;
            $this->view->isSignAnchor = $user->getUserInfoObject()->isSignAnchorNew($uid);
        }else{
            $this->view->ns_userUid = 0;
            $this->view->isSignAnchor = 0;
            //是否有自动登录
            /*if ($this->cookies->has($this->config->websitecookies->userPassword) && $this->cookies->has($this->config->websitecookies->userName) ) {
                $userName = trim($this->cookies->get($this->config->websitecookies->userName)->getValue());
                $userPassword = trim($this->cookies->get($this->config->websitecookies->userPassword)->getValue());
                //登录
                if($userName != '0'){
                    $autoLogin = false;
                    $result = $this->userAuth->userLogin($userName, $userPassword);
                    if ($result['code'] == $this->status->getCode('OK')) {
                        $autoLogin = true;
                    }else{
                        //第三方自动登录
                        $result = $this->userAuth->userLogin($userName, $userPassword, 1);
                        if ($result['code'] == $this->status->getCode('OK')) {
                            $this->view->ns_source_login = $result['data']['ns_source'];
                            $autoLogin = true;
                        }
                    }
                    if($result['data']['ns_source']){
                        $this->view->ns_source_login = $result['data']['ns_source'];
                    }else{
                        $this->view->ns_source_login = 'normal';
                    }
                    //自动登录
                    if($autoLogin){
                        $user = $this->userAuth->getUser();
                        if($user != NULL){
                            $this->view->ns_userUid = $user->getUid();
                        }
                        $time = 2592000;
                        $this->cookies->set($this->config->websitecookies->userName, $userName, time() + $time);
                        $this->cookies->set($this->config->websitecookies->userPassword, $userPassword, time() + $time);
                    }
                }
            }*/
        }
        if($this->view->ns_userUid != 0){
            $this->view->ns_userType = $user->getType();
        }
        $this->view->webType = $this->config->webType[$this->config->channelType];
    }


    public function codeReturn($data,$info='',$status=0,$type='JSON') {
        $result = array();
        $result['status'] = $status;
        $result['info'] = $info;
        $result['data'] = $data;
        //扩展ajax返回数据, 在Action中定义function ajaxAssign(&$result){} 方法 扩展ajax返回数据。
        if(method_exists($this,'ajaxAssign')) 
            $this->ajaxAssign($result);
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        if(strtoupper($type)=='JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:text/html; charset=utf-8');
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }elseif(strtoupper($type)=='XML'){
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($result));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
        }else{
            // TODO 增加其它格式
        }
    }

    public function writer($filename, $data = '', $mode='w'){
        if(trim($filename)){
            $file = @fopen($filename, $mode);
              $filedata = @fwrite($file, $data);
            @fclose($file);
        }
        if(!is_file($filename)){
            die('Sorry,'.$filename.' file write in failed!');
        }
    }
    
    //给访问者设置cookie
    private function setGuestCookie() {
        $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
        $log->setGuestLog();
    }

 
}
