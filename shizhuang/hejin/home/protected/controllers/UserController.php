<?php
/**
 * 2013-9-24 14:33
 * @author Administrator
 *
 */

class UserController extends Controller{
    public function init(){
        parent::init();
        $this->layout = 'column1';
    }

    public function actionlogin(){

        $this->render('login');
    }

    public function actionUserLogin(){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $array = array();
        $array['username'] = $username;
        $array['password'] = $password;
        $array['game_id'] = $this->gameId;
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $url = "http://gunweb.u591.com:83/interface/website/login.php";
        $result = $this->https_post($url, $array);
        $resultArr = json_decode($result, true);

        if (isset($resultArr['status']) && $resultArr["status"] == 0) {
            //登录成功
            $this->setSession('accountInfo', $resultArr['data']);
        }
        echo $result;
        exit();
    }
    public function actionLoginout(){
        $this->setSession('accountInfo', '');
        $this->redirect('/');
    }

    public function actionUserRegister(){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $array = array();
        $array['username'] = $username;
        $array['password'] = $password;
        $array['game_id'] = $this->gameId;
        if(isset($_POST['code'])) {
            $array['code'] = $_POST['code'];
            $codeArr = array('username'=>$username, 'code'=>$_POST['code'], 'game_id'=>$this->gameId);
            $url = "http://gunweb.u591.com:83/interface/website/register.php";
            $rs = $this->https_post($url, $codeArr);
            $rsArr = json_decode($rs, true);
            if(!isset($rsArr['status']) || $rsArr['status'] != 0)
                exit(json_encode(array('status'=>1, 'msg'=>'验证码错误')));
        }
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $url = "http://gunweb.u591.com:83/interface/website/register.php";
        $result = $this->https_post($url, $array);
        $resultArr = json_decode($result, true);
        if (isset($resultArr['status']) && $resultArr["status"] == 0 && isset($resultArr['data'])) {
            //登录成功
            $this->setSession('accountInfo', $resultArr['data']);
        }
        echo $result;
        exit();
    }
    public function actionSmsSent(){
        if(empty($_POST['phone']))
            return false;
        $array = array();
        $array['phone'] = trim($_POST['phone']);
        $array['game_id'] = $this->gameId;
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $url = "http://gunweb.u591.com:83/interface/guanwang/sms_sent.php";
        $result = $this->https_post($url, $array);
        echo $result;
        exit();
    }

}