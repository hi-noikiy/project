<?php

namespace Micro\Controllers;

use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Frameworks\Logic\User\UserAbstract;
use Micro\Frameworks\Logic\User\UserData\UserInfo;

class test {
    public function t1() {
        echo " y ";
    }
};

class PbTestController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');  //use views/layouts/main.volt
        parent::initialize();
    }

    //测试1：采用find，然后再save的方式写数据库，此时另外一个请求更新了同样的字段，会有什么样的错误？
    public function dbTestAction() {
        try {

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //测试2：存储过程的调用，其log是如何的
            // $uid = 1;
            // $consume = 100;
            // $connection = $this->di->get('db');
            // $connection->execute("LOCK TABLES  ".$this->config->mysql->dbname.".pre_user_profiles WRITE;");
            // $sql = "call ".$this->config->mysql->dbname.".money({$uid},{$consume},0,0,0,@res_code);select @rescode;";
            // $res_code = $connection->fetchOne($sql);
            // $connection->execute("UNLOCK TABLES;");

            // if($res_code['res_code'] != 0){
            //     echo "error";
            // }
            // else {
            //     echo "succeed";
            // }
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //测试3：原生的update set和phql的update set的区别
            // phql update set
            $uid = 1011;
            // $sql = "update \Micro\Models\Users set internalType=1 where uid=" . $uid;
            // $query = $this->modelsManager->createQuery($sql);
            // $query->execute();

            $connection = $this->di->get('db');
            $sql = "update pre_users set internalType=1 where uid=".$uid;
            $ret = $connection->query($sql);
            if($ret){
                echo "succeed";
            }
            else {
                echo "error";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        die;
    }



    public function mongodbTestAction() {
        try {
            $this->collection = $this->mongo->collection('user_coordinates');
            $this->collection = $this->mongo->collection('user_coordinates');
            $this->collection = $this->mongo->collection('user_coordinates');
            $this->collection = $this->mongo->collection('user_coordinates');
        }
        catch (\Exception $e) {
            echo "error : ".$e->getMessage();
        }
        echo 5;die;
    }

    public function ajaxAction() {
        $this->status->ajaxReturn($this->status->getCode('OK'));
    }

    public function test3RdAction() {
        //$this->userAuth->userThirdLogin('qq');
        //$this->userAuth->userThirdLogin('weibo');
    }

    public function test3RdReturnAction() {
        //$return_url = "http://openapi.qzone.qq.com/oauth/show?which=Login&display=pc&response_type=code&client_id=101188323&redirect_uri=http%3A%2F%2Fwww.91ns.com%2F91ns.php%3Fprovider%3Dqq%26login&state=1424962887";
        //$return_url = $_SERVER['HTTP_HOST']."?which=Login&display=pc&response_type=code&client_id=101188323&redirect_uri=http%3A%2F%2Fwww.91ns.com%2F91ns.php%3Fprovider%3Dqq%26login&state=1424962887";
        // test
        //$provider = 'qq';
        //$return_url = "www.91ns.com:8083/PbTest/test3RdReturn?code=6DD239AA3A09251711079DC4E4069357&state=1424963732";

        //$provider = $this->request->get('provider');
        //$return_url = $this->request->getHttpHost().$this->request->getURI();
        //$return_url = $_SERVER['HTTP_HOST']; . $_SERVER["REQUEST_URI"];
        //echo $return_url;die;

        //$result = $this->userAuth->userThirdLoginReturn($provider, $return_url);
        //if ($result['code'] == $this->status->getCode('OK')) {
        //    $this->status->ajaxReturn($this->status->getCode('OK'));
        //}

        //$this->status->ajaxReturn($result['code'], $result['data']);
    }

    public function testUserAction() {
        if ($this->session->get($this->config->websiteinfo->authkey) == NULL) {
            $data =  array(
                'id' => 5,
                'name' => 'testName'
            );

            $this->session->set($this->config->websiteinfo->authkey, $data);
        } 

        $sessionData = $this->session->get($this->config->websiteinfo->authkey);
        $uid = $sessionData['id'];
        $user = UserFactory::getInstance($uid);
        $userInfo = $user->getUserInfoObject();

        var_dump($userInfo->getData());die;
    }

    public function enterRoomAction() {
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $result = $this->roomModule->getRoomOperObject()->enterRoom($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function testSecurimageAction() {
        // $img = new \Securimage();
        // $img->show();

        // $this->status->ajaxReturn($result['code'], $result);
    }

    public function testCheckSecurimageAction() {
        //$securimage = new \Securimage();
        //$result = $securimage->check('tapftd');
    }

    public function addFollowAction() {
        $this->userMgr->addFollowTest();
    }

    public function userUnregAction() {
        $username = 'test';
        $result = $this->comm->userUnreg($username);
        if ($result === false) {
            $this->status->ajaxReturn($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $result['code'];
        if ($errorCode != 0) {
            $this->status->ajaxReturn($this->status->getCode('CHATSERVER_RETURN_ERROR'), $result);
        }

        $this->status->ajaxReturn($result['OK'], $result);
    }
}