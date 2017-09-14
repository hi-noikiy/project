<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/13
 * Time: 下午3:34
 */
class AddGoodController extends Controller{
    public function _condition(&$condition){
        $condition=array();
        if(isset($_POST['name']) && !empty($_POST['name']))
            $condition[]="name like '%{$_POST['name']}%'";
    }

}