<?php
namespace app\yfe\controller;

use think\Controller;

class Common extends Controller
{
    /**
     * 基础控制器初始化
     */
    public function _initialize()
    {
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());
        
        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);
    }
}