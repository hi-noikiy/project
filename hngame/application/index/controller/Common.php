<?php
namespace app\index\controller;

use think\Controller;
use think\Url;

class Common extends Controller
{
    /**
     * 基础控制器初始化
     * @author 苏晓信
     */
    public function _initialize()
    {
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());
        url::root('/hngame/public/index.php');
        
        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);
        $config = new \app\index\model\Config();
        $cwhere['status'] = 1;
        $configList = $config->where($cwhere)->select();
        foreach ($configList as $val){
            if($val['k']=='copyright'){
                $this->assign('copyright', $val);
            }else if($val['k']=='copyright_en'){
                $this->assign('copyright_en', $val);
            }else if($val['k']=='approve'){
                $this->assign('approve', $val);
            }else if($val['k']=='approve_en'){
                $this->assign('approve_en', $val);
            }
        }
    }
}