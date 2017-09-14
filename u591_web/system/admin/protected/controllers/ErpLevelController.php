<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 下午3:38
 */
class ErpLevelController extends Controller{
    public function _condition(&$condition){
        $condition=array();
        if(isset($_POST['title']) && !empty($_POST['title']))
            $condition[]="title like '%{$_POST['title']}%'";


    }

    public function _params(&$params){
        $params = array();
        $model = ErpLevel::model();
        $maxLevel = $model->getMaxLevel();
        $params['maxLevel'] = $maxLevel+1;
    }

}