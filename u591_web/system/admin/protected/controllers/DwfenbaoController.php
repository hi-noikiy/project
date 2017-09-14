<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 分包控制类
* ==============================================
* @date: 2016-11-30
* @author: luoxue
* @version:
*/
class DwfenbaoController extends Controller{
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name like '%{$_POST['name']}%'";
		if(isset($_POST['fenbaoid']) && !empty($_POST['fenbaoid']))
			$condition[]="fenbao_id={$_POST['fenbaoid']}";


	}

	public function _params(&$params){
        $params = array('dwAccessArr'=>$this->getDwAccess());
    }
	public function _order(&$order){
		$order = 'id desc';
	}
    //返回字段权限数组
	private function getDwAccess(){
	    $model = new AccessTable;
        $dwAccessArr = $model->getAccess('web_dwFenbao', $this->mangerInfo['id']);
        return $dwAccessArr;
    }
}