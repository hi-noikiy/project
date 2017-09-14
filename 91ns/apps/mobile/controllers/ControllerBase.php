<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Micro\Frameworks\Logic\User;
use Micro\Frameworks\Logic\Base;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        if(!$this->request->isAjax()){
            $this->generateChannelType();
            $this->blockInterceptor();
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
        /*header('Location:'.$uri);
        exit();*/
        $this->response->redirect($uri);
    }

    protected function proxyError()
    {
        $this->status->ajaxReturn($this->status->getCode('PROXY_ERROR'));
    }

    protected function pageError()
    {
        return $this->redirect('');
    }

    protected function pageCheckSuccess($res)
    {
        if($res['code'] != $this->status->getCode('OK')){
            return $this->pageError();
        }
    }

    protected function generateChannelType()
    {
        $serverName = $this->request->getServerName();

        $nsName = "91ns";
        $douziName = "douzi";
        //判断是否存在   返回bool值
        if (stripos($serverName, $douziName) !== false) {
            //$newstr = str_replace($findme,' ',$mystring, );//执行替换操作
            $this->config->channelType = 2;
        }else if (stripos($serverName, $nsName) !== false) {
            $this->config->channelType = 1;
        }
    }

    protected function blockInterceptor()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;

        if (!$this->config->application->debug) {
            $this->view->isAddGoogleCode = true;
        }

        $user = $this->userAuth->getUser();
        if($user != NULL){
            $this->view->ns_userUid = $user->getUid();
        }else{
            $this->view->ns_userUid = 0;
            //是否有自动登录
            /*if ($this->cookies->has($this->config->websitecookies->userPassword) && $this->cookies->has($this->config->websitecookies->userName) ) {
                //$time = 2592000;//
                $userName = trim($this->cookies->get($this->config->websitecookies->userName)->getValue());
                $userPassword = trim($this->cookies->get($this->config->websitecookies->userPassword)->getValue());
                //var_dump($userName);die;
                //登录
                if($userName != '0')
                $result = $this->userAuth->userLogin($userName, $userPassword);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $user = $this->userAuth->getUser();
                    if($user != NULL){
                        $this->view->ns_userUid = $user->getUid();
                    }
                    $time = 2592000;
                    $this->cookies->set($this->config->websitecookies->userName, $userName, time() + $time);
                    $this->cookies->set($this->config->websitecookies->userPassword, $userPassword, time() + $time);
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
}
