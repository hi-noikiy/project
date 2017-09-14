<?php
namespace Micro\Controllers;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Micro\Models\Users;
use Micro\Models\UserProfiles;
class UsermgrController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        return $this->forward("usermgr/user");
    }
    
    public function userAction($p = 1)
    {
        /*$numberPage = $p;
        $parameters = array();

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
        }

        $paginator = new Paginator(array(
            "data"  => $users,
            "limit" => 20,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
        $this->view->users = $users;*/
    }

    public function searchAction()
    {
        $p=$_POST['page'];
    	$numberPage = $p?$p:1;
        $limit = 20;
        $start = ($numberPage - 1) * $limit;
    	$conditions = '1=1';
//        if(isset($_POST['Search']))
//        {
        $username = trim($_POST['username']);
        $uid = intval($_POST['uid']);
        if(!empty($username)){
            $conditions .= " AND userName LIKE '%{$username}%'";
        }

        if(!empty($uid)){
            $conditions .= " AND uid = {$uid}";
        }
//        }

        $where = array(
            'conditions' => $conditions,
            'limit' => "$start, $limit",
        );

        $users = Users::find($where)->toArray();
        foreach($users as $key=>$val){
            $userInfo= \Micro\Models\UserInfo::findfirst("uid=".$val['uid']);
            $users[$key]['nickName']=$userInfo->nickName;
            $users[$key]['tuoType']=$users[$key]['internalType']==2?2:0;
        }
        $count=  Users::count($where['conditions']);
        $return['list']=$users;
        $return['count']=$count;
        self::codeReturn($return, 'ok');
//        $paginator = new Paginator(array(
//            "data"  => $users,
//            "limit" => 20,
//            "page"  => $numberPage
//        ));
//
//        $this->view->page = $paginator->getPaginate();
//        $this->view->users = $users;
    }
	

    public function userCountAction($id = ''){
        if(empty($id) || !is_numeric($id)){
            self::codeReturn('', '参数错误', 1);
        }

        $userCount = new UserProfiles();
        $list = UserProfiles::findFirst($id)->toArray();
        if(empty($list)){
            self::codeReturn('', '未查询到该用户', 1);
        }

        self::codeReturn($list, 'ok');
    }

    public function userCountUpdateAction($id = ''){
        $msg = '';
        if(empty($id) || !is_numeric($id)){
            self::codeReturn('', '参数错误', 1);
        }

        $list = UserProfiles::findFirst($id);
        if(empty($list)){
            self::codeReturn('', '未查询到该用户', 1);
        }

        if($this->request->isPost()){
            $post = $this->request->getPost();
            /* $listArr = $list->toArray();
            foreach($listArr as $key => $val){
                if(isset($post[$key])){
                    $list->$key = $post[$key];
                }
            } */
			if(isset($post)){
				$list->coin = $post['bean'];
				$list->cash = $post['gold'];
				$list->exp1 = $post['vipExp'];
				$list->exp3 = $post['regalExp'];
				$list->exp5 = $post['charmExp'];
				$list->exp4 = $post['fanExp'];
				
			}			
            $ret = $list->save();
            if($ret == FALSE){
                foreach($list->getMessages() as $message) {
                    $msg .= $message;
                }

                self::codeReturn('', $msg, 1);
            }else{
                self::codeReturn('', '修改成功');
            }
        }else{
            self::codeReturn('', '表单错误', 1);
        }
    }

    public function getUserAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->userMgr->getUserList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function delUserAction($uid) {
        if($this->request->isPost()){
            $result = $this->userAuth->userUnreg($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function setUserInteralTypeAction($uid) {
        if ($this->request->isPost()) {
            $internalType = $this->request->getPost('targetInternalType');

            $result = $this->userMgr->setUserInternalType($uid, $internalType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function setUserChatRecordAction($uid) {
        if ($this->request->isPost()) {
            $isChatRecord = $this->request->getPost('isChatRecord');

            $result = $this->userMgr->setUserChatRecord($uid, $isChatRecord);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function innerPayAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $RMB = $this->request->getPost('rmb');

            if(empty($RMB) || !is_numeric($RMB)){
                self::codeReturn('', '参数错误', 1);
            }

            if(empty($uid) || !is_numeric($uid)){
                self::codeReturn('', '参数错误', 1);
            }

            $result = $this->innerPay->pay($RMB,$uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //设置超级管理员
    public function setManageTypeAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $manageType = $this->request->getPost('manageType');

            if(empty($uid) || !is_numeric($uid)){
                self::codeReturn('', '参数错误', 1);
            }

            $result = $this->userMgr->setManageType($uid, $manageType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
//    public function addAction()
//    {
//    	global $config;
//    	$pre = $config->config['passwordPre'];
//        if(isset($_POST['addButton'])){
//            if($this->request->isPost()){
//                $post = $this->request->getPost();
//                $username = $post['username'];
//                $password = $post['password'];
//                if(empty($username) || empty($password)){
//                    $this->flash->notice("用户名密码为空");
//                    return false;
//                }
//
//                $newPwd = md5($password . $pre);
//                $user = new Users();
//                $user->username = $username;
//                $user->accountID = '';
//                $user->password = $newPwd;
//                $user->vip = 0;
//                $user->vip_expire = date('Y-m-d H:i:s');
//                $user->rejectFriend = 0;
//                $user->bagLevel = 0;
//                $user->email = time() . "@qq.com";
//                $user->avatar = 0;
//                $user->status = 1;
//                $user->regdate = time();
//                $ret = $user->save();
//                if($ret == false){
//                	echo "xxx";
//
//                 foreach($user->getMessages() as $message) {
//				        echo $message, "\n";
//				  }
//                }
//                //var_dump($user->getMessages());
//                die;
//            }
//        }
//    }

//    public function deleteAction($id)
//    {
//    	if(empty($id) || !is_numeric($id)){
//    	    return $this->forward("usermgr/user");
//    	}
//
//	    $robot = Users::findFirst($id);
//		if ($robot != false) {
//		    if ($robot->delete() == false) {
//		        $this->flash->notice("删除失败");
//		    } else {
//		        $this->flash->notice("删除成功");
//		    }
//		}
//    }

//    public function editAction($id)
//    {
//        if(empty($id) && !is_numeric($id)){
//            self::codeReturn('', '参数错误', 1);
//        }
//
//        $res = Users::find($id)->toArray();
//        if(empty($res)){
//            self::codeReturn('', '未查询到该用户', 1);
//        }
//
//        self::codeReturn($res, 'ok');
//    }
//
//    public function updateAction($id)
//    {
//        $msg = '';
//        if(empty($id) && !is_numeric($id)){
//            self::codeReturn('', '参数错误', 1);
//        }
//
//        $user = Users::find($id);
//        if(empty($res)){
//            self::codeReturn('', '未查询到该用户', 1);
//        }
//
//        if($this->request->isPost()){
//            $post = $this->request->getPost();
//            $user->vip = intval($post['vip']);
//            $user->vip_expire = $post['vip_expire'];
//            $user->username = $post['username'];
//            $ret = $user->save();
//            if($ret == FALSE){
//                foreach($user->getMessages() as $message) {
//                    $msg .= $message;
//                }
//
//                self::codeReturn('', $msg, 1);
//            }else{
//                self::codeReturn('', '更新成功');
//            }
//        }else{
//            self::codeReturn('', '参数错误', 1);
//        }
//    }
    
    
     //设置托账号
    public function setTuoTypeAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $manageType = $this->request->getPost('manageType');

            if (empty($uid) || !is_numeric($uid)) {
                self::codeReturn('', '参数错误', 1);
            }

            $result = $this->userMgr->setTuoType($uid, $manageType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}












