<?php
namespace Micro\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Micro\Models\InvUser;

class GmmgrController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        return $this->forward("gmmgr/user");
    }
    
    public function userAction($p = 1)
    {
    }

    public function searchAction($p = 1)
    {
    	$numberPage = $p;
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
        self::codeReturn($users, 'ok');
//        $paginator = new Paginator(array(
//            "data"  => $users,
//            "limit" => 20,
//            "page"  => $numberPage
//        ));
//
//        $this->view->page = $paginator->getPaginate();
//        $this->view->users = $users;
    }

    public function addUserAction() {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $roleId = $this->request->getPost('roleId');

            $result = $this->invMgrBase->addInvUser($username, $password, $roleId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
	

    public function getUserInfoAction($uid = ''){
        if(empty($uid) || !is_numeric($uid)){
            self::codeReturn('', '参数错误', 1);
        }

        $result = $this->invMgrBase->getInvUserInfo($uid);
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    public function updateUserInfoAction($uid = ''){
        $msg = '';
        if(empty($uid) || !is_numeric($uid)){
            self::codeReturn('', '参数错误', 1);
        }

        if($this->request->isPost()){
            $post = $this->request->getPost();

            $info['password'] = $post['password'];
            $info['roleId'] = $post['roleId'];

            $result = $this->invMgrBase->editInvUser($uid, $info);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function getUserAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->invMgrBase->getInvUsers();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function delUserAction($uid) {
        if($this->request->isPost()){
            //$result = $this->userAuth->userUnreg($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}












