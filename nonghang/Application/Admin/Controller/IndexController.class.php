<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController {
    public function index(){

    	// for ($i=1; $i <= 1000 ; $i++) { 
    	// 	$zhimu = rand(65,90);
    	// 	$shuzhi = rand(1000000,9999999);

    	// 	echo 'YHC' . chr($zhimu) . $shuzhi . chr(9) . chr(9) . rand(100000,999999) . "\r\n";
    	// }
    	// die();

        $this->display();
    }

    public function goUrl()
    {
        $mid = I('request.mid');
        $chrentMenus = $this->getMenus(2, $mid);
    	foreach ($chrentMenus as $key => $value) {
            $this->success('', array('url' => U($value[2], array('homeMid' => $mid)), 'mid' => $mid));
    		// $this->redirect($value[2]);
    	}
        $this->error('');
    }

}