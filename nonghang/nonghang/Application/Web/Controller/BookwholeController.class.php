<?php
namespace Web\Controller;
use Think\Controller;
class BookwholeController extends Controller {
    public function index(){
        $this->assign('pageId', 'page-bookwholeIndex');
       $this->display();
    }

	public function orderInfo(){
	    $this->assign('pageId', 'page-orderInfo');
       $this->display('orderInfo');
    }

	public function payMent(){
	   $this->assign('pageId', 'page-payment');
       $this->display('payMent');
    }

	public function paySuccess(){
	   $this->assign('pageId', 'page-paySuccess');
       $this->display('paySuccess');
    }

}