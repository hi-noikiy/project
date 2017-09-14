<?php
namespace Micro\Controllers;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Micro\Models\SigningAnchor;

class SigningAnchorController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        return $this->forward("signinganchor/signinganchor");
    }

    public function signinganchorAction($p = 1)
    {
        $numberPage = $p;
        $parameters = array();

        $signinganchor = SigningAnchor::find($parameters);
        if (count($signinganchor) == 0) {
            $this->flash->notice("The search did not find any signinganchor");
        }

        $paginator = new Paginator(array(
            "data"  => $signinganchor,
            "limit" => 20,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
        $this->view->signinganchor = $signinganchor;
    }

    public function signinganchorUpdateAction($id = ''){
        $msg = '';
        $action = $_POST['action'] ? $_POST['action']:'';
        switch($action){
            case 'add':
                $msg = '';
                $signinganchor = new SigningAnchor();
                $post = $this->request->getPost();
                foreach($post as $key => $val){
                    $signinganchor->{$key} = $val;
                }
                $ret = $signinganchor->save();
                if($ret){
                    self::codeReturn('', '更新成功');
                }else{
                    foreach($signinganchor->getMessages() as $message) {
                        $msg .= $message;
                    }

                    self::codeReturn('', $msg, 1);
                }

                break;
            case 'del':
                if(empty($id) || !is_numeric($id)){
                    self::codeReturn('', '参数错误', 1);
                }

                $signinganchor = SigningAnchor::findFirst($id);
                if(empty($signinganchor)){
                    self::codeReturn('', '数据错误', 1);
                }

                if ($signinganchor->delete() == FALSE) {
                    self::codeReturn('', '删除失败', 1);
                } else {
                    self::codeReturn('', '删除成功');
                }

                break;
            case 'update':
                if(empty($id) || !is_numeric($id)){
                    self::codeReturn('', '参数错误', 1);
                }

                $signinganchor = SigningAnchor::findFirst($id);
                if(empty($signinganchor)){
                    self::codeReturn('', '数据错误', 1);
                }

                if($this->request->isPost()){
                    $post = $this->request->getPost();
                    $list = $signinganchor->toArray();
                    foreach($list as $key => $val){
                        if(isset($post[$key])){
                            $signinganchor->$key = $post[$key];
                        }
                    }

                    $ret = $signinganchor->save();
                    if($ret){
                        self::codeReturn('', '更新成功');
                    }else{
                        foreach($signinganchor->getMessages() as $message) {
                            $msg .= $message;
                        }

                        self::codeReturn('', $msg, 1);
                    }
                }else{
                    self::codeReturn('', '表单错误', 1);
                }

                break;
            default:
                if(empty($id) || !is_numeric($id)){
                    self::codeReturn('', '参数错误', 1);
                }

                $signinganchor = SigningAnchor::findFirst($id);
                if(empty($signinganchor)){
                    self::codeReturn('', '数据错误', 1);
                }

                $data = $signinganchor->toArray();
                self::codeReturn($data, 'ok');
                break;
        }
    }

}