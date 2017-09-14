<?php
class VgModelController extends Controller{
	
	
	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name like '{$_POST['name']}%'";
		if(isset($_POST['icon']) && !empty($_POST['icon']))
			$condition[]="icon='{$_POST['icon']}'";
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
		if($sort)
			$condition[]="sort='$sort'";
		
		$sortArr = array(0=>'类型',1=>'发型', 2=>'皮肤', 3=>'妆容');
	
		$condition['param'] = array('sortArr'=>$sortArr, 'sort'=>$sort);
	}
}
