<?php
namespace Micro\Controllers;

class FamilymgrController extends ControllerBase
{
    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        return $this->forward("familymgr/family");
    }

    public function familyAction() {}

    public function getFamilyAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->familyMgr->getFamilyList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateFamilyAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $shortName = $post['shortName'];
                    $announcement = $post['announcement'];
                    $description = $post['description'];
                    $logo = $post['logo'];
                    $status = $post['status'];
                    $result = $this->familyMgr->addFamily($name, $shortName, $announcement, $description, $logo, $status);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->familyMgr->delFamily($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->familyMgr->updateFamily($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->familyMgr->getFamilyInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    /*public function searchFamilyAction($p = 1)
    {
        $numberPage = $p;
        $limit = 20;
        $start = ($numberPage - 1) * $limit;
        $conditions = '1=1';
        $fname = trim($_POST['fname']);
        if(!empty($username)){
            $conditions .= " AND fname LIKE '%{fname}%'";
        }

        $where = array(
            'conditions' => $conditions,
            'limit' => "$start, $limit",
        );

        $familys = Familys::find($where)->toArray();
        self::codeReturn($familys, 'ok');
    }*/

    public function signAnchorAction() {}

    public function getSignAnchorAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->familyMgr->getSignAnchorList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateSignAnchorAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $uid = $post['uid'];
                    $familyId = $post['familyId'];
                    $isFamilyCreator = $post['isFamilyCreator'];
                    $realName = $post['realName'];
                    $gender = $post['gender'];
                    $photo = $post['photo'];
                    $bank = $post['bank'];
                    $birth = $post['birth'];
                    $cardNumber = $post['cardNumber'];
                    $accountName = $post['accountName'];
                    $idCard = $post['idCard'];
                    $telephone = $post['telephone'];
                    $qq = $post['qq'];
                    $birthday = $post['birthday'];
                    $address = $post['address'];
                    $status = $post['status'];
                    $result = $this->familyMgr->addSignAnchor($uid, $familyId, $isFamilyCreator, $realName, $gender, $photo, $bank, $birth, $cardNumber, 
                                                              $accountName, $idCard, $telephone, $qq, $birthday, $address, $status);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->familyMgr->delSignAnchor($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->familyMgr->updateSignAnchor($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->familyMgr->getSignAnchorInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }
}